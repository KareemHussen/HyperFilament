<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_vehicle');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehicle $driver): bool
    {
        return $user->can('view_vehicle');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_vehicle');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehicle $driver): bool
    {
        return $user->can('update_vehicle');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $driver): bool
    {
        return $user->can('delete_vehicle');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehicle $driver): bool
    {
        return $user->can('restore_vehicle');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehicle $driver): bool
    {
        return $user->can('forceDelete_vehicle');
    }
}
