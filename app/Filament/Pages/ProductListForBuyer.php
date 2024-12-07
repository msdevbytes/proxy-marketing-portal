<?php

namespace App\Filament\Pages;

use App\Tables\Columns\CustomImageColumn;
use DeepCopy\Filter\Filter;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\TextInput;
use Filament\Pages\BasePage;
use Filament\Panel\Concerns\HasPlugins;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter as FiltersFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductListForBuyer extends BasePage implements HasTable
{
    use InteractsWithTable;
    use HasPlugins;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = "";

    protected static string $view = 'filament.pages.product-list-for-buyer';

    public function hasLogo(): bool
    {
        return false;
    }



    public static function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Product::query()->where('status', 1))
            ->searchPlaceholder("Search Everything....")
            ->recordUrl(fn() => null)->recordAction(null)
            ->columns([
                TextColumn::make('sale_limit_per_day')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('remaning_orders')
                    ->view('tables.columns.product-remining-orders-count')
                    ->sortable(),
                TextColumn::make('market.market')->sortable()->searchable(),

                TextColumn::make('product_brand')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id')->sortable()->searchable(),
                CustomImageColumn::make('image'),
            ])
            ->filters([
                FiltersFilter::make('ID')
                    ->form([
                        TextInput::make('id')->label('Product ID')->placeholder("search"),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->where('id', 'like', $data['id'] . '%');
                    }),
                FiltersFilter::make('product_brand')
                    ->form([
                        TextInput::make('product_brand')->label('Product Brand')->placeholder("search"),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->where('product_brand', 'like', '%' . $data['product_brand'] . '%');
                    }),
                SelectFilter::make('category')->relationship('category', 'category')->searchable()->preload()->native(false),
                SelectFilter::make('market')->relationship('market', 'market')->searchable()->preload()->native(false),

            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->actions([])
            ->bulkActions([]);
    }
}
