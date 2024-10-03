<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Livewire\ProductStats;
use App\Livewire\ProductStatsOverview;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\UserDashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->registration(Register::class)
            ->profile(isSimple: false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->spa()
            ->sidebarCollapsibleOnDesktop()
            // ->unsavedChangesAlerts()
            ->maxContentWidth(MaxWidth::Full)
            ->plugins([
                FilamentSpatieRolesPermissionsPlugin::make(),
                \Hasnayeen\Themes\ThemesPlugin::make()
            ])
            ->navigationItems([
                NavigationItem::make('Create Product')
                    ->group('Products')
                    ->icon('lucide-plus')
                    ->url('/admin/products/create')
                    ->sort(1)
                    ->hidden(fn() => Auth::user()->isPM())
                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.products.create')),
                NavigationItem::make('All Products')
                    ->group('Products')
                    ->icon('lucide-layers')
                    ->url('/admin/products')
                    ->badge(fn() => (Auth::user()->isPMM() ? Auth::user()->products()->where('products.status', 1)->whereDate('marketing_end_date', '>=', Carbon::now())->count() : null))
                    ->sort(0)
                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.products.index'))
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                UserDashboard::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                ProductStatsOverview::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
