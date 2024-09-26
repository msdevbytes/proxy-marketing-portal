<?php

namespace App\Filament\Pages\Auth;

use App\Enums\Gender;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Support\Enums\MaxWidth;

class Register extends BaseRegister
{

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['status'] = 0;
        return $data;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getCnicFormComponent(),
                        $this->getPhoneNumberFormComponent(),
                        $this->getGenderFormComponent(),
                        $this->getAddressFormComponent(),
                        $this->getCnicImageFormComponent(),
                        $this->getBankAccountNameFormComponent(),
                        $this->getBankAccountNumberFormComponent(),

                    ])
                    ->statePath('data')
            ),
        ];
    }


    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        return app(RegistrationResponse::class);
    }

    protected function getCnicFormComponent(): Component
    {
        return TextInput::make('cnic')
            ->label('CNIC No.')
            ->numeric()
            ->required();
    }

    protected function getPhoneNumberFormComponent(): Component
    {
        return TextInput::make('phone_number')
            ->label('Phone Number')
            ->required();
    }
    protected function getGenderFormComponent(): Component
    {
        return Radio::make('gender')
            ->label('Gender')
            ->options(function () {
                $gender = [];
                foreach (Gender::cases() as $case) {
                    $gender[$case->value] = $case->value;
                }
                return $gender;
            })
            ->required()->inline();
    }
    protected function getAddressFormComponent(): Component
    {
        return TextInput::make('address')
            ->required();
    }
    protected function getImageFormComponent(): Component
    {
        return TextInput::make('image')
            ->label('Profile Image')
            ->required();
    }
    protected function getCnicImageFormComponent(): Component
    {
        return FileUpload::make('cnic_image')->label('CNIC Picture');
    }

    protected function getBankAccountNameFormComponent(): Component
    {
        return TextInput::make('bank_account_name')
            ->required();
    }
    protected function getBankAccountNumberFormComponent(): Component
    {
        return TextInput::make('bank_account_number')
            ->required();
    }
    protected function getBankAccountCityIdFormComponent(): Component
    {
        return Select::make('bank_account_city_id')
            ->label('Bank A/C City')
            ->relationship('bankAccountCity', titleAttribute: 'name')
            ->searchable()
            ->preload()
            ->native(false);
    }
    protected function getCityIdFormComponent(): Component
    {
        return Select::make('city_id')
            ->relationship('city', titleAttribute: 'name')
            ->searchable()
            ->preload()
            ->native(false);
    }
}
