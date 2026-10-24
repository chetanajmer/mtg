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

                                    <button type="button" data-toggle="modal" data-target="#add-close-modal" class="btn btn-info waves-effect waves-light">Add Tier Pricing</button>

                                </div>

                                <h4 class="page-title">Tier Pricing</h4>

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

                                                    <th>Min Qty</th>

                                                    <th>Max Qty</th>

                                                    <th>Percentage</th>

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @if(!empty($tierPricing))
                                                    @foreach($tierPricing as $tier)
                                                    <tr>

                                                        <td>{{$tier->min_qty}}</td>

                                                        <td>{{$tier->max_qty ?? 'Unlimited'}}</td>

                                                        <td>{{$tier->percentage}}%</td>

                                                        <td>

                                                            <button type="button" data-toggle="modal" data-target="#edit-close-modal" class="editbutton btn btn-success waves-effect waves-light" value="{{$tier->id}}"><i class="fa fa-edit"></i>

                                                            </button>

                                                            <button type="button" class="deletetier btn btn-danger waves-effect waves-light" value="{{$tier->id}}"><i class="fa fa-remove"></i>

                                                            </button>

                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" style="text-align: center;">No tier pricing found. Click "Add Tier Pricing" to add.</td>
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

            <!-- Add Modal -->

            <div id="add-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                                                    <h4 class="modal-title">Add Tier Pricing</h4>

                                                </div>

                                                <div class="modal-body">

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label for="min_qty" class="control-label">Min Qty</label>

                                                                <input type="number" id="min_qty" class="form-control" placeholder="Min Qty" min="1">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label for="max_qty" class="control-label">Max Qty (Optional)</label>

                                                                <input type="number" id="max_qty" class="form-control" placeholder="Max Qty (Optional)" min="1">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label for="percentage" class="control-label">Percentage</label>

                                                                <input type="number" id="percentage" class="form-control" placeholder="Percentage" step="0.01" min="0">

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>

                                                    <button type="button" id="savetier" class="btn btn-info waves-effect waves-light">Save changes</button>

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

                                                    <h4 class="modal-title">Edit Tier Pricing</h4>

                                                </div>

                                                <div class="modal-body">

                                                    <div class="row">

                                                        <input type="hidden" name="editid" id="editid">

                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label for="min_qty" class="control-label">Min Qty</label>

                                                                <input type="number" id="updatemin_qty" class="form-control" placeholder="Min Qty" min="1">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label for="max_qty" class="control-label">Max Qty (Optional)</label>

                                                                <input type="number" id="updatemax_qty" class="form-control" placeholder="Max Qty (Optional)" min="1">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12">

                                                            <div class="form-group">

                                                                <label for="percentage" class="control-label">Percentage</label>

                                                                <input type="number" id="updatepercentage" class="form-control" placeholder="Percentage" step="0.01" min="0">

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>

                                                    <button type="button" id="updatetier" class="updatetier btn btn-info waves-effect waves-light">Update changes</button>

                                                </div>

                                            </div>

                                        </div>

            </div><!-- /.modal -->



            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->



@push('custom-scripts')



<script>

$('#savetier').on('click',function(e) {

        var min_qty=$("#min_qty").val();

        var max_qty=$("#max_qty").val();

        var percentage=$("#percentage").val();



        $.ajax({

            url:"{{ route('add_tier_pricing') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","min_qty":min_qty,"max_qty":max_qty,"percentage":percentage},

                success:function (data)

                {

                console.log(data);

                $.Notification.notify('success','top right', 'Success ', 'Added Successfully !');

                $('#add-close-modal').modal('hide');

                location.reload();

                }

            })

});



$(document).on('click', '.editbutton', function() {

        var id=$(this).val();

        $.ajax({

            url:"{{ route('find_tier_pricing') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

                success:function (data)

                {

                console.log(data);

                $("#updatemin_qty").val(data.min_qty);

                $('#editid').val(data.id);

                $('#updatemax_qty').val(data.max_qty);

                $('#updatepercentage').val(data.percentage);

                }

            })

});



$(document).on('click', '.updatetier', function() {

    var min_qty =$("#updatemin_qty").val();

    var max_qty=$("#updatemax_qty").val();

    var percentage=$("#updatepercentage").val();

    var id=$('#editid').val();



        $.ajax({

            url:"{{ route('update_tier_pricing') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id,"min_qty":min_qty,"max_qty":max_qty,"percentage":percentage},

                success:function (data)

                {

                console.log(data);

                $.Notification.notify('success','top right', 'Success ', 'Updated Successfully !');

                $('#edit-close-modal').modal('hide');

                location.reload();

                }

            })

});



$(document).on('click', '.deletetier', function() {

       var id=$(this).val();

        $.ajax({

            url:"{{ route('delete_tier_pricing') }}",

                method:"POST",

                data: {"_token": "{{ csrf_token() }}","id": id},

                success:function (data)

                {

                console.log(data);

                $.Notification.notify('success','top right', 'Success ', 'Deleted Successfully !');

               location.reload();

                }

            })

});

</script>

@endpush

@endsection
