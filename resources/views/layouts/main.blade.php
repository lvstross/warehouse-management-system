<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Levi Gonzales">
    <title>Dataentry System</title>
    <!-- Stylesheets -->
    <!-- Latest compiled and minified CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}?v=4">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/font-awesome.min.css') }}">
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="main-color">
    <!-- #wrapper -->
    <div id="wrapper">
        <!-- #page-wrapper -->
        <div id="page-wrapper">
            <!-- .container -->
            <div id="main-container" class="container">
                @include('inc.nav')
                @include('inc.heading')

                @yield('content')
            </div>
            <!-- /.container-fluid end -->
        </div>
        <!-- /#page-wrapper end -->
   </div>
    <!-- /#wrapper end -->
    <script src="{{ asset('/js/app.js') }}?v=4.2.5"></script>
    <noscript>This application required Javascript to be enabled. Please enable javascript to use this application.</noscript>
</body>
</html>