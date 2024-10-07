<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Auth;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    function beforeSave(): void
    {
        if ($this->data['id'] == Auth::user()->id) {
            Notification::make()
                ->title('Hey ' . Auth::user()?->name)
                ->body("You can not update your account from here.")
                ->icon('heroicon-o-information-circle')
                ->color("danger")
                ->send();
            $this->halt();
        }
    }
}
