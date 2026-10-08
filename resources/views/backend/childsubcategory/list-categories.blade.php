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

                                    <a href="{{url('add-childsubcategories')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Child Subcategory</button></a>

                                  

                                </div>



                                <h4 class="page-title">All Child Subcategories</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                        	<div class="col-lg-12">

                        		<div class="card-box">

                        	        



                        			<div class="table-responsive">

                                        <table class="table table-actions-bar">

                                            <thead>

                                                <tr>
                                                    <th>Image</th>

                                                    <th>Name</th>

                                                    <th>Ranking</th>

                                                    <th>Status</th>

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>



                                            <tbody>


                                                @if(count($items_array)>0)
                                                @foreach($items_array as $items)

                                                <tr>

                                                    <td>

                                                        @if(!empty($items->image1))
                                                        <img  src="{{ URL::asset('upload/childsubcategory/'.$items->image1) }}" class=" img-rounded" style="width:80px;"> 
                                                        @else
                                                        Image Not  Uploaded 
                                                        @endif
                                                    </td>

                                                     <td>{{$items->catname}}</td>

                                                   

                                                    
                                                    <td>{{$items->ranking}}</td>
                                                   

                                                    <td>

                                                       <input type="checkbox" id="active" class="active" name="active"  value="{{$items->slug}}" @if($items->is_active=="online") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>

                                                    </td>

                                                     <td>

                                                        <a href="{{url('edit-childsubcategories/'.$items->slug)}}"  class="table-action-btn"><i class="md md-edit"></i></a>

                                                        <a href="{{url('deletesubcat/'.$items->id)}}" class="table-action-btn"><i class="md md-close"></i></a>

                                                    </td>

                                                </tr>

                                                @endforeach

                                                @else
                                                    <tr>
                                                        <td>No Record Found!</td>
                                                    </tr>
                                                @endif

                                            </tbody>

                                        </table>
                                        
                                        {{$items_array->links('pagination::bootstrap-4')}}
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

                url:"{{ route('childsubcatactive') }}",
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