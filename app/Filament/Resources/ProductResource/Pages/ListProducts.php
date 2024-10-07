<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Closure;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table->defaultSort('created_at', 'desc'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Product'),
        ];
    }
}
