<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table->defaultSort('created_at', 'desc'));
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
