<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'for_user_role',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'status' => "boolean",
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    function forUserRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'for_user_role');
    }
}
