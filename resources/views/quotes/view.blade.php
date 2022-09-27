@extends('layouts.master')

@section('content')

    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('quoteList')}}">Quotes</a></li>
        </ol>
        <h6 class="slim-pagetitle">Quote{{isset($quote)?' - '.$quote->id:''}}</h6>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success autoFadeOut">{{ session()->get('message') }}</div>
    @endif


    <div class="row row-sm">
        <div class="col-lg-8">

            <div class="card card-people-list mg-b-20 ">

                @if(isset($quote->customer->detail))
                    <h5 class="d-flex">
                        Customer:&nbsp;
                        <a class="pl-2" href="javascript:void(0)">
                            {{$quote->customer->detail->first_name .' '.$quote->customer->detail->last_name}}
                        </a>
                    </h5>
                @endif


                @if(trim($quote->note) != '')
                    <div class="pd-t-10 taskDesc mg-t-5">
                        {!! nl2br($quote->note) !!}
                    </div>
                @endif

            </div>

            <div class="card card-people-list mg-b-20 ">

                @if(count($products))

                    <div style="font-weight:bold;">Products</div>
                    <div class="section-wrapper mg-t-10 " style="padding:0px;">
                        <div class="table-responsive">
                            <table class="table table-striped mg-b-0">
                                <thead>
                                <tr>
                                    <th width="50%">Title</th>
                                    <th class="tx-center" width="10%">Price</th>
                                    <th class="tx-center" width="10%">Quantity</th>
                                    <th class="tx-center" width="10%">Discount%</th>
                                    <th class="tx-center" width="10%">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?PHP
                                $pTotal = 0;
                                $shipping = 0;
                                ?>
                                @foreach($products as $row)
                                    <?PHP
                                    $s_tot = 0;
                                    $s_tot = ($row->price * $row->quantity);

                                    if ($row->discount == 0 || $row->discount == '') {
                                    } else {
                                        $s_tot = $s_tot - (($s_tot * $row->discount) / 100);
                                    }

                                    $s_tot = number_format($s_tot, 2, '.', '');
                                    $shipping = isset($row->quote->shipping)?$shipping + $row->quote->shipping:$shipping+ 0;
                                    $pTotal = $pTotal + $s_tot;

                                    ?>
                                    <tr>
                                        <td style="white-space: nowrap">
                                            {{isset($row->product->title)?$row->product->title:'-'}}
                                        </td>
                                        <td class="tx-center">${{$row->price}}</td>
                                        <td class="tx-center">{{$row->quantity}}</td>
                                        <td class="tx-center">{{$row->discount}}%</td>
                                        <td class="tx-au">${{$s_tot}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4"
                                        style="text-align:right; font-weight:bold;">
                                        Shipping :
                                    </td>
                                    <td class="tx-center" style="font-weight:bold;">
                                        ${{number_format($shipping,2,'.','')}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"
                                        style="text-align:right; font-weight:bold;">
                                        Total :
                                    </td>
                                    <td class="tx-center" style="font-weight:bold;">
                                        ${{number_format($pTotal+$shipping,2,'.','')}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4 mg-t-20 mg-lg-t-0">
            <div class="row row-sm">
                <div class="col-lg-6">
                    <a href="{{route('quoteEdit',$quote->id)}}"
                       class="btn btn-primary btn-sm btn-block mg-b-10"><i class="icon ion-compose"></i> Edit
                        Quote</a>
                </div>

                <div class="col-lg-6">
                    <a href="{{route('quoteDelete',$quote->id)}}"
                       class="btn btn-danger btn-sm btn-block mg-b-10 confirm_deletion"><i
                            class="icon ion-android-delete"></i> Delete Quote</a>

                </div>
            </div>


            <div class="card card-people-list mg-b-20 pd-0 ">
                <div class="card-contact mg-b-10" style="border:none;">


                    <p class="contact-item" style="border-top:0px;">
                        <span>Quote ID:</span>
                        {{$quote->id}}
                    </p>
                    <p class="contact-item">
                        <span>Sub Total:</span>
                        ${{$quote->sub_total}}
                    </p>
                    <p class="contact-item">
                        <span>Shipping:</span>
                        ${{$quote->shipping}}
                    </p>
                    <p class="contact-item">
                        <span>Taxes:</span>
                        ${{$quote->tax}}
                    </p>

                    <p class="contact-item" style="font-weight:600;">
                        <span>Total:</span>
                        ${{$quote->total_price}}
                    </p>
                    <p class="contact-item">
                        <span>Status:</span>
                        <span class="btn btn-oblong btn-primary btn-sm mg-b-10"
                              style="cursor:default;">{{$quote->status}}</span>
                    </p>
                    @if(isset($quote->CreatedUser->uniqname) && $gen->isPermission('quote') == 1)
                        <p class="contact-item">
                            <span>Created By:</span>
                            <a href="javascript:void(0)">
                                {{$quote->creator->detail->first_name}} {{$quote->creator->detail->last_name}}
                            </a>
                        </p>
                    @endif

                </div>
            </div>


            <div class="card card-people-list mg-b-20">
                <div class="slim-card-title">Shipping Address</div>
                <div style="padding-left:0px; margin: 15px 0px;">
                    <div>{{$quote->address}}</div>
                    <div>{{$quote->address2}}</div>
                    <div>{{$quote->city}}@if($quote->state != '')
                            , {{$quote->state}}@endif @if($quote->zip != '')
                            , {{$quote->zip}}@endif</div>
                    <div>{{$quote->country}}</div>
                    @if(trim($quote->phone) != '')
                        <p style="margin: 5px 0px;"><i
                                class="icon ion-ios-telephone-outline "></i> {{$quote->phone}}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
