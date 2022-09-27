<?PHP
$quote_customer_id = (old('customer_id')) ? old('customer_id') : ((isset($quote->customer_id)) ? $quote->customer_id : '');
?>
<label class="form-control-label">Customer <span class="tx-danger">*</span></label>

<select name="customer_id" id="customer_id" required {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
        class="form-control select2-show-search-customer">
    <option value="" selected disabled></option>
    @foreach($customers as $customer)
        <option value="{{$customer->id}}" {{(isset($quote->customer_id) && $quote->customer_id == $customer->id)?'selected':''}}>{{$customer->detail->first_name.' '.$customer->detail->last_name}}</option>
    @endforeach
</select>

