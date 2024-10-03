<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Enums\Gender;
use Auth;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles, HasSuperAdmin, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cnic',
        'phone_number',
        'gender',
        'address',
        'image',
        'cnic_front_image',
        'cnic_back_image',
        'bank_name',
        'bank_account_holder_name',
        'bank_account_number',
        'bank_account_city_id',
        'city_id',
        'status',
        'acc_deactive_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'acc_deactive_at' => 'datetime',
            'password' => 'hashed',
            'gender' => Gender::class
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->status == 0) {
            FacadesAuth::logout();
        }
        return $this->status == 1;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }
    public function isPM(): bool
    {
        return $this->hasRole('PM');
    }

    public function isPMM(): bool
    {
        return $this->hasRole('PMM');
    }

    function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    function bankAccountCity(): BelongsTo
    {
        return $this->belongsTo(City::class,  "bank_account_city_id");
    }

    function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    function pmmOrders(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Order::class);
    }

    function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    function checkProductReservation(int $productID): bool
    {
        return $this->reservations()
            ->where([['product_id', $productID], ['status', 0]])
            ->whereRaw('reservation_expiry > STR_TO_DATE(?, "%Y-%m-%d %H:%i:%s")', Carbon::now()->format('Y-m-d H:m:s'))
            ->exists();
    }
}
