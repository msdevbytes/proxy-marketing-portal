<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Closure;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Str;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;

class ProductResource extends Resource
{

    protected static ?string $label = "All Products";

    protected static ?string $navigationGroup = "Products";

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function canDeleteAny(): bool
    {
        return Auth::user()->checkPermissionTo('delete Product (web)');
    }

    public static function canForceDeleteAny(): bool
    {
        return Auth::user()->checkPermissionTo('force-delete Product (web)');
    }


    public static function canRestoreAny(): bool
    {
        return Auth::user()->checkPermissionTo('restore Product (web)');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make("General Info")

                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->default(true)
                            ->onColor('success')
                            ->offColor("danger")
                            ->inline(false),
                        Forms\Components\Toggle::make('is_expensive')
                            ->onColor('primary')
                            ->inline(false),
                        Forms\Components\TextInput::make('product_brand')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255),
                        Forms\Components\TagsInput::make('keyword')->required()->separator(Product::keywordSeparator())->color('info'),
                        Forms\Components\TextInput::make('amz_sold_by')->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('asin')
                            ->label("ASIN")
                            ->maxLength(255),
                        Forms\Components\TextInput::make('seller')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('product_price')

                            ->numeric(),
                    ])->columns(['md' => 4, 'sm' => 1]),
                Section::make("Product Limits")->schema([
                    Forms\Components\DatePicker::make('marketing_end_date')
                        ->displayFormat("M d, Y")
                        ->timezone(env('APP_TIMEZONE'))
                        ->minDate(now())
                        ->closeOnDateSelection()
                        ->native(false)->placeholder("Select Date"),
                    Forms\Components\TextInput::make('sale_limit_per_day')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('sale_limit_overall')
                        ->required()
                        ->numeric(),
                ])->columns(['md' => 4, 'sm' => 1]),
                Section::make("Product Links")->schema([
                    Forms\Components\TextInput::make('amazone_short_link')
                        ->label('Amazon Short Link')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('product_link')
                        ->url()
                        ->maxLength(255),
                ])->columns(['md' => 2, 'sm' => 1]),
                Section::make('Select Category & Market')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->relationship('category', titleAttribute: 'category'),
                        Forms\Components\Select::make('market_id')
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->preload()
                            ->live()
                            ->relationship('market', titleAttribute: 'market'),
                        Forms\Components\TextInput::make('commission')
                            ->numeric()
                            ->live()
                            ->rules([
                                fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    if ($get('market_id')) {
                                        $market = \App\Models\Market::where('id', $get('market_id'))->first();
                                        if ($value < $market->commission) {
                                            $fail("The " . Str::replace('data.', '', $attribute) . " can not be lessthen {$market->commission}.");
                                        }
                                    }
                                },
                            ])
                            ->required(),
                    ])->columns(['md' => 3, 'sm' => 1]),

                Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image(),
                        Forms\Components\FileUpload::make('amazone_image')
                            ->label('Amazon Image')
                            ->image(),
                    ])->label("Images")->columns(2),
                Section::make("Instructions & Condtions")->schema([
                    Forms\Components\Textarea::make('review_instructions')
                        ->readOnly()
                        ->default('5 star possetive review need 2-3 lines')
                        ->rows(5),
                    Forms\Components\Textarea::make('refund_conditions')
                        ->readOnly()
                        ->default('Refund after review : Product+pp fee is covered')
                        ->rows(5),
                    Forms\Components\Textarea::make('comission_conditions')
                        ->readOnly()
                        ->default('Refund after review : Product+pp fee is covered')
                        ->rows(5),
                    Forms\Components\Textarea::make('instructions')
                        ->readOnly()
                        ->default('1: 5 Star positive review needed of 2-3 lines 2: Review after 4-5 days of receiving product 3: Refund takes 3-4 working days once review goes live. Weekends are excluded. Product+PPFee covered')
                        ->rows(5),
                ])->columns([
                    'md' => 2,
                    'sm' => 1
                ]),
            ])->columns(['md' => 4, 'sm' => 1]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder("Search Everything....")
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->isPMM()) {
                    $query->where('user_id', Auth::user()?->id);
                } else if (!Auth::user()->isSuperAdmin()) {
                    $query->where('status', 1);
                }
            })->recordUrl(fn() => null)->recordAction(null)
            ->columns([
                ViewColumn::make('user')->view('tables.columns.user-info')->label('Seller'),
                Tables\Columns\TextColumn::make('market.market')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_limit_per_day')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_limit_overall')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaning_orders')
                    ->view('tables.columns.product-remining-orders-count')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commission')
                    ->money('PKR', locale: 'Rs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keyword')
                    ->color('primary')
                    ->separator(',')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id')->label('Product ID')->searchable(),
                Tables\Columns\ImageColumn::make('image')->square()->simpleLightbox(),
                Tables\Columns\IconColumn::make('is_expensive')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\TextColumn::make('product_price')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('PKR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amazone_short_link')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('marketing_end_date')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('status')->hidden(Auth::user()->isPM())->offColor("danger")->onColor("success")
            ])
            ->filters([
                SelectFilter::make('market')->relationship('market', 'market')->searchable()->preload()->native(false),
                SelectFilter::make('category')->relationship('category', 'category')->searchable()->preload()->native(false),
                // SelectFilter::make('users')->label('PMMs')->relationship('user', 'name', function (User $user) {
                //     return $user->role('PMM');
                // })->native(false)->searchable()->preload()->hidden(Auth::user()->isPMM()),
                // DateRangeFilter::make('created_at')
                //     ->label('Date Range')
                //     ->autoApply(false)
                //     ->timezone(env('APP_TIMEZONE'))
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->actions([
                Action::make('reserve')
                    ->hidden(function (Product $product) {
                        return (Auth::user()->checkProductReservation($product->id) || Auth::user()->isSuperAdmin() || $product->isProductDisabled());
                    })
                    ->color('info')
                    ->button()
                    ->label('Reserve')
                    ->icon('lucide-alarm-clock')
                    ->action(fn(Product $product) => self::reserveProduct($product)),

                Action::make('danger')
                    ->hidden(function (Product $product) {
                        return (!Auth::user()->checkProductReservation($product->id)  || $product->isProductDisabled());
                    })
                    ->color('info')
                    ->disabled()
                    ->button()
                    ->label('Reserved')
                    ->icon('lucide-alarm-clock'),
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->color('primary'),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function releaseProdct(Product $product)
    {

        if (Auth::user()->isSuperAdmin()) {
            Reservation::where('product_id', $product->id)
                ->whereRaw('reservation_expiry > STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'))->delete();
        } else {
            Auth::user()->reservations()->where('product_id', $product->id)->delete();
        }
        Notification::make()
            ->title('Hey ' . Auth::user()?->name)
            ->body("Product Released")
            ->icon('lucide-alarm-clock')
            ->color("success")
            ->send();
    }

    public static function reserveProduct(Product $product)
    {
        $msg = "Product Reserved Successfully!";
        $color = "danger";
        $product = Product::find($product->id);
        if (!$product->status && $product->isProductDisabled()) {
            $msg = 'Product may not enabled or the marketing date is end';
        }

        $saleLImit = Order::where([['product_id', $product->id], ['status', '!=', OrderStatus::CANCELLED->value]])->count();

        if ($product->sale_limit_overall == $saleLImit) {
            $msg = 'Sale limit reached';
        }

        $dailySaleLImit = Order::where([['product_id', $product->id], ['status', '!=', OrderStatus::CANCELLED->value]])->groupBy('created_at')->count();
        if ($dailySaleLImit == $product->sale_limit_per_day) {
            $msg = 'Daily sale simit reached';
        }

        $reserved = Reservation::where([['product_id', $product->id], ['status', 0]])
            ->whereRaw('reservation_expiry > STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'))
            ->orderBy('created_at', 'DESC')->get();

        if ($product->sale_limit_per_day > $reserved?->count()) {
            $reserve = new Reservation;

            $reserve->user_id = Auth::user()?->id;
            $reserve->product_id = $product->id;
            $reserve->reservation_number = sprintf("%02d-%s", $product->id, time());
            $reserve->keywords = $product->keyword;
            $reserve->market_id = $product->market_id;
            $reserve->status = 0;
            $reserve->reservation_expiry = Carbon::now()->addHours(2);

            $reserve->save();
            $color = "success";
        } else {
            $now = Carbon::now();
            $msg = 'This product is already reserved and will be available in <br/> <b>' . Carbon::createFromTimestamp($now->diffInMilliseconds($reserved[0]?->reservation_expiry) / 1000)->format('h:m:s') . ' </b>';
        }


        Notification::make()
            ->title('Hey ' . Auth::user()?->name)
            ->body($msg)
            ->icon('lucide-alarm-clock')
            ->color($color)
            ->send();
    }
}
