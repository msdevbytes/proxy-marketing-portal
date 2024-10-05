<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return parent::table($table->defaultSort('created_at', 'desc'));
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'active' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', true)),
            'inactive' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', false)),
            'PM' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->role('PM');
                }),
            'PMM' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->role('PMM');
                }),
            'Manager' => Tab::make()
                ->modifyQueryUsing(function ($query) {
                    return $query->role('manager');
                }),
        ];
    }
}
