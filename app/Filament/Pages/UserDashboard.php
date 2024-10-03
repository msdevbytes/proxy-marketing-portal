<?php

namespace App\Filament\Pages;

use Auth;
use Filament\Pages\Page;

class UserDashboard extends Page
{
    protected static ?string $navigationIcon = 'bx-stats';

    protected static string $view = 'filament.pages.user-dashboard';

    protected static ?string $navigationLabel = "Dashboard";

    protected static ?string $title = 'Dashboard';

    protected static ?string $slug = '/dashboard';


    // public static function canAccess(): bool
    // {
    //     return Auth::user()->isSuperAdmin();
    // }
}
