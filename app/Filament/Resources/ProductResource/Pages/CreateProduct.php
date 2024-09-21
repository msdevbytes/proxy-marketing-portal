<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Auth;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use \App\Traits\RedirectIndex;
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = $data['user_id'] ?? Auth::user()->id;

        return parent::mutateFormDataBeforeCreate($data);
    }
}
