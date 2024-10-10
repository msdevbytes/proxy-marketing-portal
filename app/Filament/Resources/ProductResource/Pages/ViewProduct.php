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
                    %s
                    %s
                    %s
                    %s
                ',
                    nl2br("Product ID:"),
                    nl2br($record->id),
                    nl2br("Product Name: "),
                    nl2br($record->name),
                    nl2br("Product Brand: "),
                    nl2br($record->product_brand),
                    nl2br("Product Link: "),
                    nl2br($record->product_link),
                    nl2br("Amazon Sold by: "),
                    nl2br($record->seller),
                    nl2br("Amazon Keyword: "),
                    nl2br($record->keyword)
                );
            })->button()->color('primary')
        ];
    }
}
