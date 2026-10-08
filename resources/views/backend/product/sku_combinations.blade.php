@if(count($combinations[0]) > 0)
	<table class="table table-bordered">
		<thead>
			<tr>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant Price')}}</label>
				</td>
				<!-- <td class="text-center">
					<label for="" class="control-label">{{__('SKU')}}</label>
				</td> -->
				<td class="text-center">
					<label for="" class="control-label">{{__('Quantity')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Image')}}</label>
				</td>
				
				
			</tr>
		</thead>
		<tbody>


@foreach ($combinations as $key => $combination)
	@php
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
			$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Models\Color::where('code', $item)->first()->name;

					$str .= $color_name;
					$sku .='-'.$color_name;
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
			}
		}
	@endphp
	
	@if(strlen($str) > 0)
	
			<tr>
				<td>
					<label for="" class="control-label">{{ $str }}</label>
				</td>
				<td>
					<input type="number" name="price_{{ $str }}" value="{{ $unit_price }}" min="0" step="0.01" class="form-control" required>
				</td>
			<!-- 	<td>
					<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
				</td> -->
				<td>
					<input type="number" name="qty_{{ $str }}" value="10" min="0" step="1" class="form-control" required>
				</td>
				<td>
				<img id="preview_{{$str}}" class="img-rounded" src="assets/images/upload.png" alt="your image" width="100" height="80">
				<input type="file" id="img_{{$str}}" name="img_{{$str}}[]" multiple>
				</td>
				
			</tr>
	@endif

<script>
$('#img_{{$str}}').on("change", function () 
{
	if (typeof ($('#img_{{$str}}')[0].files) != "undefined") 
	{
		var size = parseFloat($('#img_{{$str}}')[0].files[0].size / 1024).toFixed(2);
		var reader = new FileReader();
		reader.onload = function (e) 
		{
			document.getElementById("preview_{{$str}}").src = e.target.result;
		};
			reader.readAsDataURL(this.files[0]);
	} 
	else 
	{
		alert("This browser does not support HTML5.");
	}
});
</script>

@endforeach
	</tbody>
</table>
@endif


