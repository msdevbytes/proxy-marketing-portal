<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    use \App\Traits\RedirectIndex;

    protected static string $resource = ReservationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reservation_expiry'] = Carbon::now()->addHours(2);

        return parent::mutateFormDataBeforeCreate($data);
    }
}
