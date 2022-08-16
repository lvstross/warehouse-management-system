@extends('layouts.main')

@section('content')
    {{-- Users Template --}}
    @if(Request::is('users'))
        <div id="users-app">
            <users></users>
        </div>
    @endif

    {{-- Customers Template --}}
    @if(Request::is('customers'))
        <div id="customers-app">
            <customers></customers>
        </div>
    @endif

    {{-- Products Template --}}
    @if(Request::is('products'))
        <div id="products-app">
            <products></products>
        </div>
    @endif

    {{-- Invoices Template --}}
    @if(Request::is('invoices'))
        @if(session('company-message'))
            <div class="alert alert-info text-center alert-dismissible fixed-message" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('company-message') }}
            </div>
        @endif
        <div id="invoice-app">
            <invoices></invoices>
        </div>
    @endif

    {{-- Certs Template --}}
    @if(Request::is('certifications'))
        @if(session('company-message'))
            <div class="alert alert-info text-center alert-dismissible fixed-message" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('company-message') }}
            </div>
        @endif
        <div id="cert-app">
            <certifications></certifications>
        </div>
    @endif
    
    {{-- Routers and Departments Template --}}
    @if(Request::is('production') || Request::is('submitrouter'))
        @if(session('inventory-message'))
            <div class="alert alert-info text-center alert-dismissible fixed-message" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ session('inventory-message') }}
            </div>
        @endif
        <div id="routers-app">
            <routers></routers>
        </div>
    @endif

    {{-- Purchase Orders Template --}}
    @if(Request::is('purchaseorders'))
        <div id="purchaseorders-app">
            <purchaseorders></purchaseorders>
        </div>
    @endif

    {{-- System Log Template --}}
    @if(Request::is('systemlog'))
        <div id="systemlog-app">
            <systemlog></systemlog>
        </div>
    @endif

    {{-- Vendors Template --}}
    @if(Request::is('vendors'))
        <div id="vendors-app">
            <vendors></vendors>
        </div>
    @endif

    {{-- Purchases Template --}}
    @if(Request::is('purchases'))
        <div id="purchases-app">
            <purchases></purchases>
        </div>
    @endif
@endsection