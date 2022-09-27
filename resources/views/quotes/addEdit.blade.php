@extends('layouts.master')

@section('styles')
    <link href="{{ asset('assets/css/spinkit.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <style>
        span.select2-selection--single {
            border: 1px solid #ced4da !important;
            border-radius: 1px !important;
            height: 40px !important;
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        .qtyText {
            height: 25px;
            padding: 0 0 0 2px !important;
            line-height: auto;
        }

    </style>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/quote.js') }}"></script>
@endsection



@section('content')


    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('quoteList')}}">Quotes</a></li>
            @if(isset($quote))
                <li class="breadcrumb-item"><a href="{{route('quoteView',$quote->id)}}">{{$quote->id}}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">
                @if(isset($quote))
                    Edit
                @else
                    New
                @endif
            </li>

        </ol>
        <h6 class="slim-pagetitle">
            {{(isset($quote))?'Edit':'New'}} Quote
        </h6>
    </div>
    <input type="hidden" id="edit_module" value="{{isset($quote)?'1':'0'}}">


    <div class="section-wrapper">


        @if($errors->any())
            <div class="alert alert-danger autoFadeOut">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @if (session()->has('message'))
            <div class="alert alert-success autoFadeOut">{{ session()->get('message') }}</div>
        @endif


        <div class="mg-t-0">

            <form method="post" action="{{route('quoteSave')}}" enctype="multipart/form-data"
                  onsubmit="generateCartTotal()">
                @csrf
                <input type="hidden" name="id" value="{{(isset($quote->id))?$quote->id:0}}"/>

                <div class="row mg-b-20">

                    <div class="col-lg-6 pd-b-20">
                        <div id="customer_inner_content">
                            @if(isset($quote) && ($quote->status == "0"))
                                <label class="slim-card-title">Customer</label>
                                <div>
                                    @php
                                        $customer = $quote->customer->detail;
                                    @endphp
                                    {{$customer->first_name.' '.$customer->last_name}}
                                </div>
                            @else
                                @include('include.customer_list')
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-3 pd-b-20">

                    </div>
                    <div class="col-lg-3 pd-b-20">

                    </div>
                </div>

                <div class="row mg-b-20 d-none" id="customer_shipping_content">
                    @include('include.shipping')
                </div>

                <!--
                    Products Section
                -->
                <div class="row mg-b-20">
                    @if(isset($quote) && ($quote->status == DECLINED))
                        <div class="col-lg-6 pd-b-20">
                            <div class="slim-card-title">Products</div>
                        </div>
                        <div class="col-lg-12 pd-b-20">
                            <div class="section-wrapper mg-t-10 " style="padding:0px;">
                                <div class="table-responsive" id="quotes_products">
                                    <table class="table table-striped mg-b-0">
                                        <thead>
                                        <tr>
                                            <th width="50%">Title</th>
                                            <th class="tx-center" width="10%">Price</th>
                                            <th class="tx-center" width="10%">Quantity</th>
                                            <th class="tx-center" width="10%">Discount %</th>
                                            <th class="tx-center" width="10%">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($quote) &&  isset($quote->products) && count($quote->products))
                                            @foreach($quote->products as $row)
                                                <?PHP
                                                $s_tot = $row->price * $row->quantity;

                                                if ($row->discount == 0 || $row->discount == '') {
                                                } else {
                                                    $s_tot = $s_tot - (($s_tot * $row->discount) / 100);
                                                }
                                                $s_tot = number_format($s_tot, 2, '.', '');
                                                ?>
                                                <tr id="cart_item_{{$row->product_id}}">
                                                    <td>
                                                        @if(isset($row->product->title))
                                                            {{$row->product->title}}
                                                        @endif
                                                    </td>
                                                    <td class="tx-center">
                                                        ${{$row->price}}
                                                    </td>
                                                    <td class="tx-center">
                                                        {{$row->quantity}}
                                                    </td>
                                                    <td class="tx-center">
                                                        {{$row->discount}}%
                                                    </td>

                                                    <td class="tx-center">
                                                        ${{$s_tot}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-6 pd-b-1" style="font-weight:600;">
                            Products
                        </div>
                        <div class="col-lg-6 pd-b-1" style="text-align:right;">
                            <a class="btn btn-primary btn-sm" href="javascript:void(0)" data-toggle="modal"
                               data-target="#product_selection_popup_model" title="Add Product"><i
                                    class="fa fa-plus"></i>
                                Add Products</a>
                        </div>

                        <div class="col-lg-12 pd-b-20">
                            <div class="section-wrapper mg-t-10 " style="padding:0px;">
                                <div class="table-responsive" id="quotes_products">
                                    <table class="table table-striped mg-b-0">
                                        <thead>
                                        <tr>
                                            <th width="50%">Title</th>
                                            <th class="tx-center" width="10%">Price</th>
                                            <th class="tx-center" width="10%">Quantity</th>
                                            <th class="tx-center" width="10%">Discount %</th>
                                            <th class="tx-center" width="10%">Total</th>
                                            <th class="tx-center" width="5%">&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($quote) &&  isset($quote->products) && count($quote->products))
                                            @foreach($quote->products as $row)
                                                <?PHP
                                                $s_tot = $row->price * $row->quantity;

                                                if ($row->discount == 0 || $row->discount == '') {
                                                } else {
                                                    $s_tot = $s_tot - (($s_tot * $row->discount) / 100);
                                                }
                                                $s_tot = number_format($s_tot, 2, '.', '');
                                                ?>
                                                <tr id="cart_item_{{$row->product_id}}">
                                                    <td>
                                                        @if(isset($row->product->title))
                                                            {{$row->product->title}}
                                                        @endif

                                                        <input type="hidden" class="cart_product_id"
                                                               name="cart_product_id[]" value="{{$row->product_id}}"/>
                                                        <input type="hidden" id="product_retail_{{$row->product_id}}"
                                                               class="cart_product_retail"
                                                               name="cart_product_retail[]" value="{{$row->price}}"/>
                                                        <input type="hidden" id="is_changed_{{$row->product_id}}"
                                                               value="0"/>
                                                    </td>

                                                    <td class="tx-center">${{$row->price}}</td>

                                                    <td class="">
                                                        <div class="tx-center">
                                                            <input
                                                                id="quantity_{{$row->product_id}}"
                                                                class="form-control qtyText cart_product_qty"
                                                                name="cart_product_qty[]" type="number"
                                                                min="{{$row->product->min_quantity}}" step="1"
                                                                onchange="javascript:editProduct({{$row->product_id}})"
                                                                onfocusout="saveEdit({{$row->product_id}})"
                                                                onkeypress="return /[0-9]/i.test(event.key);"
                                                                value="{{$row->quantity}}"/>
                                                        </div>
                                                        <small>
                                                            <a href="javascript:editProduct({{$row->product_id}})">Update</a>
                                                        </small>
                                                    </td>
                                                    <td class="">
                                                        <div class="tx-center">
                                                            <input
                                                                class="form-control qtyText cart_product_discount"
                                                                id="discount_{{$row->product_id}}"
                                                                name="cart_product_discount[]" type="number" min="0"
                                                                step=".01"
                                                                onchange="javascript:editProduct({{$row->product_id}})"
                                                                onfocusout="saveEdit({{$row->product_id}})"
                                                                value="{{$row->discount}}"/>
                                                        </div>
                                                        <small>
                                                            <a href="javascript:changeValue({{$row->product_id}})">Update</a>
                                                        </small>
                                                    </td>


                                                    <td class="tx-center cart_product_total_price"
                                                        id="total_{{$row->product_id}}">$s_tot
                                                    </td>
                                                    <td class="tx-center"><a
                                                            href="javascript:removethisproduct({{$row->product_id}})"><i
                                                                class="fa fa-trash-alt"></i></a></td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <?PHP
                $t_shipping_cost = 0.00;
                $t_taxes_cost = 0.00;

                $t_shipping_cost = (isset($quote->shipping)) ? $quote->shipping : 0.00;
                $t_taxes_cost = (isset($quote->tax)) ? $quote->tax : 0.00;



                ?>
                <div class="row mg-b-20">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <div style="font-weight:600">Quote Totals</div>

                        @if(isset($quote) && ($quote->status == DECLINED))
                            <table class="table table-striped mg-b-0" style="border:1px solid #CCC;">
                                <tbody>
                                <tr>
                                    <th width="70%">Subtotal</th>
                                    <td width="30%" id="">${{$quote->sub_total}}</td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td>
                                        {{$quote->shipping}}
                                    </td>
                                </tr>
                                <tr style="font-weight:600; font-size:20px;">
                                    <th>Total</th>
                                    <td id="">${{$quote->total_price}}</td>
                                </tr>


                                </tbody>
                            </table>
                        @else
                            <table class="table table-striped mg-b-0" style="border:1px solid #CCC;">
                                <tbody>
                                <tr>
                                    <th width="70%">Subtotal</th>
                                    <td width="30%" id="dsp_subtotal">$0.00</td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td>
                                        <input class="form-control" type="number" min="0" step=".01" id="shipping_cost"
                                               name="shipping_cost" value="{{$t_shipping_cost}}"/>
                                    </td>
                                </tr>
                                <tr style="font-weight:600; font-size:20px;">
                                    <th>Total</th>
                                    <td id="dsp_total">$0.00</td>
                                </tr>


                                </tbody>
                            </table>
                        @endif

                    </div>
                </div>

                @if(isset($quote) && ($quote->status == DECLINED))
                    <div class="row mg-b-20">
                        <div class="col-lg-4">
                            <div class="slim-card-title">Quote Status</div>
                            <input type="text" readonly value="{{$quote->status}}" class="form-control mt-2">
                        </div>
                    </div>
                @else
                    <div class="row mg-b-20">
                        <div class="col-lg-12 pd-b-20">
                            <label class="form-control-label">Note </label>
                            <textarea class="form-control" name="note"
                                      rows="5">{{ (old('note')) ? old('note'):((isset($quote->note))?$quote->note:'') }}</textarea>
                        </div>
                    </div>

                    <?PHP
                    $t_status = (old('status')) ? old('status') : ((isset($quote->status)) ? $quote->status : '1');
                    ?>

                    <div class="row mg-b-20">
                        <div class="col-lg-4">
                            <label class="form-control-label">Quote Status </label>
                            <select name="status" class="form-control">
                                @foreach(getDataApps('quoteStatus') as $rowStatus)
                                    <option value="{{$rowStatus}}" {{($rowStatus == $t_status)?'selected':''}}
                                    ">{{$rowStatus}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                @endif
                <div class="form-layout-footer">
                    <button type="submit" class="btn btn-primary bd-0"><i class="fa fa-save"></i> Save</button>
                </div>


            </form>

        </div>

    </div>
    <!-- MODAL -->
    <div id="product_selection_popup_model" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center" role="document">
            <div class="modal-content bd-0 tx-14">
                <div class="modal-header pd-y-20 pd-x-25">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"> Select Product</h6>
                    <button type="button" class="close closereload" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-25">
                    <div class="row mg-b-10">
                        <div class="col-lg-12">
                            <label class="form-control-label">Select Product</label>
                            <select name="select_product[]" id="select_product" multiple="multiple"
                                    class="form-control select2-show-search" style="width:450px !important;">
                                @foreach(getDataApps('products') as $row)
                                    <option value="{{$row->id}}" pTitle="{{$row->title}}"
                                            pRetail="{{$row->price}}">
                                        {{$row->title}}
                                        (${{$row->price}})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary " onclick="addProductsToCart()"><i
                            class="fa fa-plus"></i> Add
                    </button>
                    <button type="button" class="btn btn-secondary closereload" data-dismiss="modal" aria-label="Close">
                        Close
                    </button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->
@endsection
