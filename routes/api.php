<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function(){
    // ==================== Products Api ==================== //
    Route::get('products', 'ProductsController@getProducts'); // Get All Products
    Route::get('products/{id}', 'ProductsController@getOne'); // Get One Product For Editing
    Route::post('products/store', 'ProductsController@addProduct'); // Add A Single Product
    Route::patch('products/{id}', 'ProductsController@updateProduct'); // Update A Single Product
    Route::delete('products/{id}', 'ProductsController@deleteProduct'); // Delete A Single Product
    // ==================== End of Products Api ==================== //

    // ==================== Users Api ======================== //
    Route::get('users', 'UsersController@getUsers'); // Get All Users
    Route::get('users/{id}', 'UsersController@getUser'); // Get One User for Editing
    Route::post('users/store', 'UsersController@addUser'); // Add A Single User
    Route::patch('users/{id}', 'UsersController@updateUser'); // Update A Single User
    Route::delete('users/{id}', 'UsersController@deleteUser'); // Delete A Single User
    // ==================== End of Users Api ======================== //

    //====================== Customers Api ====================== //
    Route::get('customers', 'CustomersController@getCustomers'); // Get all Customers
    Route::get('customers/{id}', 'CustomersController@getCustomer'); // Get One Customer for Editing
    Route::post('customers/store', 'CustomersController@addCustomer'); // Add A Single Custer
    Route::patch('customers/{id}', 'CustomersController@updateCustomer'); // Update a Single Customer
    Route::delete('customers/{id}', 'CustomersController@deleteCustomer'); // Delete a Single Customer
    //====================== End of Customers Api ================ //

    //====================== Invoices Api ====================== //
    Route::get('invoices', 'InvoicesController@getInvoices'); // Get all Invoices in the last 12 months
    Route::get('invoices/all', 'InvoicesController@getAllInvoices'); // Get all Invoices
    Route::get('invoices/{id}', 'InvoicesController@getOne'); // Get One Invoice for Editing
    Route::get('invoices/report/{start}/{end}', 'InvoicesController@betweenDates'); // Get the invoices between two given dates
    Route::get('invoices/search/{term}', 'InvoicesController@byInvoiceNum'); // searches for an invoice by inv_num
    Route::post('invoices/store', 'InvoicesController@addInvoice'); // Add A Single Invoice
    Route::patch('invoices/{id}', 'InvoicesController@updateInvoice'); // Update a Single Invoice
    Route::delete('invoices/{id}', 'InvoicesController@deleteInvoice'); // Delete a Single Invoice
    //====================== End of Invoices Api ====================== //

    //====================== Company Api ====================== //
    Route::get('company', 'CompanyController@getName'); //Get company info for viewing
    Route::get('company/{id}', 'CompanyController@getOne'); // Get company for editing
    Route::get('invoice/prefix', 'CompanyController@getInvPrefix'); // Get the invoice prefix number
    Route::post('company/store', 'CompanyController@addCompany'); // Add the company info
    Route::patch('company/{id}', 'CompanyController@updateCompany'); // Update the company info
    // No Delete Functionality. This api is for the company info using the application.
    //====================== End of Company Api ====================== //

    //====================== Routers Api ====================== //
    Route::get('routers', 'RoutersController@getRouters'); // Get all Routers in the last 12 months
    Route::get('routers/all', 'RoutersController@getAllRouters'); // Get all Routers
    Route::get('routers/{id}', 'RoutersController@getOne'); // Get One Router for Editting
    Route::get('routers/search/byrouternum/{term}', 'RoutersController@byRouterNum'); // Searches for a router by router_num
    Route::get('routers/search/bypartnum/{term}', 'RoutersController@byPartNum'); // Searches for a router by part_num
    Route::get('routers/search/bystatus/{term}', 'RoutersController@byStatus'); // Searches for a router by status
    Route::post('routers/inventory', 'RoutersController@inventory'); // Sets STFI status router to II status
    Route::post('routers/store', 'RoutersController@addRouter'); // Add a single router
    Route::post('routers/sort', 'RoutersController@sortRouters'); // Reset the router keys for sorting
    Route::post('routers/department', 'RoutersController@updateRouterDept'); // Update a single routers department
    Route::post('routers/log/sort', 'RoutersController@logSort'); // log the sorted router intot he system log
    Route::patch('routers/{id}', 'RoutersController@updateRouter'); // Update a single router
    Route::delete('routers/{id}', 'RoutersController@deleteRouter'); // Delete a Single Router
    //====================== End of Routers Api ====================== //

    //====================== Departments Api ====================== //
    Route::get('departments', 'DepartmentsController@getDepartments'); // Get all Departments
    Route::get('departments/{id}', 'DepartmentsController@getOne'); // Get One Department for Editting
    Route::post('departments/store', 'DepartmentsController@addDepartment'); // Add a single department
    Route::post('departments/sort', 'DepartmentsController@sortDepartments'); // Resets all department keys
    Route::patch('departments/{id}', 'DepartmentsController@updateDepartment'); // Update a single department
    Route::delete('departments/{id}', 'DepartmentsController@deleteDepartment'); // Delete a Single department
    //====================== End of Departments Api ====================== //

    //====================== Purchase Orders Api ====================== //
    Route::get('purchaseorders/open', 'PurchaseordersController@getPurchaseordersOpen'); // Get pos in past 12 months
    Route::get('purchaseorders/closed', 'PurchaseordersController@getPurchaseordersClosed'); // Get all pos
    Route::get('purchaseorders/{id}', 'PurchaseordersController@getOne'); // Get One purchase order for Editing
    Route::get('purchaseorders/search/byponum/{status}/{term}', 'PurchaseordersController@byPoNum'); // searches for an po by po_num
    Route::get('purchaseorders/search/bycust/{status}/{term}', 'PurchaseordersController@byCust'); // searches for a po by customer name
    Route::get('purchaseorders/search/bypartnum/{status}/{term}', 'PurchaseordersController@byPartNum'); // searches for a po by part_num
    Route::post('purchaseorders/store', 'PurchaseordersController@addPurchaseorder'); // Add A Single PO
    Route::post('purchaseorders/sort', 'PurchaseordersController@sortPurchaseorders'); // Sort the purchase orders
    Route::patch('purchaseorders/clear', 'PurchaseordersController@clearAllRating'); // Clears all rating numbers from purchase orders
    Route::patch('purchaseorders/{id}', 'PurchaseordersController@updatePurchaseorder'); // Update a Single po
    Route::delete('purchaseorders/{id}', 'PurchaseordersController@deletePurchaseorder'); // Delete a Single po
    //====================== End of Purchase Orders Api ====================== //

    //====================== Inventory Api ====================== //
    Route::get('inventory', 'InventoryController@getInventory'); // Get all inventory ship tickets in the last 12 months
    Route::get('inventory/all', 'InventoryController@getAllInventory'); // Get all inventory
    Route::get('inventory/{id}', 'InventoryController@getOne'); // Get One Inventory ship ticket for Editing
    Route::get('inventory/search/partnum/{term}', 'InventoryController@byPartNum'); // searches for a ship ticket by part_num
    Route::get('inventory/search/date/{term}', 'InventoryController@byDate'); // searches for a ship ticket by date
    Route::get('inventory/search/status/{term}', 'InventoryController@byStatus'); // searches for a ship ticket by status
    Route::post('inventory/store', 'InventoryController@addInventory'); // Add A Single Inventory ship ticket
    Route::patch('inventory/{id}', 'InventoryController@updateInventory'); // Update a Single Inventory ship ticket
    Route::delete('inventory/{id}', 'InventoryController@deleteInventory'); // Delete a Single Inventory ship ticket
    //====================== End of Inventory Api ====================== //

    //====================== SystemLog Api ====================== //
    Route::get('systemlog', 'SystemlogController@getSystemLog'); // Get the last 12 months of system log
    Route::get('systemlog/all', 'SystemlogController@getSystemLogAll'); // Get all system entries months of system log
    Route::get('systemlog/month/{month}', 'SystemlogController@getSystemLogByMonth'); // Get log values by how ever many months ago
    Route::get('systemlog/date/{date}', 'SystemlogController@getSystemLogByDate'); // Get log values by how ever many months ago
    Route::delete('systemlog/{id}', 'SystemlogController@deleteSystemLogItem'); // delete on system log item
    //====================== End of SystemLog Api ====================== //

    //====================== Vendors Api ====================== //
    Route::get('vendors', 'VendorController@getVendors'); // Get all Vendors
    Route::get('vendors/{id}', 'VendorController@getVendor'); // Get One Vendor for Editing
    Route::post('vendors/store', 'VendorController@addVendor'); // Add A Single Vendor
    Route::patch('vendors/{id}', 'VendorController@updateVendor'); // Update a Single Vendor
    Route::delete('vendors/{id}', 'VendorController@deleteVendor'); // Delete a Single Vendor
    //====================== End of Vendors Api ================ //

    //====================== Purchases Api ====================== //
    Route::get('purchases/open', 'PurchasesController@getPurchasesOpen'); // Get pos in past 12 months
    Route::get('purchases/closed', 'PurchasesController@getPurchasesClosed'); // Get all pos
    Route::get('purchases/{id}', 'PurchasesController@getOne'); // Get One purchase order for Editing
    Route::get('purchases/search/byponum/{status}/{term}', 'PurchasesController@byPoNum'); // searches for an po by po_num
    Route::get('purchases/search/byvendor/{status}/{term}', 'PurchasesController@byVendor'); // searches for a po by vendor name
    Route::post('purchases/store', 'PurchasesController@addPurchase'); // Add A Single PO
    Route::patch('purchases/{id}', 'PurchasesController@updatePurchase'); // Update a Single po
    Route::delete('purchases/{id}', 'PurchasesController@deletePurchase'); // Delete a Single po
    //====================== End of Purchases Api ================ //
});
