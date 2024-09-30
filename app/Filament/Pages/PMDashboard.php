<?php

namespace App\Filament\Pages;

use Auth;
use Filament\Pages\Page;

class PMDashboard extends Page
{
    protected static ?string $navigationIcon = 'bx-stats';

    protected static string $view = 'filament.pages.p-m-dashboard';

    protected static ?string $navigationLabel = "Dashboard";

    protected static ?string $title = 'Dashboard';

    protected static ?string $slug = 'pm-dashboard';


    // public static function canAccess(): bool
    // {
    //     return Auth::user()->isSuperAdmin();
    // }
}
