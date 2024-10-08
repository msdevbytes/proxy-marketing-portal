<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Webbingbrasil\FilamentCopyActions\Pages\Actions\CopyAction;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make('edit')->color("success"),
            Actions\DeleteAction::make(),
            CopyAction::make('Copy')->copyable(function ($record) {
                return sprintf(
                    '
                    Product ID: %s
                    Product Name: %s
                    Product Brand: %s
                    Product Link: %s
                    Amazon Sold by: %s
                    Amazon Keyword: %s
                ',
                    nl2br($record->id),
                    nl2br($record->name),
                    nl2br($record->product_brand),
                    nl2br($record->product_link),
                    nl2br($record->seller),
                    nl2br($record->keyword)
                );
            })->button()->color('primary')
        ];
    }
}
