<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Load the invoices page for Super Admin and Regular Admin
     *
     * @return view
     */
    public function invoices()
    {
        if( User::isSuperAdmin() || User::isAdmin() ) {
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the users page only for the Super Admin
     *
     * @return view
     */
    public function users()
    {
        if( User::isSuperAdmin() ) {
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the customers page only for the Super admin and the regular admin.
     *
     * @return view
     */
    public function customers()
    {
        if( User::isSuperAdmin() || User::isAdmin() ) {
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
    * Load the products page to level 1-3 users
    *
    * @return view
    */
    public function products()
    {
        if( User::isSuperAdmin() || User::isAdmin() || User::isManager() ) {
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
    * Load the certifications page to level 1-3 users
    *
    * @return view
    */
    public function certs()
    {
        if( User::isSuperAdmin() || User::isAdmin() || User::isManager() ) {
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the routers page to level 1-3 users
     *
     * @return void
     */
    public function routers()
    {
        if( User::isSuperAdmin() || User::isAdmin() || User::isManager() || User::isEmployee() ){
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the purchaseorders page to level 1-3 users
     *
     * @return void
     */
    public function purchaseorders()
    {
        if( User::isSuperAdmin() || User::isAdmin() || User::isManager() ){
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the systemlog page to level 1-3 users
     *
     * @return void
     */
    public function systemlog()
    {
        if( User::isSuperAdmin() ){
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the vendors page to level 1-3 users
     *
     * @return void
     */
    public function vendors()
    {
        if( User::isSuperAdmin() || User::isAdmin() || User::isManager() ){
            return view('page');
        }else{
            return abort('401');
        }
    }

    /**
     * Load the purchases page to level 1-2 users
     *
     * @return void
     */
    public function purchases()
    {
        if( User::isSuperAdmin() || User::isAdmin() ){
            return view('page');
        }else{
            return abort('401');
        }
    }
}
