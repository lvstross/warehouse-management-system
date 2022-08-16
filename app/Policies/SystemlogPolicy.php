<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemlogPolicy
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
    * Only level one users can delete a system log item
    *
    * @return void
    */
    public function delete($user)
    {
        return $user->permission == 1 ? true : false ;
    }
}
