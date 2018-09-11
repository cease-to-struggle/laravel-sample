<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 
     * 
     * @param  User   $currentUser [description]
     * @param  User   $user        [description]
     * @return [type]              [description]
     */
    public function update(User $currentUser,User $user){

        //dd($currentUser->id,$user->id);

        return $currentUser->id === $user->id;

    }
}
