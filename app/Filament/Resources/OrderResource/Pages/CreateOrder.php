<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use Auth;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use \App\Traits\RedirectIndex;
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = $data['status'] ?? OrderStatus::ORDERED;
        $data['user_id'] = $data['user_id'] ?? Auth::user()->id;

        return parent::mutateFormDataBeforeCreate($data);
    }


    protected function beforeCreate(): void
    {
        if (!Auth::user()->checkProductReservation($this->data['product_id'])) {
            Notification::make()
                ->title('Hey ' . Auth::user()?->name)
                ->body("Please reserve this product before order")
                ->icon('heroicon-o-information-circle')
                ->color("danger")
                ->send();
            $this->halt();
        }
    }
}
