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

                                    <a href="{{url('add-products')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Product</button></a>

                                  

                                </div>



                                <h4 class="page-title">All Products</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                        	<div class="col-lg-12">

                        		<div class="card-box">

                        	        





                        			<div class="table-responsive">

                                        <table class="table table-actions-bar" id="datatable">

                                            <thead>

                                                <tr>

                                                    <th style="text-align: center;">Image</th>

                                                    <th style="text-align: center;">Model & Name </th>

                                                    <th style="text-align: center;">Category</th>

                                                    

                                                    <th style="text-align: center;">Status</th>

                                                    <th style="text-align: center;">Active</th>

                                                    <th style="text-align: center;">New Arrival</th>

                                                    <th style="text-align: center;">Sale</th>

                                                    <th style="min-width: 80px;text-align: center;">Action</th>

                                                </tr>

                                            </thead>



                                            <tbody>



                                                @foreach($items_array as $items)

                                                <tr>

                                                    <td style="text-align:center;"><img  src="{{ URL::asset('upload/product/thumbnail/'.$items->thumbnail) }}" class=" img-rounded" style="width:80px;"> </td>

                                                    <td style="text-align:center;">{{$items->modelno}} & {{$items->name}}</td>



                                                    <td style="text-align:center;">

                                                        

                                                        @if(!empty($items->category))



                                                        @php

                                                        $cat_data=\App\Models\Category::where('id',$items->category)->first();

                                                        if(!empty($cat_data))
                                                        {
                                                            echo $cat_data->catname;
                                                        }
                                                        else
                                                        {
                                                            echo "No Category Assigned ";
                                                        }
                                                       


                                                        @endphp



                                                       



                                                        @endif



                                                    </td>



                                                    <td style="text-align:center;">@if($items->is_active=="online")  <span class="label label-success">Active</span> @else<span class="label label-danger">In-Active</span> @endif</td>





                                                    <td style="text-align:center;">



                                                          <!-- <input type="checkbox" checked data-plugin="switchery" data-color="#81C868" data-size="small"/> -->



                                                          <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>





                                                    </td>

                                                    <td style="text-align:center;">



                                                      



                                                        <input type="checkbox" id="newarrival" class="newarrival" name="newarrival"  value="{{$items->slug}}" @if($items->newarrival=="on") checked="" @endif data-plugin="switchery" data-color="#f05050" data-size="small"/>

                                                        



                                                    </td>

                                                    <td style="text-align:center;">



                                                       <!--  <input type="checkbox" checked data-plugin="switchery" data-color="#1E539C" data-size="small"/> -->



                                                        <input type="checkbox" id="sale" class="sale" name="sale"  value="{{$items->slug}}" @if($items->sale=="on") checked="" @endif data-plugin="switchery" data-color="#1E539C" data-size="small"/>



                                                    </td>

                                                    <td style="text-align:center;">

                                                        <a href="{{url('edit-products/'.$items->slug)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                                                        <a href="{{url('deletemyproduct/'.$items->slug)}}" class="table-action-btn"><i class="md md-close"></i></a>

                                                    </td>

                                                </tr>

                                                @endforeach



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





    $(document).on('change', '.newarrival', function() {



          var my_id = $(this).val(); 

          //alert(my_id);

        if(this.checked) 

        {

          var status="on";  

          //alert("Checked");

        }

        else

        {

            var status="off";

            ///alert("UnChecked");

        } 





         $.ajax({



                url:"{{ route('newarrival') }}",

                    method:"POST",

                    data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},

                    success:function (data) 

                    {

                        $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");

                    } 

                });

                    

        });



</script>



<script>





    $(document).on('change', '.sale', function() {



          var my_id = $(this).val(); 

          //alert(my_id);

        if(this.checked) 

        {

          var status="on";  

          //alert("Checked");

        }

        else

        {

            var status="off";

            ///alert("UnChecked");

        } 





         $.ajax({



                url:"{{ route('sale') }}",

                    method:"POST",

                    data: {"_token": "{{ csrf_token() }}","my_id": my_id,"status": status},

                    success:function (data) 

                    {

                        $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");

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



                url:"{{ route('active') }}",

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