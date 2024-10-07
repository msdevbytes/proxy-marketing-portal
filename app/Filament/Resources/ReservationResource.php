<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Auth;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class ReservationResource extends Resource
{

    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';


    public static function canDeleteAny(): bool
    {
        return Auth::user()->checkPermissionTo('delete Reservation (web)');
    }

    public static function canForceDeleteAny(): bool
    {
        return Auth::user()->checkPermissionTo('force-delete Reservation (web)');
    }

    public static function canRestoreAny(): bool
    {
        return Auth::user()->checkPermissionTo('restore Reservation (web)');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reservation_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TagsInput::make('keywords')->separator(',')->color('info'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'id')
                    ->required(),
                Forms\Components\Select::make('market_id')
                    ->relationship('market', 'id'),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->isPMM()) {
                    $query->join('products', 'products.id', '=', 'reservations.product_id')
                        ->where('products.user_id', Auth::user()->id)->select('reservations.*')
                        ->whereRaw('reservation_expiry > STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'));
                } else if (Auth::user()->isPM()) {
                    $query->where("user_id", Auth::user()->id)->whereRaw('reservation_expiry > STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'));
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('reservation_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.id')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reservation_expiry')->view('tables.columns.reservation-timer')->alignCenter(),

                Tables\Columns\ImageColumn::make('product.image')->square()->simpleLightbox(),

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
                Action::make('reserve')
                    ->hidden(function (Reservation $reservation) {
                        return (Auth::user()->isSuperAdmin() || $reservation->product->isProductDisabled());
                    })
                    ->color('danger')
                    ->button()
                    ->label('Release')
                    ->icon('lucide-alarm-clock')
                    ->action(fn(Reservation $reservation) => ProductResource::releaseProdct($reservation->product)),
                Action::make('createOrder')
                    ->hidden(!Auth::user()->isPM())
                    ->button()
                    ->url(fn(Reservation $record): string => route('filament.admin.resources.orders.create', ['product_id' => $record->product_id]))

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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
