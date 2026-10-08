
@if(count($combinations[0]) > 0)
	<table class="table table-bordered" style="width: 100%">
		<thead>
			<tr>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant Price')}}</label>
				</td>
				<!-- <td class="text-center">
					<label for="" class="control-label">{{__('Part No')}}</label>
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
			<td style="width: 20%">
				<label for="" class="control-label">{{ $str }}</label>
			</td>
			<td style="width: 20%">
				<input type="number" name="price_{{ $str }}"  id="price" value="@php
                    if ($product->price == $unit_price) {
						if(isset(json_decode($product->variations)->$str->price)){
	                        echo json_decode($product->variations)->$str->price;
	                    }
	                    else{
	                        echo $unit_price;
	                    }
                    }
					else{
						echo $unit_price;
					}
                @endphp" min="0" step="0.01" class="form-control" required>
			</td>
			
			<td style="width: 20%">
				<input type="number" name="qty_{{ $str }}" value="@php
                    if(isset(json_decode($product->variations)->$str->qty)){
                        echo json_decode($product->variations)->$str->qty;
                    }
                    else{
                        echo '10';
                    }
                @endphp" min="0" step="1" class="form-control" required id="qty">
			</td>
			<td style="width: 70%">
			<div class="row">
        <div class="col-xs-12">
					@php 
					if(isset(json_decode($product->variations)->$str->img))
					{
            $img=json_decode($product->variations)->$str->img; @endphp
            <div class="row">
	            @php  
	              if(!empty($img))
	              {
	              	$j=0;
		            	foreach($img as $items)
		              {
		              	$image=$items; 
		          @endphp
		            <div class="column">
								  <img src="{{ URL::asset('upload/product/'.$image) }}" width="100" height="80" id="preview_{{$str}}">
								   <center> 
										<a  style="background-color:white; position: absolute; top:0px; left:10px; border:1px solid #ddd; padding:2px; cursor:pointer;color:grey; border-radius:50%; width:25px;" onclick="delete_variation_image('{{$str}},{{$image}}')"  >X</a> 
										</center>
								  <input type="hidden"  name="oldimg_{{$str}}{{$j}}" value="{{$image}}" >
								  <input type="hidden"  name="old_img_{{$str}}[]" value="{{$image}}" >
								   <input type="file"  name="img_{{$str}}[]" class="" >
								</div>
							@php $j++;
						     } 
						    }
						    else
						    { @endphp
						    	<img id="preview1_{{$str}}"  src="{{asset('assets/images/upload.png')}}" alt="your image" width="100" height="80">
						  @php  }
						  @endphp
						  <div class="column">
							  <img id="preview2_{{$str}}"  src="{{asset('assets/images/upload.png')}}" alt="your image" width="100" height="80">
							  <input type="file" id="newimg_{{$str}}" name="newimg_{{$str}}[]" class="" multiple >
							</div>
            </div>
              
		 @php }  
					else
					 { @endphp
					 	 <div class="row">
					 	  <div class="column">
					 	  	<img id="preview11_{{$str}}"  src="{{asset('assets/images/upload.png')}}" alt="your image" width="100" height="80">
					 		</div>
					 		</div>
					 	 <input type="file" id="newimg_{{$str}}" name="newimg_{{$str}}[]" class="" multiple >

				@php	 }
				@endphp
      </div>
    </div>
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

<style>
	
.column {
  float: left;
  width: 33.33%;
  padding: 5px;
  position: relative;
}


.row::after {
  content: "";
  clear: both;
  display: table;
} 
</style>

@push('custom-scripts')

@endpush
