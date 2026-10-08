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

                                <div class="btn-group pull-right m-t-15">

                                    <a href="{{url('add-categories')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Category</button></a>

                                  

                                </div>



                                <h4 class="page-title">All Categories</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                        	<div class="col-lg-12">

                        		<div class="card-box">

                                <form method="POST" action="{{url('listcategory')}}" >  {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-lg-8"></div>
                                  <div class="col-lg-3">
                                    <p style="float: right;margin: 0;">
                                      <input  type="search" id="post-search-input" name="search" value="" style="float: left;margin: 0 4px 0 0;border:1px solid #E3E3E3" class="form-control" placeholder="Search Category">
                                    </p>
                                  </div>
                                  <div class="col-lg-1"> 
                                    <input type="submit" id="search-submit" class="btn btn-primary" value="Search">
                                  </div>
                                </div></form> <br>

                        			<div class="table-responsive">

                                        <table class="table table-actions-bar">

                                            <thead>

                                                <tr>

                                                    <th>Category Image</th>

                                                    <th>Name / Product Count</th>

                                                    <th>Featured</th>
                                                     <th>Active</th>
                                                     
                                                    <th>Ranking</th>

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>



                                            <tbody> 

                                                @if(count($items_array)>0)

                                                @foreach($items_array as $items)
                                                  @php
                                                    $product_count=App\Models\Product::where('category',$items->id)->get();
                                                  @endphp
                                                <tr>
                                                    <td>
                                                        @if(!empty($items->image1))
                                                        <img  src="{{ URL::asset('upload/category/'.$items->image1) }}" class=" img-rounded" style="width:80px;"> 
                                                        @else
                                                        No Image Uploaded
                                                        @endif
                                                    </td>

                                                    
                                                    <td>{{$items->catname}} 
                                                    	/&nbsp;<span class="label label-pink">{{count($product_count)}}</span>
                                                    </td>

                                                    <td>
                                                      <input type="checkbox" id="featured" class="featured" name="featured"  value="{{$items->slug}}" @if($items->featured=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>    
                                                    </td>

                                                    <td>
                                                       <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                                                    </td>
                                                    
                                                    <td>{{$items->ranking}}</td>

                                                     <td>

                                                        <a href="{{url('edit-categories/'.$items->slug)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                                                        <a href="{{url('deletecategory/'.$items->id)}}" class="table-action-btn"><i class="md md-close"></i></a>

                                                    </td>

                                                </tr>

                                                @endforeach

                                                 @else

                                                <tr>
                                                    <td> No Catgory Found!</td>
                                                </tr>

                                        @endif

                                            </tbody>

                                        </table>
                                       
                                      
                                    </div>

                        		</div>

                                

                            </div> <!-- end col -->



                            

                        </div>



                        

                        

                        



                    </div> <!-- container -->

                               

                </div> <!-- content -->



                



            </div>



            

            

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->


@push('custom-scripts')

<script>


    $(document).on('change', '.featured', function() {

          var my_id = $(this).val(); 
          //alert(my_id);
        if(this.checked) 
        {
          var status="online";  
          //alert("Checked");
        }
        else
        {
            var status="offline";
            ///alert("UnChecked");
        } 


         $.ajax({

                url:"{{ route('catfeatured') }}",
                    method:"POST",
                    data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
                    success:function (data) 
                    {
                        $.Notification.notify('success','top right', 'Success ', "Featured Updated Successfully !");
                    } 
                });
                    
        });

</script>

<script>


    $(document).on('change', '.active', function() {

          var my_id = $(this).val(); 
          //alert(my_id);
        if(this.checked) 
        {
          var status="online";  
          //alert("Checked");
        }
        else
        {
            var status="offline";
            ///alert("UnChecked");
        } 


         $.ajax({

                url:"{{ route('catactive') }}",
                    method:"POST",
                    data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},
                    success:function (data) 
                    {
                        $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
                    } 
                });
                    
        });

</script>

@endpush


@endsection