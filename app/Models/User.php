<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
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
        'phone_number',
        'cnic',
        'gender',
        'address',
        'password',
        'image',
        'cnic_image',
        'bank_account_name',
        'bank_account_number',
        'bank_account_city_id',
        'city_id',
        'status',
        'acc_deactive_at'
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
    function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
