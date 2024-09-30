<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();
        if ($user->email_verified_at == null) {
            Filament::auth()->logout();
            Notification::make()
                ->title('Account On Hold')
                ->body("Please wait your account is on hold for furthur verification")
                ->danger()
                ->icon('heroicon-o-exclamation-circle')
                ->iconColor('danger')
                ->duration(10000)
                ->send();
            throw ValidationException::withMessages([
                'data.email' => "Please wait your account is on hold for furthur verification",
            ]);
        }

        if ($user->acc_deactive_at != null || !$user->status) {
            Filament::auth()->logout();
            Notification::make()
                ->title('Account Deactivated')
                ->body('Your account has been deactivated by admin.')
                ->danger()
                ->duration(10000)
                ->send();
            throw ValidationException::withMessages([
                'data.email' => "This email is deactevated by admin.",
            ]);
        }

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
