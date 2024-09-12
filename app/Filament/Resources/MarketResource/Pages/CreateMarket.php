<?php

namespace App\Filament\Resources\MarketResource\Pages;

use App\Filament\Resources\MarketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMarket extends CreateRecord
{
    use \App\Traits\RedirectIndex;

    protected static string $resource = MarketResource::class;
}
