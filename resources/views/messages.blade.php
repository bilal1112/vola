@extends('layouts.master')

@section('content')
    <div class="pd-t-30">

        @if($type == "error" || $type == "danger")
            <div class="alert alert-danger" data-uk-alert="">
                {!! $message !!}
            </div>
        @elseif($type == "warning")
            <div class="alert alert-warning" data-uk-alert="">
                {!! $message !!}
            </div>
        @elseif($type == "success")
            <div class="alert alert-success" data-uk-alert="">
                {!! $message !!}
            </div>
        @elseif($type == "info")
            <div class="alert alert-info" data-uk-alert="">
                {!! $message !!}
            </div>
        @elseif($type == "plain")
            <div>
                {!! $message !!}
            </div>

        @elseif($type == "formated")

            <div class="section-wrapper">

                @if(isset($sub_type) && $sub_type == "error" || $sub_type == "danger")
                    <div style="padding:20px 20px 20px 20px ;">
                        <div class="alert alert-danger" data-uk-alert="">
                            {!! $message !!}
                        </div>
                    </div>
                @else
                    <div>
                        {!! $message !!}
                    </div>
                @endif

            </div>


        @else
            <div class="alert" data-uk-alert="">
                {!! $message !!}
            </div>
        @endif


    </div>

@endsection
