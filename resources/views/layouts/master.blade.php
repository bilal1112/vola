<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Vola') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">


    <!-- vendor css -->
    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ionicons.css') }}" rel="stylesheet">


    <link href="{{ asset('assets/css/lightbox.min.css') }}" rel="stylesheet">
    <!-- Slim CSS -->
    <link href="{{ asset('assets/css/slim_n.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/slim_updates.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @section('styles')
    @show


</head>
<body>
<div class="overlayAutoClick"></div>
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

@section('header')
    @include('include.header')
    @include('include.navbar')
@show

<div class="slim-mainpanel">
    <div class="container">
        @yield('content')
    </div>
</div>


<!-- BASIC MODAL --><!-- modal_default-->
<div id="modal_default" class="modal fade">
    <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14 modal_default_content">
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->

<!-- BASIC MODAL --><!-- General Popup-->
<div id="general_popup_model" class="modal fade">
    <div class="modal-dialog modal-dialog-vertical-center" role="document">
        <div class="modal-content bd-0 tx-14 general_popup_model_content">
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->

<!-- LARGE MODAL -->
<div id="general_popup_model_large" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tx-size-sm general_popup_model_content">
        </div>
    </div><!-- modal-dialog -->
</div><!-- modal -->


<div id="delete_confirm_modal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <?php /*?><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button><?php */?>

                <p class="mg-b-20   mg-x-20" id="error_message">Are you sure you want to delete?</p>
                <a href="#" id="deletelinkbutton" class="btn btn-danger btn-sm pd-x-25">Yes</a>

                <button type="button" class="btn btn-secondary btn-sm  pd-x-25" data-dismiss="modal" aria-label="Close">
                    No
                </button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->


<div class="slim-footer">
    <div class="container">
        <p>Copyright {{date('Y')}} &copy; All Rights Reserved. Vola</p>
    </div><!-- container -->
</div><!-- slim-footer -->


<script src="{{ asset('assets/js/jquery-3.3.1.min.js')}}"></script>


<script src="{{ asset('assets/js/lightbox.js')}}"></script>
<script src="{{ asset('assets/js/jquery.mask.js')}}"></script>


<script src="{{ asset('assets/js/popper.js')}}"></script>
<script src="{{ asset('assets/js/bootstrap.js')}}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js')}}"></script>

<script src="{{ asset('assets/js/jquery-ui.js')}}"></script>


<script src="{{ asset('assets/js/slim.js')}}"></script>

<script src="{{ asset('assets/js/custom.js')}}"></script>

<!-- Const -->
<script>
    let HOME_URL = "{{route('/')}}";
    let QUOTE_LIST = '{{route('quoteList')}}';
</script>

@section('scripts')

@show


</body>
</html>
