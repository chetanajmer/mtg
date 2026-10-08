@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif


@extends('layouts.app')



@section('content')



            <!-- ============================================================== -->

            <!-- Start right Content here -->

            <!-- ============================================================== -->                      

            <div class="content-page">

                <!-- Start content -->

                <div class="content">

                    <div class="container">



                        <!-- Page-Title -->

                        <div class="row">

                            <div class="col-sm-12">

                                <h4 class="page-title">Category Settings</h4>

                                <ol class="breadcrumb">

                                </ol>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-12">

                                    <form action="{{ route('categorysettingupdate') }}" method="post" class="form-horizontal" enctype="multipart/form-data">

                                                       {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-4">
                                                        <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section1</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category section1    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($category1->category_status=="on") checked="" @endif name="categorystatus1" data-plugin="switchery" data-color="#f05050"   data-size="small" />

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category1">
                                                            <option value="">Select Category</option>
                                                           @foreach($category as $items)
                                                             @php 
                                                              $cat=App\Models\Category::where('parentcat_id',$items->id)->get();
                                                             @endphp
                                                             <option value="{{$items->id}}" @if($category1->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                     <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category1->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category1->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />


                                                            </div>



                                                        @else



                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files1" name="image_icon1" class=""  >
                                                        <input type="hidden" name="oldimage_icon1" value="{{$category1->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors1[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category1->colors))
                                                          {
                                                             $product_color=explode(',',$category1->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category1->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Product Limit </label>
                                                        <input type="number" name="productlimit1" value="@if(!empty($category1->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">
                                                    </div>

                                                </div>  
                                            </div>

                                            <div class="col-lg-4">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section2</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category Section2    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($category2->category_status=="on") checked="" @endif name="categorystatus2" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                     <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category2">
                                                            <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}" @if($category2->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category2->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category2->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                

                                                            </div>



                                                        @else



                                                        <img id="imagepreview2" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files2" name="image_icon2" class=""  >
                                                        <input type="hidden" name="oldimage_icon2" value="{{$category2->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors2[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category2->colors))
                                                          {
                                                             $product_color=explode(',',$category2->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category2->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>

                                                   <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit2" value="@if(!empty($category2->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-lg-4">

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section3</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category Section3   &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox" @if($category3->category_status=="on") checked="" @endif  name="categorystatus3" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                     <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category3">
                                                            <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}"  @if($category3->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category3->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category3->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                 

                                                            </div>



                                                        @else



                                                        <img id="imagepreview3" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files3" name="image_icon3" class=""  >
                                                        <input type="hidden" name="oldimage_icon3" value="{{$category3->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors3[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category3->colors))
                                                          {
                                                             $product_color=explode(',',$category3->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category3->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>

                                                   <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit3" value="@if(!empty($category1->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>
                                            </div>

                                        </div>


                                        <div class="row">

                                            <div class="col-lg-4">
                                                        <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section4</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category section4    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($category4->category_status=="on") checked="" @endif name="categorystatus4" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Category</label>
                                                      
                                                       <select class="form-control select2" name="category4">
                                                            <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}"@if($category4->category_id==$items->id) selected=""@endif>{{$items->catname}}
                                                           </option>
                                                           @endforeach

                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category4->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category4->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                 

                                                            </div>



                                                        @else



                                                        <img id="imagepreview4" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files4" name="image_icon4" class=""  >
                                                        <input type="hidden" name="oldimage_icon4" value="{{$category4->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors4[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category4->colors))
                                                          {
                                                             $product_color=explode(',',$category4->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category4->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>
                                                    <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit4" value="@if(!empty($category4->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>  
                                            </div>

                                            <div class="col-lg-4">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section5</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category Section5    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($category5->category_status=="on") checked="" @endif name="categorystatus5" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                     <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category5">
                                                             <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}" @if($category5->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>
                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category5->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category5->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                

                                                            </div>



                                                        @else



                                                        <img id="imagepreview5" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files5" name="image_icon5" class=""  >
                                                        <input type="hidden" name="oldimage_icon5" value="{{$category5->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors5[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category5->colors))
                                                          {
                                                             $product_color=explode(',',$category5->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category5->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>
                                                   <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit5" value="@if(!empty($category5->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-lg-4">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section6</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category Section6   &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($category6->category_status=="on") checked="" @endif name="categorystatus6" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                     <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category6">
                                                             <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}" @if($category6->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category6->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category6->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                               

                                                            </div>



                                                        @else



                                                        <img id="imagepreview6" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files6" name="image_icon6" class=""  >
                                                        <input type="hidden" name="oldimage_icon6" value="{{$category6->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors6[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category6->colors))
                                                          {
                                                             $product_color=explode(',',$category6->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category6->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>
                                                   <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit6" value="@if(!empty($category6->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-lg-4">
                                                        <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section7</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category section7    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox"  @if($category7->category_status=="on") checked="" @endif name="categorystatus7" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                    <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category7">
                                                           <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}" @if($category7->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category7->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category7->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                

                                                            </div>



                                                        @else



                                                        <img id="imagepreview7" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files7" name="image_icon7" class=""  >
                                                        <input type="hidden" name="oldimage_icon7" value="{{$category7->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors7[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category7->colors))
                                                          {
                                                             $product_color=explode(',',$category7->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category7->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>
                                                    <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit7" value="@if(!empty($category7->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>  
                                            </div>

                                            <div class="col-lg-4">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section8</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category Section8    &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox" @if($category8->category_status=="on") checked="" @endif  name="categorystatus8" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                     <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category8">
                                                             <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}" @if($category8->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category8->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category8->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                

                                                            </div>



                                                        @else



                                                        <img id="imagepreview8" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files8" name="image_icon8" class=""  >
                                                        <input type="hidden" name="oldimage_icon8" value="{{$category8->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors8[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category8->colors))
                                                          {
                                                             $product_color=explode(',',$category8->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category8->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>
                                                   <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit8" value="@if(!empty($category8->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-lg-4">

                                               

                                                <div class="card-box">

                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Category Section9</b></h5>

                                                    <div class="form-group m-b-20">

                                                    <label>Enable/Disable Category Section9   &nbsp;&nbsp;&nbsp;</label>

                                                    <input type="checkbox" @if($category9->category_status=="on") checked="" @endif  name="categorystatus9" data-plugin="switchery" data-color="#f05050"   data-size="small"/>

                                                    </div>

                                                     <div class="form-group m-b-20">

                                                        <label>Category</label>

                                                       <select class="form-control select2" name="category9">
                                                             <option value="">Select Category</option>
                                                           @foreach($category as $items)

                                                           <option value="{{$items->id}}" @if($category9->category_id==$items->id) selected=""@endif>{{$items->catname}}</option>
                                                           @endforeach
                                                       </select>

                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Homepage Icon </label>
                                                       <div id="categoryicondiv1">
                                                        @if($category9->image_icon)
                                                          <div style="position: relative;">

                                                                <img src="{{ URL::asset('upload/category/icon/'.$category9->image_icon) }}" alt="your image" style="width:80px;height:80px;margin-bottom: 20px;" class=" img-responsive img-thumbnail" id="item-img-output" style="max-width: 110px;max-height: 138px;" />

                                                                

                                                            </div>



                                                        @else



                                                        <img id="imagepreview9" class="img-rounded" src="/assets/images/upload.png" alt="your image" style="max-width:100px; max-height:100px;margin-bottom: 20px;">



                                                    @endif

                                                </div>

                                                    <input type="file" id="files9" name="image_icon9" class=""  >
                                                        <input type="hidden" name="oldimage_icon9" value="{{$category9->image_icon}}">
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Color </label>
                                                        <select  name="colors9[]" class="selectpicker" multiple data-style="btn-white">
                                                        @php 
                                                          if(!empty($category9->colors))
                                                          {
                                                             $product_color=explode(',',$category9->colors);
                                                          }
                                                        @endphp

                                                        @if(!empty($category9->colors))
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" <?php if(in_array($val->code, $product_color)) echo 'selected'?>>{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @else
                                                          @foreach($color as $val)
                                                            <option style="background-color:{{ $val->code }} " value="{{ $val->code }}" >{{ $val->name }}
                                                            </option>   
                                                          @endforeach  
                                                        @endif
                                                                            
                                                      </select> 
                                                    </div>

                                                   <div class="form-group m-b-20">

                                                        <label>Product Limit </label>

                                                        <input type="number" name="productlimit9" value="@if(!empty($category9->product_limit)){{$category1->product_limit}}@endif" class="form-control" placeholder="10">

                                                    </div>

                                                </div>
                                            </div>

                                        </div>   


                                        <div class="row">

                                            <div class="col-sm-12">

                                             

                                                <div class="text-center p-20">

                                                     <!-- <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->

                                                     <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>

                                                    <!--  <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete</button> -->

                                                </div>

                                            </div>

                                        </div>

                                    </form>

                                </div>

                            </div>



<!-- Image Popup  -->

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

<div class="modal-dialog modal-lg" role="document">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title" id="modalLabel">Crop Image</h5>

<button type="button" class="close" data-dismiss="modal" aria-label="Close">

<span aria-hidden="true">×</span>

</button>

</div>

<div class="modal-body">

<div class="img-container">

<div class="row">

<div class="col-md-8">

<img id="image" src="https://avatars0.githubusercontent.com/u/3456749">

</div>

<div class="col-md-4">

<div class="preview"></div>

</div>

</div>

</div>

</div>

<div class="modal-footer">

<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

<button type="button" class="btn btn-primary" id="crop">Crop</button>

</div>

</div>

</div>

</div>

</div>

                     <!-- Image popup End -->      



                    </div> <!-- container -->

                               

    

                </div> <!-- content -->





@push('header-scripts')



<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">





<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>



<style type="text/css">

img {

display: block;

max-width: 100%;

}

.preview {

overflow: hidden;

width: 160px; 

height: 160px;

margin: 10px;

border: 1px solid red;

}

.modal-lg{

max-width: 1000px !important;

}

</style>



@endpush







@push('custom-scripts')



<script>

var $modal = $('#modal');

var image = document.getElementById('image');

var cropper;

$("body").on("change", ".image", function(e){

var files = e.target.files;



if(this.files[0].size>350000)

{

    alert("Image Size Cannot Be More Then 350 Kb !");

    

}



else

{



    var done = function (url) {

    image.src = url;

    $modal.modal('show');

    };

    var reader;

    var file;

    var url;

}



if (files && files.length > 0) {



   





file = files[0];

if (URL) {

done(URL.createObjectURL(file));

} else if (FileReader) {

reader = new FileReader();

reader.onload = function (e) {

done(reader.result);

};

reader.readAsDataURL(file);

}

}

});

$modal.on('shown.bs.modal', function () {

cropper = new Cropper(image, {

aspectRatio: 1,

viewMode: 3,

preview: '.preview'

});

}).on('hidden.bs.modal', function () {

cropper.destroy();

cropper = null;

});

$("#crop").click(function(){

canvas = cropper.getCroppedCanvas({

width: 250,

height: 250,

});



//var base64data=0;

canvas.toBlob(function(blob) 

{

        url = URL.createObjectURL(blob);

        var reader = new FileReader();

        reader.readAsDataURL(blob); 



        reader.onloadend = function() {





        var base64data = reader.result; 

        document.getElementById("imagepreview").src = base64data;

        document.getElementById("cropped").value = base64data;

        $modal.modal('hide');

        //var mylength=base64data.[]size;

        //var mS_totalBytes = base64data.files.size;

        alert("Crop image successfully uploaded"+ mS_totalBytes);



        }





});





            $("#save1").click(function(){







            alert(base64data);

            $.ajax({

            type: "POST",

            dataType: "json",

            url: "addcategory",

            data: {'_token': $('meta[name="_token"]').attr('content'), 'image': base64data},

            success: function(data){

            console.log(data);

            $modal.modal('hide');

            alert("Crop image successfully uploaded");

            }

            });



        });    





})

</script>





<script>

  // $("body").on("change", ".image", function(e){

    $("#files1").on("change", function () {

            if (typeof ($("#files1")[0].files) != "undefined") 

            {

                var size = parseFloat($("#files1")[0].files[0].size / 1024).toFixed(2);

                


                    var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview1").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);


            } 

            else 

            {

                alert("This browser does not support HTML5.");

            }

        });


    $("#files2").on("change", function () {

            if (typeof ($("#files2")[0].files) != "undefined") 

            {

                var size = parseFloat($("#files2")[0].files[0].size / 1024).toFixed(2);

                 var reader = new FileReader();

                    reader.onload = function (e) {

                    document.getElementById("imagepreview2").src = e.target.result;

                    };

                    reader.readAsDataURL(this.files[0]);

            } 

            else 

            {

                alert("This browser does not support HTML5.");

            }

        });


</script>        

@endpush

@endsection

