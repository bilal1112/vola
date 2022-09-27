
<div class="col-lg-6 pd-b-20">
    <label class="form-control-label">Street address <span class="tx-danger">*</span></label>

    <input type="text" class="form-control" name="address" required="required" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('address')) ? old('address'):((isset($quote) && isset($quote->address))?$quote->address:'') }}">
</div>

<div class="col-lg-4 pd-b-20">
    <label class="form-control-label">Suite/Apt.</label>
    <input type="text" class="form-control" name="address2" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('address2')) ? old('address2'):((isset($quote) && isset($quote->address2))?$quote->address2:'') }}">
</div>
<div class="col-lg-4  pd-b-20">
    <label class="form-control-label">City <span class="tx-danger">*</span></label>
    <input type="text" class="form-control" name="city" required="required" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('city')) ? old('city'):((isset($quote) && isset($quote->city))?$quote->city:'') }}">
</div>
<div class="col-lg-3 pd-b-20">
    <label class="form-control-label">State <span class="tx-danger">*</span></label>
    <input type="text" class="form-control" name="state" required="required" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('state')) ? old('state'):((isset($quote) && isset($quote->state))?$quote->state:'') }}">

</div>
<div class="col-lg-2 pd-b-20">
    <label class="form-control-label">Postal Code <span class="tx-danger">*</span></label>
    <input type="text" class="form-control" name="zip" required="required" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('zip')) ? old('zip'):((isset($quote) && isset($quote->zip))?$quote->zip:'') }}">

</div>

<div class="col-lg-3 pd-b-20">
    <label class="form-control-label">Country <span class="tx-danger">*</span></label>
    <input type="text" class="form-control" name="country" required="required" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('country')) ? old('country'):((isset($quote) && isset($quote->country))?$quote->country:'') }}">

</div>
<div class="col-lg-4 pd-b-20">
    <label class="form-control-label">Phone</label>
    <input type="text" class="form-control phone_us" name="phone" {{(isset($quote) && $quote->status == DECLINED)?'disabled':""}}
           value="{{ (old('phone')) ? old('phone'):((isset($quote) && isset($quote->phone))?$quote->phone:'') }}">

</div>
