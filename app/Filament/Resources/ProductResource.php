<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        Forms\Components\TextInput::make('keyword')
                            ->maxLength(255),
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
                Tables\Columns\ImageColumn::make('amazone_image'),
                Tables\Columns\TextColumn::make('product_brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keyword')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amz_sold_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seller')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('market.id')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
