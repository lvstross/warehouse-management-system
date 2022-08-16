@extends('layouts.status')

@section('content')
<div class="jumbotron">
    <h1 class="text-center">404</h1>
    <h4 class="text-center">Sorry, the page you are looking for does not exists. Click <a href="{{ url('/') }}">HERE</a> to find your way back.</h4>
</div>
@endsection