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

                                    <button type="button" data-toggle="modal" data-target="#con-close-modal" class="btn btn-info waves-effect waves-light">Add New Shipping</button>

                                  

                                </div>



                                <h4 class="page-title">All Shippings</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                        	<div class="col-lg-12">

                        		<div class="card-box">

                        	        <div class="row m-t-10 m-b-10">

                                        <div class="col-sm-10 col-lg-10">

                                           <!-- <form role="form">

                                                <div class="form-group contact-search m-b-30">

                                                    <input type="text" id="search" class="form-control" placeholder="Search...">

                                                    <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>

                                                </div>

                                            </form> -->

                                        </div>



                                        <!-- <div class="col-sm-2 col-lg-2">

                                            <div class="h5 m-0">

                                               <a href="{{url('show-slider')}}"> <button type="button" class="btn btn-info waves-effect waves-light"></button></a>

                                            </div>

                                        </div> -->

                                    </div>





                        			<div class="table-responsive">

                                        <table class="table table-actions-bar">

                                            <thead>

                                                <tr>

                                                    <th>Cityname</th>

                                                    <th>shippingcost</th>

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>



                                            <tbody>



                                                @foreach($items_array as $items)

                                                <tr>

                                                   

                                                    <td>{{$items->cityname}}</td>

                                                    <td>{{$items->shippingcost}}</td>

                                                     <td>

                                                        <button type="button" data-toggle="modal" data-target="#edit-close-modal"   class=" editbutton btn btn-success waves-effect waves-light" value="{{$items->id}}" ><i class="fa fa-edit"></i>

                                                        </button>



                                                        <button type="button"   class="deleteshipping btn btn-danger waves-effect waves-light" value="{{$items->id}}" ><i class="fa fa-remove"></i>

                                                        </button>

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



            

            <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

                                        <div class="modal-dialog"> 

                                            <div class="modal-content"> 

                                                <div class="modal-header"> 

                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 

                                                    <h4 class="modal-title">Add Shipping</h4> 

                                                </div> 

                                                <div class="modal-body"> 

                                                    <div class="row"> 

                                                        <div class="col-md-6"> 

                                                            <div class="form-group"> 

                                                                <label for="field-1"  class="control-label">Cityname</label> 

                                                                <input type="text" id="city" class="form-control" id="field-1" placeholder="Sharjah"> 

                                                            </div> 

                                                        </div> 

                                                        <div class="col-md-6"> 

                                                            <div class="form-group"> 

                                                                <label for="field-2"  class="control-label">shippingcost</label> 

                                                                <input type="text" id="cost"class="form-control" id="field-2" placeholder="100"> 

                                                            </div> 

                                                        </div> 

                                                    </div> 

                                                 

                                                </div> 

                                                <div class="modal-footer"> 

                                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> 

                                                    <button type="button" id="saveshipping" class="btn btn-info waves-effect waves-light">Save changes</button> 

                                                </div> 

                                            </div> 

                                        </div>

            </div><!-- /.modal -->







            <!-- Edit Modal -->



            <div id="edit-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

                                        <div class="modal-dialog"> 

                                            <div class="modal-content"> 

                                                <div class="modal-header"> 

                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 

                                                    <h4 class="modal-title">Edit Shipping</h4> 

                                                </div> 

                                                <div class="modal-body"> 

                                                    <div class="row"> 

                                                        <input type="hidden" name="editid" id="editid">

                                                        <div class="col-md-6"> 

                                                            <div class="form-group"> 

                                                                <label for="field-1"  class="control-label">Cityname</label> 

                                                                <input type="text" id="updatecity" class="form-control" id="field-1" placeholder="Sharjah"> 

                                                            </div> 

                                                        </div> 

                                                        <div class="col-md-6"> 

                                                            <div class="form-group"> 

                                                                <label for="field-2"  class="control-label">shippingcost</label> 

                                                                <input type="text" id="updatecost"class="form-control" id="field-2" placeholder="100"> 

                                                            </div> 

                                                        </div> 

                                                    </div> 

                                                 

                                                </div> 

                                                <div class="modal-footer"> 

                                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> 

                                                    <button type="button" id="updateshipping" class="updateshipping btn btn-info waves-effect waves-light">Update changes</button> 

                                                </div> 

                                            </div> 

                                        </div>

            </div><!-- /.modal -->  

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->



@push('custom-scripts')





<script>

    





$('#saveshipping').on('click',function(e) {



        

        var cityname=$("#city").val();

        var shippingcost=$("#cost").val();



        //alert(cityname+shippingcost);

        $.ajax({



            url:"{{ route('addshipping') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","cityname": cityname,"shippingcost":shippingcost},

                success:function (data) 

                {

                console.log(data);



                swal("success",'Shipping Added Successfully !');

                $('#con-close-modal').modal('hide');

                location.reload();



                }



            })





});



$(document).on('click', '.editbutton', function() {

//$('.').on('click',function(e) {



       //var cityname=

        var id=$(this).val();

       //alert(id);



        $.ajax({



            url:"{{ route('findshipping') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

                success:function (data) 

                {

                console.log(data);



                //swal("success",'Shipping Added Successfully !');

                $("#updatecity").val(data['cityname']);

                $("#updatecost").val(data['shippingcost']);

                $('#editid').val(data['id']);

               

                }



            })







});







$(document).on('click', '.updateshipping', function() {

//$('.').on('click',function(e) {



    var cityname =$("#updatecity").val();

    var shippingcost=$("#updatecost").val();

    var id=$('#editid').val();

                

       //var id=$(this).val();

       //alert(id+cityname+shippingcost);



        $.ajax({



            url:"{{ route('updateshipping') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id,"cityname": cityname,"shippingcost":shippingcost},

                success:function (data) 

                {

                console.log(data);



                swal("success",'Shipping Added Successfully !');

               

                $('#edit-close-modal').modal('hide');

                location.reload();

                }



            })







});







$(document).on('click', '.deleteshipping', function() {

//$('.').on('click',function(e) {



/*    var cityname =$("#updatecity").val();

    var shippingcost=$("#updatecost").val();

    var id=$('#editid').val();

                */

       var id=$(this).val();

     //  alert(id);



        $.ajax({



            url:"{{ route('deleteshipping') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

                success:function (data) 

                {

                console.log(data);



                swal("success",'Deleted Successfully !');

               location.reload();

                

                }



            })







});



</script>

@endpush

@endsection