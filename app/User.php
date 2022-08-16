<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'permission'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Check against authentication for for user permission level 
     *
     * @return boolean
     */
    public static function isSuperAdmin() 
    {
        return Auth::user()->permission == 1;
    }

    /**
     * Check against authentication for for user permission level 
     *
     * @return boolean
     */
    public static function isAdmin()
    {
        return Auth::user()->permission == 2;
    }

    /**
     * Check against authentication for for user permission level 
     *
     * @return boolean
     */
    public static function isManager()
    {
        return Auth::user()->permission == 3;
    }

    /**
     * Check against authentication for for user permission level 
     *
     * @return boolean
     */
    public static function isEmployee()
    {
        return Auth::user()->permission == 4;
    }

    /**
    * This function checks to see if there is at least one users in the database.
    * The first user of the application should be the Super Admin User. All other
    * users can be added in the backend by the Super Admin User. It checks if the count of users
    * in the database is higher than one. If so, then it will redirect all http requests
    * from the register route to the login route. This is to restrict other users from signing up
    * for the app without permission from the super admin user.
    *
    * @return integer
    */
    public static function FirstSuperAdminExists()
    {
        return User::where('id', 1)->count();
    }

    /**
     * Returning the number of users in the database. 
     *
     * @return integer
     */
    public static function numberOfUses()
    {
        return User::all()->count();
    }
}
