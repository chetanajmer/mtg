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

                                <h4 class="page-title">Rewards</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">

                               @foreach($reward_status as $items)
                                <div class="card-box">

                                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Reward Points on Total Sale</b></h5>
                                                    
                                    <div class="form-group m-b-20">

                                        <label>Enable/Disable     &nbsp;&nbsp;&nbsp;</label>

                                     
                                        <input type="checkbox" id="totalsale_status" class="totalsale_status" name="totalsale_status"  value="{{$items->totalsale_status}}" @if($items->totalsale_status=="on") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>



                                    </div>
                                </div>

                            </div>

                                

                           
                            <div class="col-lg-6">

                                <div class="card-box">

                                <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Reward Points on Products</b></h5>
                                                    
                                    <div class="form-group m-b-20">

                                        <label>Enable/Disable     &nbsp;&nbsp;&nbsp;</label>

                                     
                                        <input type="checkbox" id="product_status" class="product_status" name="product_status"  value="{{$items->product_status}}" @if($items->product_status=="on") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>



                                    </div>
                                </div>

                            </div>
                             @endforeach

                        	



                            

                        </div>


                        <div class="row">

                            <div class="col-sm-12">

                                <div class="btn-group pull-right m-t-15">

                                    <a href="{{url('addreward')}}"> <button type="button" class="btn btn-info waves-effect waves-light">Add New Reward</button></a>

                                  

                                </div>



                                <h4 class="page-title">Total Sale Rewards</h4>

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

                                                    <th>Reward Points</th>

                                                    <th>Amount</th>

                                                    <th>Active</th>

                                                    <th style="min-width: 80px;">Action</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                 @foreach($reward as $items)

                                                  <tr>

                                                    <td>{{$items->reward_points}} %</td>

                                                    <td>{{$items->totalsale_from}}  
                                                        @if(!empty($items->totalsale_to))
                                                        - {{$items->totalsale_to}}
                                                        @endif
                                                    </td>

                                                    <td>
                                                      <input type="checkbox" id="status" class="status" name="status"  value="{{$items->id}}" @if($items->status=="on") checked="" @endif data-plugin="switchery" data-color="#81C868" data-size="small"/>
                                                    </td>

                                                    <td>
                                                        <a href="{{url('edit-reward/'.$items->id)}}"  class="table-action-btn">
                                                            <i class="md md-edit"></i></a>

                                                        <a href="{{url('deletereward/'.$items->id)}}" class="table-action-btn">
                                                            <i class="md md-close"></i></a>
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


    $(document).on('change', '.product_status', function() {

        if(this.checked) 
        {
          var status="on";  
        }
        else
        {
            var status="off";
        } 


         $.ajax({

                url:"{{ route('rewardproductstatus') }}",
                    method:"POST",
                    data: {"_token": "{{ csrf_token() }}","status": status},
                    success:function (data) 
                    {
                        $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
                    } 
                });
                    
        });

</script>

<script>


    $(document).on('change', '.totalsale_status', function() {

        if(this.checked) 
        {
          var status="on";  
        }
        else
        {
            var status="off";
        } 


         $.ajax({

                url:"{{ route('rewardtotalsalestatus') }}",
                    method:"POST",
                    data: {"_token": "{{ csrf_token() }}","status": status},
                    success:function (data) 
                    {
                        $.Notification.notify('success','top right', 'Success ', "Updated Successfully !");
                    } 
                });
                    
        });

</script>
<script>


    $(document).on('change', '.status', function() {

        var my_id = $(this).val(); 
          // alert(my_id);
        if(this.checked) 
        {
          var status="on";  
        }
        else
        {
            var status="off";
        } 


         $.ajax({

                url:"{{ route('totalsalestatus') }}",
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