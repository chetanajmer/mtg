{{-- Estimated delivery time, shown on the product page and in the category page filter. Suggests wording already in use, so one delivery time doesn't become several filter options. --}}
@php
  $deliveryTimes = \App\Models\Product::whereNotNull('estimated_delivery_time')->where('estimated_delivery_time', '!=', '')
      ->orderBy('estimated_delivery_time')->distinct()->pluck('estimated_delivery_time');
@endphp
<div class="col-md-6">

  <div class="form-group m-b-20">

    <label>Estimated Delivery Time</label>

    <input type="text" class="form-control" name="estimated_delivery_time" maxlength="250" list="deliveryTimeOptions" placeholder="e.g : 7-10 working days" value="{{ $value ?? '' }}">

    <datalist id="deliveryTimeOptions">
      @foreach($deliveryTimes as $deliveryTime)
        <option value="{{ $deliveryTime }}"></option>
      @endforeach
    </datalist>

  </div>

</div>
