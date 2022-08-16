<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
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
    * Only level on users can delete inventory
    *
    * @return void
    */
    public function delete($user)
    {
        return $user->permission == 1 ? true : false ;
    }
}
