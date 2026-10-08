
      <div class="row">
        <div class="col-lg-12">
          <div class="card-box">
            
           <form method="POST" action="{{url('listproduct')}}" >  
              @if(isset($_GET['page']))
                <input type="hidden" name="pageid" id="pageid" value="{{$_GET['page']}}">
              @else
                 <input type="hidden" name="pageid" id="pageid" value="1">
              @endif
            {{ csrf_field() }}
            
            <div class="row">
              <div class="col-lg-3">
                <select class="form-control select2" name="brand" id="brand">
                  <option value="">Select Brand</option>
                  @foreach($brand as $item)
                    <option value="{{$item->id}}" @if($search_brand==$item->id) selected='' @endif>{{$item->brandname}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-3">
                <select class="form-control select2" name="category" id="categoryonproduct_select">
                  <option value="">Select Category</option>
                  @foreach($category as $item)
                    <option value="{{$item->id}}" @if($search_category==$item->id) selected='' @endif>{{$item->catname}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-3">
                <select name="subcategory" class="form-control select2" id="subcategoryonproduct_select">

                   <option value="">Select</option>
                   @foreach($subcategory as $item)
                    <option value="{{$item->id}}" @if($search_subcategory==$item->id) selected='' @endif>{{$item->catname}}</option>
                  @endforeach
               </select>
              </div>
              <div class="col-lg-2">
                <p style="float: right;margin: 0;">
                  <input  type="search" id="post-search-input" name="search" value="" style="float: left;margin: 0 4px 0 0;border:1px solid #E3E3E3" class="form-control" placeholder="Search Product">
                </p>
              </div>
              <div class="col-lg-1"> 
                <input type="submit" id="search-submit" class="btn btn-primary" value="Search">
              </div>
            </div>
           
          </form> <br>


            <div class="table-responsive">
              <table class="table table-actions-bar">
              <thead>
                <tr>
                  <th style="text-align: center;">Image</th>
                  <th style="text-align: center;">Category / Subcategory / Name</th>
                  <!-- <th style="text-align: center;">Category</th>
                  <th style="text-align: center;">Status</th> -->
                  <th style="text-align: center;">Active</th>
                  <th style="text-align: center;">New Arrival</th>
                  <th style="text-align: center;">Sale</th>
                  <th style="text-align: center;">Most Selling</th>
                   <th style="text-align: center;">Featured</th>
                  <th style="min-width: 80px;text-align: center;">Action</th>
                  <!-- <th style="min-width: 80px;text-align: center;">View Product</th> -->
                </tr>
              </thead>
              <tbody>
                @if(count($items_array)>0)
                @foreach($items_array as $items)
                  <tr>
                  @if(!empty($items->thumbnail))
                  <td style="text-align:center;"><img  src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" class=" img-rounded" style="width:50px;height: 50px"> </td>
                  @else
                  <td>No Image Available</td>
                  @endif
                  <td style="width:300px">
                    @if(!empty($items->category))
                      @php
                        $cat_data=\App\Models\Category::where('id',$items->category)->first();
                      @endphp

                      @if(!empty($cat_data))
                      <span class="label label-pink">{{$cat_data->catname}}</span> 
                      @endif
                    @endif 
                     
                   
                    
                     @if(!empty($items->subcategory))
                      @php
                        $subcat_data=\App\Models\Subcategory::where('id',$items->subcategory)->first();
                      @endphp

                      @if(!empty($subcat_data))
                     <span class="label label-primary">{{$subcat_data->catname}}</span> 
                      @endif
                    @endif <br> <b>{{$items->name}}</b>
                  </td>
                
                  <!--   <td style="text-align:center;">@if($items->is_active=="online")  <span class="label label-success">Active</span> @else<span class="label label-danger">In-Active</span> @endif</td> -->
                  <td style="text-align:center;">
                    <!-- <input type="checkbox" checked data-plugin="switchery" data-color="#81C868" data-size="small"/> -->
                     <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                    <input type="checkbox" id="newarrival" class="newarrival" name="newarrival"  value="{{$items->slug}}" @if($items->newarrival=="online") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                    <input type="checkbox" id="sale" class="sale" name="sale"  value="{{$items->slug}}" @if($items->sale=="online") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                    <input type="checkbox" id="most_selling" class="most_selling" name="most_selling"  value="{{$items->slug}}" @if($items->most_selling=="online") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>
                  </td>

                   <td style="text-align:center;">
                    <input type="checkbox" id="featured" class="featured" name="featured"  value="{{$items->slug}}" @if($items->featured=="online") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>
                  </td>

                  <td style="text-align:center;">
                  <a href="{{url('edit-products/'.$items->slug.'/'.$pageid)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                <!--   <a href="{{url('deletemyproduct/'.$items->slug.'/'.$pageid)}}" class="table-action-btn"><i class="md md-close" ></i></a> -->
                  <a  class="table-action-btn"><i class="md md-close" onclick="deletemyproduct('{{$items->slug}}')"></i></a>
                  </td>
                  <!--   <td style="text-align:center;">
                  <a href="#"  target="_blank">View Products</a>
                  </td> -->
                  </tr>
                  @endforeach
                  @else
                  <tr><td>No Products Found!</td></tr>
                  @endif
                  </tbody>
                  </table>
                
                  <!-- {!! $items_array->appends(Request::except('page'))->render() !!} -->
                  {{$items_array->links('pagination::bootstrap-4')}}
                </div>
            </div>
          </div> <!-- end col -->
        </div>
