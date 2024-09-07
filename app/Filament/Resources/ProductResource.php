<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
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
                        ->timezone('Asia/Karachi')
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
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('keyword')
                    ->badge()
                    ->color('info')
                    ->separator(',')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller')
                    ->searchable(),
                Tables\Columns\TextColumn::make('market.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_limit_per_day')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_limit_overall')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('commission')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_expensive')
                    ->boolean(),
                Tables\Columns\TextColumn::make('product_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amazone_short_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marketing_end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.id')
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
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->native(false)->closeOnDateSelection()->placeholder("From Date"),
                        DatePicker::make('created_until')->native(false)->closeOnDateSelection()->placeholder("To Date"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
}
