@extends('layouts.main')

@section('content')
    <h2 class="text-center">Import / Export</h2>
    @include('inc.im-ex-session-messages')
    <div class="flex-center">
        <button type="button" id="ieBtn" class="btn btn-default btn-xs">Show Imports and Exports</button>
    </div>
    <br>
    <div id="ieCont" class="well">
        @include('inc.im-ex-user')
        @include('inc.im-ex-customer')
        @include('inc.im-ex-vendor')
        @include('inc.im-ex-product')
        @include('inc.im-ex-invoice')
        @include('inc.im-ex-purchases')
        @include('inc.im-ex-routers')
        @include('inc.im-ex-departments')
        @include('inc.im-ex-purchaseorder')
        @include('inc.im-ex-inventory')
        @include('inc.im-ex-systemlog')
        @include('inc.im-ex-company')
    </div>
    <div id="settings-app">
        <settings></settings>
    </div>
@endsection
