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

                                    <button type="button" data-toggle="modal" data-target="#con-close-modal" class="btn btn-info waves-effect waves-light">Add Exhibition Event</button>

                                  

                                </div>



                                <h4 class="page-title">Exhibition Events</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                        	<div class="col-lg-12">

                        		<div class="card-box">

                        	        <div class="row m-t-10 m-b-10">

                                        <div class="col-sm-10 col-lg-10">

                                        </div>

                                    </div>



                        			<div class="table-responsive">

                                        <table class="table table-actions-bar">

                                            <thead>

                                                <tr>

                                                    <th>Event Name</th>

                                                    <th>Start Date</th>

                                                    <th>End Date</th>

                                                    <th>Image</th>

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>



                                            <tbody>



                                                @foreach($items_array as $items)

                                                <tr>

                                                   

                                                   

                                                    <td>{{$items->name}}</td>

                                                    <td>{{$items->start_date ?? '-'}}</td>

                                                    <td>{{$items->end_date ?? '-'}}</td>

                                                    <td>
                                                        @if($items->image)
                                                            <img src="{{asset('uploads/exhibition_events/'.$items->image)}}" width="50" height="50" alt="">
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

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

                                                    <h4 class="modal-title">Add Exhibition Event</h4> 

                                                </div> 

                                                <div class="modal-body"> 

                                                    <div class="row"> 

                                                        <div class="col-md-12"> 

                                                            <div class="form-group">

                                                                <label for="field-1"  class="control-label">Event Name</label>

                                                                <input type="text" id="name" class="form-control" id="field-1" placeholder="e.g : Exhibition 2024">

                                                            </div>

                                                        </div>

                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="field-3" class="control-label">Start Date</label>
                                                                <input type="date" id="start_date" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="field-4" class="control-label">End Date</label>
                                                                <input type="date" id="end_date" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="field-2"  class="control-label">Image</label>
                                                                <input type="file" id="image" class="form-control" accept="image/*">
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

                                                    <h4 class="modal-title">Edit Exhibition Event</h4> 

                                                </div> 

                                                <div class="modal-body"> 

                                                    <div class="row"> 

                                                        <input type="hidden" name="editid" id="editid">

                                                        <div class="col-md-12"> 

                                                            <div class="form-group">

                                                                <label for="field-1"  class="control-label">Event Name</label>

                                                                <input type="text" id="updatename" class="form-control" id="field-1" placeholder="">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="field-5" class="control-label">Start Date</label>
                                                                <input type="date" id="updatestart_date" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="field-6" class="control-label">End Date</label>
                                                                <input type="date" id="updateend_date" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="field-2"  class="control-label">Image</label>
                                                                <input type="file" id="updateimage" class="form-control" accept="image/*">
                                                                <input type="hidden" id="existingimage">
                                                                <div id="imagepreview" style="margin-top: 10px;"></div>
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

        var name=$("#name").val();
        var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();
        var image=$("#image")[0].files[0];

        var formData = new FormData();
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("name", name);
        formData.append("start_date", start_date);
        formData.append("end_date", end_date);
        if(image){
            formData.append("image", image);
        }

        $.ajax({

            url:"{{ route('addexhibitionevent') }}",
                method:"POST",
                data: formData,
                processData: false,
                contentType: false,

                success:function (data)

                {

                console.log(data);

                swal("success",'Added Successfully !');

                $('#con-close-modal').modal('hide');

                location.reload();

                }

            })

});



$(document).on('click', '.editbutton', function() {

        var id=$(this).val();

        $.ajax({

            url:"{{ route('findexhibitionevent') }}",
                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

                success:function (data)

                {

                console.log(data);

                $("#updatename").val(data.name);
                $("#updatestart_date").val(data.start_date);
                $("#updateend_date").val(data.end_date);
                $('#editid').val(data.id);
                $('#existingimage').val(data.image);

                if(data.image && data.image !== null && data.image !== ''){
                    $('#imagepreview').html('<img src="{{asset('uploads/exhibition_events/')}}/' + data.image + '" width="100" height="100" alt="">');
                } else {
                    $('#imagepreview').html('');
                }

                }

            })

});



$(document).on('click', '.updateshipping', function() {

    var name =$("#updatename").val();
    var start_date=$("#updatestart_date").val();
    var end_date=$("#updateend_date").val();

    var id=$('#editid').val();
    var image=$("#updateimage")[0].files[0];

    var formData = new FormData();
    formData.append("_token", "{{ csrf_token() }}");
    formData.append("id", id);
    formData.append("name", name);
    formData.append("start_date", start_date);
    formData.append("end_date", end_date);
    if(image){
        formData.append("image", image);
    }

        $.ajax({

            url:"{{ route('updateexhibitionevent') }}",
                method:"POST",
                data: formData,
                processData: false,
                contentType: false,

                success:function (data)

                {

                console.log(data);

                swal("success",'Updated Successfully !');
               
                $('#edit-close-modal').modal('hide');

                location.reload();
                }

            })

});



$(document).on('click', '.deleteshipping', function() {

       var id=$(this).val();

        $.ajax({

            url:"{{ route('deleteexhibitionevent') }}",
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
