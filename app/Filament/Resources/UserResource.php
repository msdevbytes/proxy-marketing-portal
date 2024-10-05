<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Auth;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentPasswordReveal\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User Management';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('User')->schema([

                    Forms\Components\Select::make('roles')
                        ->visible(Auth::user()?->isSuperAdmin())
                        ->label('Role')
                        ->placeholder("Select a role")
                        ->selectablePlaceholder(true)
                        ->preload()
                        ->markAsRequired(Auth::user()?->isSuperAdmin())
                        ->relationship('roles', 'name', fn(Builder $query) =>  $query->where('name', '!=', 'Super Admin'))
                        ->native(false),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone_number')
                        ->regex("/^\+?[0-9]{1,3}?[-.\s]?(\(?\d{1,4}?\)?[-.\s]?)[\d\-.\s]{5,17}$/")->validationMessages(["Please enter a valid phone numbers"])
                        ->maxLength(191),
                    Forms\Components\TextInput::make('cnic')
                        ->label("CNIC")
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make("gender")->preload()->options(function () {
                        $gender = [];
                        foreach (Gender::cases() as $case) {
                            $gender[$case->value] = $case->value;
                        }
                        return $gender;
                    })->columns(1)->native(false),
                    Forms\Components\TextInput::make('address')
                        ->maxLength(255),

                    Forms\Components\Select::make('city_id')
                        ->relationship('city', titleAttribute: 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),
                    Forms\Components\Toggle::make('status')
                        ->required()->inline(false)->offColor("danger")->onColor("success")->default(true),
                    Forms\Components\DatePicker::make('email_verified_at')
                        ->displayFormat("M d, Y")
                        ->timezone(env('APP_TIMEZONE'))
                        ->maxDate(now())
                        ->closeOnDateSelection()
                        ->native(false)->placeholder("Select Date"),
                    Forms\Components\DatePicker::make('acc_deactive_at')
                        ->displayFormat("M d, Y")
                        ->timezone(env('APP_TIMEZONE'))
                        ->minDate(now()->subDay())
                        ->closeOnDateSelection()
                        ->native(false)->placeholder("Select Date"),
                ])->columns([
                    'md' => 3,
                    'sm' => 1
                ])->description("Put user detail here"),
                Section::make('Bank A/C Detail')->schema([
                    Forms\Components\TextInput::make('bank_name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_holder_name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_number')
                        ->maxLength(255),
                    Forms\Components\Select::make('bank_account_city_id')
                        ->label('Bank A/C City')
                        ->relationship('bankAccountCity', titleAttribute: 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),
                ])->columns([
                    'md' => 3,
                    'sm' => 1
                ]),
                Section::make('Docs')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Profile Picture')
                        ->image(),
                    Forms\Components\FileUpload::make('cnic_front_image')
                        ->image(),
                    Forms\Components\FileUpload::make('cnic_back_image')
                        ->image(),
                ])->columns([
                    'md' => 3,
                    'sm' => 1
                ]),
                Section::make('Credencials')->schema([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Password::make('password')
                        ->default('Secret@123')
                        ->autocomplete('new-password')
                        ->password(true)
                        ->revealable(true)
                        ->copyable(true)
                        ->generatable(true)
                        ->copyText("Password Copied")
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context): bool => $context === 'create')
                        ->maxLength(191)
                ])->columns([
                    'md' => 2,
                    'sm' => 1
                ])->description("Define user credentials"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->withOutRole('Super Admin');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->tooltip('Copy Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->copyable()
                    ->tooltip('Copy Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnic')
                    ->copyable()
                    ->tooltip('Copy CNIC')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')->circular(),
                Tables\Columns\ImageColumn::make('cnic_front_image')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('cnic_back_image')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bank_account_holder_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_account_number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_account_city_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                ToggleColumn::make('status')->hidden(Auth::user()->isPM())->offColor("danger")->onColor("success"),
                Tables\Columns\TextColumn::make('acc_deactive_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
