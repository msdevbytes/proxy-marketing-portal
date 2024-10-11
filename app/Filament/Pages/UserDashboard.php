<?php

namespace App\Filament\Pages;

use Auth;
use Filament\Pages\Page;

class UserDashboard extends Page
{
    protected static ?string $navigationIcon = 'carbon-dashboard';

    protected static string $view = 'filament.pages.user-dashboard';

    protected static ?string $navigationLabel = "User Dashboard";

    protected static ?string $title = 'User Dashboard';

    protected static ?string $slug = '';

    // public static function canAccess(): bool
    // {
    //     return Auth::user()->isSuperAdmin();
    // }
}
