<?php

// Route for loading the front guest page
Route::get('/', function () { return view('welcome'); });

// Authentications shortcut for default routes.
Auth::routes();

/**
*   Single Page App Routes
*   'Auth' Middleware has been added on the PagesController.php
*   page via the controller constructor.
*/

// Route Group passed through web and auth middleware for the main page.
Route::group(['middleware' => ['web', 'auth']], function(){
    Route::get('/dashboard', 'HomeController@dashboard')->name('home');
    Route::get('/settings', 'HomeController@settings')->name('settings');
    Route::get('/submitrouter', 'PagesController@routers');
    Route::get('/invoices', 'PagesController@invoices');
    Route::get('/users', 'PagesController@users');
    Route::get('/customers', 'PagesController@customers');
    Route::get('/products', 'PagesController@products');
    Route::get('/production', 'PagesController@routers');
    Route::get('/purchaseorders', 'PagesController@purchaseorders');
    Route::get('/certifications', 'PagesController@certs');
    Route::get('/systemlog', 'PagesController@systemlog');
    Route::get('/vendors', 'PagesController@vendors');
    Route::get('/purchases', 'PagesController@purchases');
    // PDF Generation Routes
    Route::get('/pdf/invoice/{id}', 'PDFController@invoice');
    Route::get('/pdf/shipper/{id}', 'PDFController@shipper');
    Route::get('/pdf/report/invoice/{start}/{end}/{reportname?}', 'PDFController@invoiceReport');
    Route::get('/pdf/report/router/{start}/{end}/{reportname?}', 'PDFController@routerReport');
    Route::get('/pdf/report/inventory/{start}/{end}/{reportname?}', 'PDFController@inventoryReport');
    Route::get('/pdf/shipticket/{id}', 'PDFController@shipticket');
    Route::post('/pdf/certification', 'PDFController@certPrintOut');
    Route::get('/pdf/coor', 'PDFController@coor');
    Route::get('/pdf/coor/production', 'PDFController@coorProduction');
    Route::get('/pdf/coor/task', 'PDFController@coorTask');
    Route::get('/pdf/coor/ontimereport/{start}/{end}/{title?}', 'PDFController@onTimeReport');
    Route::get('/pdf/coor/salesreport/{start}/{end}/{show}/{status}', 'PDFController@salesReport');
    Route::get('/pdf/purchases/ontimereport/{start}/{end}/{vendor}', 'PDFController@onTimeReportPurchases');
    Route::get('/pdf/purchases/purchasingreport/{start}/{end}', 'PDFController@purchasingReport');
    Route::get('/pdf/purchases/printout/{id}', 'PDFController@PurchasePrintOut');
    Route::get('/pdf/purchases/receival/{id}', 'PDFController@ReceiverPrintOut');
    // Importing and Exporting User Files
    Route::post('user/import', 'UsersController@import');
    Route::get('user/export/excel', 'UsersController@exportExcel');
    // Importing and Exporting Customers Files
    Route::post('customers/import', 'CustomersController@import');
    Route::get('customers/export/excel', 'CustomersController@exportExcel');
    // Importing and Exporting Products Files
    Route::post('products/import', 'ProductsController@import');
    Route::get('products/export/excel', 'ProductsController@exportExcel');
    // Importing and Exporting Invoices Files
    Route::post('invoices/import', 'InvoicesController@import');
    Route::get('invoices/export/excel', 'InvoicesController@exportExcel');
    // Importing and Exporting Routers Files
    Route::post('routers/import', 'RoutersController@import');
    Route::get('routers/export/excel', 'RoutersController@exportExcel');
    // Importing and Exporting Department Files
    Route::post('departments/import', 'DepartmentsController@import');
    Route::get('departments/export/excel', 'DepartmentsController@exportExcel');
    // Importing and Exporting Purchaseorders Files
    Route::post('purchaseorders/import', 'PurchaseordersController@import');
    Route::get('purchaseorders/export/excel', 'PurchaseordersController@exportExcel');
    // Importing and Exporting Inventory Files
    Route::post('inventory/import', 'InventoryController@import');
    Route::get('inventory/export/excel', 'InventoryController@exportExcel');
    // Importing and Exporting Systemlog Files
    Route::post('systemlog/import', 'SystemlogController@import');
    Route::get('systemlog/export/excel', 'SystemlogController@exportExcel');
    // Importing and Exporting Company Files
    Route::post('company/import', 'CompanyController@import');
    Route::get('company/export/excel', 'CompanyController@exportExcel');
    // Importing and Exporting Vendor Files
    Route::post('vendors/import', 'VendorController@import');
    Route::get('vendors/export/excel', 'VendorController@exportExcel');
    // Importing and Exporting Purchasing Files
    Route::post('purchases/import', 'PurchasesController@import');
    Route::get('purchases/export/excel', 'PurchasesController@exportExcel');
});
