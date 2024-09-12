<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Market;
use App\Models\User;

class MarketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Market');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Market $market): bool
    {
        return $user->checkPermissionTo('view Market');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Market');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Market $market): bool
    {
        return $user->checkPermissionTo('update Market');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Market $market): bool
    {
        return $user->checkPermissionTo('delete Market');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Market $market): bool
    {
        return $user->checkPermissionTo('restore Market');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Market $market): bool
    {
        return $user->checkPermissionTo('force-delete Market');
    }
}
