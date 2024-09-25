<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;

class ProductResource extends Resource
{

    protected static ?string $label = "All Products";

    protected static ?string $navigationGroup = "Products";

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';


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
                        Forms\Components\TagsInput::make('keyword')->separator(Product::keywordSeparator())->color('info'),
                        Forms\Components\TextInput::make('amz_sold_by')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('asin')
                            ->label("ASIN")
                            ->maxLength(255),
                        Forms\Components\TextInput::make('seller')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('product_price')
                            ->required()
                            ->numeric(),
                    ])->columns(['md' => 4, 'sm' => 1]),
                Section::make("Product Limits")->schema([
                    Forms\Components\DatePicker::make('marketing_end_date')
                        ->displayFormat("M d, Y")
                        ->required()
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

                    Forms\Components\TextInput::make('commission')
                        ->required()
                        ->numeric(),
                ])->columns(['md' => 4, 'sm' => 1]),
                Section::make("Product Links")->schema([
                    Forms\Components\TextInput::make('amazone_short_link')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('product_link')
                        ->url()
                        ->maxLength(255),
                ])->columns(['md' => 2, 'sm' => 1]),
                Section::make('Select Category & Market')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->native(false)
                            ->relationship('category', titleAttribute: 'category'),
                        Forms\Components\Select::make('market_id')
                            ->native(false)
                            ->relationship('market', titleAttribute: 'market'),
                    ])->columns(['md' => 2, 'sm' => 1]),

                Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image(),
                        Forms\Components\FileUpload::make('amazone_image')
                            ->image(),
                    ])->label("Images")->columns(2),
                Section::make("Instructions & Condtions")->schema([
                    Forms\Components\Textarea::make('review_instructions')
                        ->columnSpanFull()
                        ->rows(5),
                    Forms\Components\Textarea::make('refund_conditions')
                        ->columnSpanFull()
                        ->rows(5),
                    Forms\Components\Textarea::make('comission_conditions')
                        ->columnSpanFull()
                        ->rows(5),
                    Forms\Components\Textarea::make('instructions')
                        ->columnSpanFull()
                        ->rows(5),
                ])
            ])->columns(['md' => 4, 'sm' => 1]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->isPMM()) {
                    $query->where('user_id', Auth::user()?->id);
                } else if (!Auth::user()->isSuperAdmin()) {
                    $query->where('status', 1)->whereDate('marketing_end_date', '>=', Carbon::now());
                }
            })
            ->columns([


                Tables\Columns\TextColumn::make('user.name')
                    ->label('Seller Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('market.market')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_limit_per_day')
                    ->numeric()
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('sale_limit_overall')
                    ->numeric()
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('remaning_orders')
                    ->view('tables.columns.product-remining-orders-count')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commission')
                    ->money('PKR', locale: 'Rs')
                    ->sortable()->searchable(),



                Tables\Columns\TextColumn::make('keyword')
                    ->color('primary')
                    ->separator(',')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id')->label('Product ID'),
                Tables\Columns\ImageColumn::make('image'),
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
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')->boolean(),
            ])
            ->filters([
                SelectFilter::make('users')->label('PMMs')->relationship('user', 'name', function (User $user) {
                    return $user->role('PMM');
                })->native(false)->searchable()->preload()->hidden(Auth::user()->isPMM()),
                DateRangeFilter::make('created_at')
                    ->label('Date Range')
                    ->autoApply(false)
                    ->timezone(env('APP_TIMEZONE'))
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->actions([
                Action::make('reserve')
                    ->hidden(function (Product $product) {
                        return (Auth::user()->checkProductReservation($product->id) || Auth::user()->isSuperAdmin() || $product->isProductDisabledOrMarketingEnd());
                    })
                    ->color('info')
                    ->button()
                    ->label('Reserve')
                    ->icon('lucide-alarm-clock')
                    ->action(fn(Product $product) => self::reserveProduct($product)),

                Action::make('danger')
                    ->hidden(function (Product $product) {
                        return (!Auth::user()->checkProductReservation($product->id) || Auth::user()->isSuperAdmin()  || $product->isProductDisabledOrMarketingEnd());
                    })
                    ->color('danger')
                    ->button()
                    ->label('Release')
                    ->icon('lucide-alarm-clock')
                    ->action(fn(Product $product) => self::releaseProdct($product)),
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->color('primary'),
                Tables\Actions\EditAction::make(),
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
        if (!$product->status && $product->isProductDisabledOrMarketingEnd()) {
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
