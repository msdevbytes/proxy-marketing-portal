<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;
use Spatie\FilamentSimpleStats\SimpleStat;

class ProductStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;

    function activeDeActiveProducts(): string
    {
        return sprintf("%s / %s", Number::format(Product::where('status', 1)->count()), Number::format(Product::where('status', 0)->count()));
    }

    function todaysProductsSummary(): string
    {
        return sprintf(
            "%s / %s",
            Number::format(Product::where('status', 1)->whereDate('created_at', Carbon::now())->count()),
            Number::format(Product::where('status', 0)->whereDate('created_at', Carbon::now())->count())
        );
    }

    protected function getStats(): array
    {

        return [
            SimpleStat::make(Product::class)->last30Days()->dailyCount(),
            Stat::make('Product Summary Overall', $this->activeDeActiveProducts())
                ->description('Active / Disabled')
                ->chart(Product::whereDate('created_at', \Carbon\Carbon::today())->get()->map(function ($group) {
                    return $group->count();
                })->values()->toArray())
                ->color('success'),
            Stat::make('Product Summary Today', $this->todaysProductsSummary())
                ->description('Active / Disabled')
                ->chart(Product::whereDate('created_at', Carbon::now())->get()->groupBy('status')->map(function ($group) {
                    return $group->count();
                })->values()->toArray())
                ->color('success'),
        ];
    }
}
