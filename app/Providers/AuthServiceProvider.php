<?php

namespace App\Providers;

use App\Invoice;
use App\Product;
use App\Department;
use App\Inventory;
use App\Router;
use App\Systemlog;
use App\Policies\InvoicePolicy;
use App\Policies\ProductPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\InventoryPolicy;
use App\Policies\RouterPolicy;
use App\Policies\SystemlogPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Product::class => ProductPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Customer::class => CustomerPolicy::class,
        User::class => UserPolicy::class,
        Department::class => DepartmentPolicy::class,
        Inventory::class => InventoryPolicy::class,
        Router::class => RouterPolicy::class,
        Systemlog::class => SystemlogPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(); // Added for passport api support

        // Api Gates
        Gate::define('delete', 'App\Policies\ProductPolicy@delete');
        Gate::define('delete', 'App\Policies\InvoicePolicy@delete');
        Gate::define('delete', 'App\Policies\CustomerPolicy@delete');
        Gate::define('delete', 'App\Policies\UserPolicy@delete');
        Gate::define('delete', 'App\Policies\DepartmentPolicy@delete');
        Gate::define('delete', 'App\Policies\InventoryPolicy@delete');
        Gate::define('delete', 'App\Policies\RouterPolicy@delete');
        Gate::define('delete', 'App\Policies\SystemlogPolicy@delete');
    }
}
