<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Market;
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
        $data['product_price'] = $data['product_price'] ?? 0;
        $data['portal_fee'] = Market::find($data['market_id'])?->portal_fee;

        return parent::mutateFormDataBeforeCreate($data);
    }
}
