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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public function canCreateAny(): bool
    {
        return Auth::user()->isSuperAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Customer Detail')->schema([
                        Forms\Components\TextInput::make('customer_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_customer_scammer')
                            ->onColor('danger')
                            ->inline(false)
                            ->required(),

                    ])->columns([
                        'md' => 3,
                        'sm' => 1
                    ]),
                    Section::make('Order Detail')->schema([
                        Hidden::make('product_id')->default(request()->get('product_id'))->visible(request()->get('product_id') != null),
                        Forms\Components\Select::make("status")
                            ->preload()
                            ->label('Order Status')
                            ->options(function () {
                                $status = [];
                                foreach (Order::orderStatusByRole() as $case) {
                                    $status[$case] = $case;
                                }

                                return $status;
                            })->native(false),
                        Forms\Components\TextInput::make('amz_order_number')
                            ->label('Amazone Order Number')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('review_link')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('remarks')
                            ->columnSpanFull()->rows(3),
                    ])->columns([
                        'md' => 3,
                        'sm' => 1
                    ]),
                    Section::make('Images')->schema([
                        Forms\Components\FileUpload::make('invoice_image')
                            ->image(),
                        Forms\Components\FileUpload::make('review_image')
                            ->image(),
                        Forms\Components\FileUpload::make('refund_image')
                            ->image(),
                    ])->columns(3),
                ])->visible(Auth::user()->isPM()),
                Group::make()->schema([
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
                                    $status = [];
                                    foreach (Order::orderStatusByRole() as $case) {
                                        $status[$case] = $case;
                                    }

                                    return $status;
                                })->native(false),

                            Forms\Components\Textarea::make('remarks')
                                ->columnSpanFull(),

                        ]),
                        Section::make('Images')->schema([
                            Forms\Components\FileUpload::make('buyer_verification_image')
                                ->image(),

                        ]),
                    ])->from('md'),
                ])->visible(Auth::user()->isPMM()),


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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('amz_order_number'),
                Infolists\Components\TextEntry::make('customer_email'),
                Infolists\Components\TextEntry::make('customer_phone_number'),
                Infolists\Components\TextEntry::make('is_customer_scammer')->badge(),
                Infolists\Components\TextEntry::make('review_type_commission'),
                Infolists\Components\TextEntry::make('status'),
                Infolists\Components\TextEntry::make('review_link'),
                Infolists\Components\TextEntry::make('market_id'),
                Infolists\Components\TextEntry::make('user.email'),
                Infolists\Components\TextEntry::make('product_id'),
                Infolists\Components\TextEntry::make('remarks')->columnSpanFull(),

                ComponentsSection::make('Images')->schema([
                    Infolists\Components\ImageEntry::make('invoice_image'),
                    Infolists\Components\ImageEntry::make('review_image'),
                    Infolists\Components\ImageEntry::make('buyer_verification_image'),
                    Infolists\Components\ImageEntry::make('refund_image'),
                ])->columns(4),
            ]);
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
