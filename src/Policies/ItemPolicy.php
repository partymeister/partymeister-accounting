<?php

namespace Partymeister\Accounting\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Admin\Models\User;
use Partymeister\Accounting\Models\Item;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('items.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Item  $item
     * @return mixed
     */
    public function view(User $user, Item $item)
    {
        return $user->hasPermissionTo('items.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('items.write');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Item  $item
     * @return mixed
     */
    public function update(User $user, Item $item)
    {
        return $user->hasPermissionTo('items.write');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Item  $item
     * @return mixed
     */
    public function delete(User $user, Item $item)
    {
        return $user->hasPermissionTo('items.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Item  $item
     * @return mixed
     */
    public function restore(User $user, Item $item)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Motor\Admin\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Item  $item
     * @return mixed
     */
    public function forceDelete(User $user, Item $item)
    {
        //
    }
}
