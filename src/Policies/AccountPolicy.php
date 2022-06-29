<?php

namespace Partymeister\Accounting\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Motor\Backend\Models\User;
use Partymeister\Accounting\Models\Account;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \Motor\Backend\Models\User  $user
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
     * @param  \Motor\Backend\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('accounts.read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Account  $account
     * @return mixed
     */
    public function view(User $user, Account $account)
    {
        return $user->hasPermissionTo('accounts.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('accounts.write');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Account  $account
     * @return mixed
     */
    public function update(User $user, Account $account)
    {
        return $user->hasPermissionTo('accounts.write');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Account  $account
     * @return mixed
     */
    public function delete(User $user, Account $account)
    {
        return $user->hasPermissionTo('accounts.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Account  $account
     * @return mixed
     */
    public function restore(User $user, Account $account)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Motor\Backend\Models\User  $user
     * @param  \Partymeister\Accounting\Models\Account  $account
     * @return mixed
     */
    public function forceDelete(User $user, Account $account)
    {
        //
    }
}
