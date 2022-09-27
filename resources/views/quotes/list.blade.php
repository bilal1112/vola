@extends('layouts.master')

@section('scripts')
    <script>
        let SEARCH_KEY = '{{$searchKey}}';
        let STATUS = '{{$status}}';
    </script>
@endsection
@section('content')

    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quotes</li>
        </ol>
        <h6 class="slim-pagetitle">Quotes</h6>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success autoFadeOut">{{ session()->get('message') }}</div>
    @endif


    <div class="row row-sm">
        <form method="get" id="filterForm" class="w-100">
            <div class="col-lg-12">
                <div class="row row-sm">
                    <div class="col-lg-10">
                        <div class="input-group mg-0">
                            <input type="text" class="form-control" name="s" value="{{$searchKey}}"
                                   placeholder="Search by customer name or quote id....">

                            <select class="form-control" name="status" onchange="this.form.submit()">
                                <option value="" {{($status=='')?'selected="selected"':''}}>All Status</option>

                                @foreach(getDataApps('quoteStatus') as $rowStatus)
                                    <option value="{{$rowStatus}}" {{($status == $rowStatus)?'selected':''}}>{{$rowStatus}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-2">

                    </div>
                </div>

            </div>

            <div class="col-lg-6 mt-2">
                <div class="row row-sm">
                    <div class="col-lg-2">
                        <button class="btn btn-primary border" type="submit">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12" style="text-align:right;">
            <a class="btn btn-primary btn-sm" href="{{route('quoteAdd')}}" title="Add New Quote">
                <i class="fa fa-plus"></i> Add New Quote
            </a>
        </div>
    </div>



    <div class="section-wrapper mg-t-10 p-0">
        <div class="table-responsive">
            <table class="table table-striped mg-b-0">
                <thead>
                <tr style="white-space: nowrap">
                    <th class="ordering {{$sortID}}" field="sortID" orderBy="{{$sortID}}">
                        Quote ID
                    </th>
                    <th class="ordering {{$orderCustomer}}" field="orderCustomer" orderBy="{{$orderCustomer}}">
                        Customer
                    </th>
                    <th class="ordering tx-center {{$orderProductCount}}" field="orderProductCount"
                        orderBy="{{$orderProductCount}}">Products
                    </th>

                    <th class="ordering tx-center {{$orderTotalPrice}}" field="orderTotalPrice"
                        orderBy="{{$orderTotalPrice}}">Total Amount
                    </th>

                    <th class="ordering tx-center {{$orderCreatedDate}}" field="orderCreatedDate"
                        orderBy="{{$orderCreatedDate}}">Creation Date
                    </th>
                    <th class="ordering tx-center {{$orderStatus}}" field="orderStatus" orderBy="{{$orderStatus}}">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody>
                @if(count($quotes))
                    @foreach($quotes as $row)
                        <tr>
                            <td width="10%"><a href="{{route('quoteView',$row->id)}}">{{$row->id}}</a></td>
                            <td>{{$row->customer_name}}</td>
                            <td class="tx-center">{{$row->products_count}}</td>
                            <td class="tx-center">${{$row->total_price}}</td>
                            <td class="tx-center">{{date('d/m/Y',strtotime($row->created_at))}}</td>
                            <td class="tx-center">{{$row->status}}</td>
                        </tr>
                    @endforeach
                @endif

                </tbody>
            </table>


            @if(count($quotes))
                <?PHP
                $array = array();

                if ($searchKey != '') {
                    $array['s'] = $searchKey;
                }
                if ($sortID != '') {
                    $array['sortID'] = $sortID;
                }
                if ($orderCustomer != '') {
                    $array['orderCustomer'] = $orderCustomer;
                }

                if ($orderProductCount != '') {
                    $array['orderProductCount'] = $orderProductCount;
                }

                if ($orderTotalPrice != '') {
                    $array['orderTotalPrice'] = $orderTotalPrice;
                }
                if ($orderCreatedDate != '') {
                    $array['orderCreatedDate'] = $orderCreatedDate;
                }
                if ($orderStatus != '') {
                    $array['orderStatus'] = $orderStatus;
                }

                ?>

                <div class="pd-20">{{ $quotes->appends($array)->links() }}</div>

            @else
                <div class="pd-20">No Results Found</div>
            @endif
        </div>
    </div>

@endsection
