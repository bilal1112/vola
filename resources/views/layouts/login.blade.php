<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>let homeURL = "{{route('/')}}"; </script>
    <!-- Meta -->
    <meta name="description" content="Vola">


    <title>{{ config('app.name', 'Vola') }}</title>


    <!-- Vendor css -->

    <link href="{{ asset('assets/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ionicons.css') }}" rel="stylesheet">

    <!-- Slim CSS -->
    <link href="{{ asset('assets/css/slim.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/slim_updates.css') }}" rel="stylesheet">
    @section('styles')
    @show
</head>
<body>
<div  class="overlayAutoClick"></div>
<div class="d-flexS bg-gray-200 ht-300 pos-relativeXX align-items-center overlayAutoClickSpinner">
    <div class="sk-cube-grid">
        <div class="sk-cube sk-cube1"></div>
        <div class="sk-cube sk-cube2"></div>
        <div class="sk-cube sk-cube3"></div>
        <div class="sk-cube sk-cube4"></div>
        <div class="sk-cube sk-cube5"></div>
        <div class="sk-cube sk-cube6"></div>
        <div class="sk-cube sk-cube7"></div>
        <div class="sk-cube sk-cube8"></div>
        <div class="sk-cube sk-cube9"></div>
    </div>
</div>



<div class="signin-wrapper">
    @yield('content')
</div><!-- signin-wrapper -->



<script src="{{ asset('assets/js/jquery-3.3.1.min.js')}}"></script>


@section('scripts')

@show

</body>
</html>
