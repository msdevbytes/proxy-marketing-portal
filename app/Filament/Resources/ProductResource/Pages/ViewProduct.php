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
                    %s
                    %s
                    %s
                    %s
                    %s
                    %s
                ',
                    nl2br("Product ID: " . $record->id),
                    nl2br("Product Name: " . $record->name),
                    nl2br("Product Brand: " . $record->product_brand),
                    nl2br("Product Link: " . $record->product_link),
                    nl2br("Amazon Sold by: " . $record->seller),
                    nl2br("Amazon Keyword: " . $record->keyword)
                );
            })->button()->color('primary')
        ];
    }
}
