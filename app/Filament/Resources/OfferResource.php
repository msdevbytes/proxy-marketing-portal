<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfferResource\Pages;
use App\Filament\Resources\OfferResource\RelationManagers;
use App\Models\Offer;
use Auth;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Offer Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('price')
                        ->label("Offer Price")
                        ->numeric()
                        ->prefix('$'),
                    Forms\Components\Select::make('for_user_role')
                        ->label('Select Role')
                        ->hint("Which Users Role Can See This Offer?")->hintColor("primary")
                        ->placeholder("Select roles")
                        ->selectablePlaceholder(true)
                        ->preload()
                        ->required()
                        ->relationship('forUserRole', 'name', function (Builder $query) {
                            return $query->whereNotIn('name', ['Super Admin']);
                        })
                        ->native(false),
                    Forms\Components\RichEditor::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])->columns([
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 3,
                ]),
                Section::make('Offer Image')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->image(),
                ])->columns([
                    'sm' => 1,
                    'md' => 1,
                ]),
                Section::make('Offer Dates')->schema([
                    Forms\Components\Toggle::make('status')->onColor("success")->default(true)->inline(false),
                    Forms\Components\DatePicker::make('start_date')
                        ->displayFormat("M d, Y")
                        ->timezone(env('APP_TIMEZONE'))
                        ->reactive()
                        ->native(false)
                        ->minDate(Carbon::now()->format('M d, Y'))
                        ->afterStateUpdated(fn(Set $set) => $set('end_date', null)),
                    Forms\Components\DatePicker::make('end_date')
                        ->displayFormat("M d, Y")
                        ->timezone(env('APP_TIMEZONE'))
                        ->native(false)
                        ->live()
                        ->minDate(fn(Get $get) => Carbon::parse($get('start_date'))?->adddays(1)),
                ])->columns([
                    'sm' => 1,
                    'md' => 3,
                ]),
            ])->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('forUserRole.name')->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}
