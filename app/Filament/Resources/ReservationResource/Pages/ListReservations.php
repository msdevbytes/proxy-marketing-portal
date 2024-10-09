<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Auth;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

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

    public function getTabs(): array
    {
        if (Auth::useR()->isSuperAdmin()) {

            return [
                'All' => Tab::make(),
                'Active' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->whereRaw('reservation_expiry > STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'))),
                'Expired' => Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->whereRaw('reservation_expiry < STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'))),
            ];
        }
        return [];
    }
}
