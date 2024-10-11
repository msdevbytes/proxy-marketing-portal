<?php

namespace App\Filament\Pages;

use Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'carbon-dashboard-reference';

    protected static ?string $navigationLabel = "Admin Dashboard";

    protected static ?string $title = 'Admin Dashboard';

    protected static ?string $slug = 'admin-dashboard';

    protected static string $routePath = 'admin-dashboard';

    public static function canAccess(): bool
    {
        return Auth::user()->isSuperAdmin();
    }
}
