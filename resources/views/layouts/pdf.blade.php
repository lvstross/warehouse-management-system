<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Levi Gonzales">
    <title>PDF Printout</title>
    <style type="text/css">
    /* alignment */
    .float-left { float: left; }
    .float-right { float: right; }
    .clear-fix { clear: both; }
    .clear-left { clear: left; }
    .clear-right { clear: right; }
    .inline { display: inline-block; }
    /* spacing */
    .padding-5 { padding: 5px; }
    .padding-15 { padding: 15px; }
    .padding-25 { padding: 25px; }
    .padding-50 { padding: 50px; }
    .padding-none { padding: 0; }
    .padding-50-lr { padding: 10px 50px; }
    .padding-20-sides { padding-left: 20px; padding-right: 20px; }
    .padding-25-sides { padding-left: 25px; padding-right: 25px; }
    .padding-46-sides { padding-left: 46px; padding-right: 46px; }
    .sm-padding-left { padding-left: 10px; }
    .less-margin-tb { margin: 5px 0; }
    .margin-bottom-20 { margin-bottom: 20px; }
    .margin-bottom-5 { margin-bottom: 5px; }
    .margin-bottom-10 { margin-bottom: 10px; }
    .margin-little { margin: 5px; }
    .margin-none { margin: 0; }
    .margin-top-25 { margin-top: 25px; }
    .margin-top-10 { margin-top: 10px; }
    .margin-top-5 { margin-top: 5px; }
    .space { height: 10px }
    .more-space { height: 90px; }
    .height-200 { max-height: 200px; }
    .height-150 { max-height: 150px; }
    .height-100 { height: 100px; }
    .small-width { width: 10%; }
    .qtr-width { width: 25%; }
    .less-qtr-width { width: 24%; }
    .half-width { width: 50%; }
    .three-qtr-width { width: 74%; }
    .full-width { width: 100%; }
    .under-half { width: 40%; }
    .just-under-half { width: 45%; }
    .qtr-width { width: 29%; }
    .set-height { height: 50px; }
    /* decoration */
    .border { border: 1px solid #000; }
    .light-border { border: 1px solid #aaa; }
    .underline { text-decoration: underline; }
    .sig-bottom-right {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 300px;
        border-top: 1px solid #000;
        padding: 5px;
    }
    /* text & font */
    .font { font-family: Helvetica, Arial, Georgia, serif }
    .no-bold { font-weight: normal; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .xs-text { font-size: 9px; }
    .sm-text { font-size: 12px; }
    .md-text { font-size: 14px; }
    .lg-text { font-size: 16px; }
    .xlg-text { font-size: 24px; }
    .control {position: fixed;bottom: 0;left: 0;}
    .table {border-collapse: collapse;}
    .td {border: .5px solid #000;padding: 4px;}
    .tr {border: .5px solid #000;}
    </style>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
    @yield('content')
</html>
