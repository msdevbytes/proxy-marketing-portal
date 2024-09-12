<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\User;
use Auth;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public function deleteAny(): bool
    {
        return Auth::user()->checkPermissionTo('delete Order');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Detail')->schema([
                    Forms\Components\TextInput::make('customer_email')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('customer_phone_number')
                        ->regex("/^\+?[0-9]{1,3}?[-.\s]?(\(?\d{1,4}?\)?[-.\s]?)[\d\-.\s]{5,17}$/")->validationMessages(["Please enter a valid phone numbers"])
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_customer_scammer')
                        ->onColor('danger')
                        ->required(),
                ])->columns([
                    'md' => 3,
                    'sm' => 1
                ]),
                Split::make([
                    Section::make('Info')->schema([
                        Forms\Components\TextInput::make('amz_order_number')
                            ->label('Amazone Order Number')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('review_type_commission')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        Forms\Components\Select::make("status")
                            ->preload()
                            ->label('Order Status')
                            ->options(function () {
                                $gender = [];
                                foreach (OrderStatus::cases() as $case) {
                                    $gender[$case->value] = $case->value;
                                }
                                return $gender;
                            })->native(false),

                        Forms\Components\TextInput::make('review_link')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('remarks')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name', fn(User $user) => $user->withoutRole('Super Admin'))
                            ->preload()
                            ->native(false)
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('market_id')
                            ->preload()
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->relationship('market', titleAttribute: 'market'),
                        Forms\Components\Select::make('product_id')
                            ->preload()
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->relationship('product', titleAttribute: 'name'),
                    ]),
                    Section::make('Images')->schema([
                        Forms\Components\FileUpload::make('invoice_image')
                            ->image(),
                        Forms\Components\FileUpload::make('review_image')
                            ->image(),
                        Forms\Components\FileUpload::make('refund_image')
                            ->image(),
                        Forms\Components\FileUpload::make('buyer_verification_image')
                            ->image(),
                    ]),
                ])->from('md'),


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('invoice_image'),
                Tables\Columns\TextColumn::make('amz_order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_phone_number')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_customer_scammer')
                    ->boolean(),
                Tables\Columns\TextColumn::make('review_type_commission')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\ImageColumn::make('review_image'),
                Tables\Columns\ImageColumn::make('refund_image'),
                Tables\Columns\ImageColumn::make('buyer_verification_image'),
                Tables\Columns\TextColumn::make('review_link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('market.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
