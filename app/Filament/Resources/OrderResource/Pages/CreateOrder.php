<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use \App\Traits\RedirectIndex;
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = $data['status'] ?? OrderStatus::ORDERED;

        return parent::mutateFormDataBeforeCreate($data);
    }
}
