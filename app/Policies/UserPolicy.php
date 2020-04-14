<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given user profile.
     *
     * @param  \App\User  $user
     * @param  \App\Use  $signedInUser
     * @return mixed
     */
    public function update(User $user, User $signedInUser)
    {
        return $signedInUser->id == $user->id;
    }
}
