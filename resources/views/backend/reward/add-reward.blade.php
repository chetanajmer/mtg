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

                                <h4 class="page-title">Reward Points on Total Sales</h4>

                                <ol class="breadcrumb">
                                </ol>

                            </div>

                        </div>



                        <div class="row">

                            <div class="col-sm-12">

                                    <form action="{{route('add_reward')}}" method="post" class="form-horizontal" enctype="multipart/form-data">

                                     {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="card-box">

                                                   <!--  <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Reward Points on Total Sales</b></h5> -->

                                                    <div class="form-group m-b-20">

                                                        <label>Enable/Disable     &nbsp;&nbsp;&nbsp;</label>

                                                        <input type="checkbox" name="status" id="status" class="status" data-plugin="switchery" data-color="#f05050"   data-size="small" value="on" />

                                                    </div>



                                                    <div class="form-group m-b-20">

                                                        <label>Reward Points</label>

                                                        <input type="number" name="reward_points" id="reward_points" class="form-control" required="required">

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                        <label>Amount From</label>

                                                        <input type="number" name="totalsale_from" id="totalsale_from" class="form-control" required="required" >

                                                    </div>


                                                    <div class="form-group m-b-20">

                                                        <label>Amount To</label>

                                                        <input type="number" name="totalsale_to" id="totalsale_to" class="form-control" required="required">

                                                    </div>

                                                </div>

                                            </div>





                                            

                                             </div>

                                        </div>





                                        <div class="row">

                                            <div class="col-sm-12">

                                                <hr />

                                                <div class="text-center p-20">
                                                     <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>

                                                     <!-- <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button> -->

                                                </div>

                                            </div>

                                        </div>

                                    </form>

                                </div>

                            </div>



   



                    </div> <!-- container -->

                               

    

                </div> <!-- content -->





@push('header-scripts')



<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>







@endpush







@push('custom-scripts')


<script>


    function status_change()
    {

       // var checkedValue = document.querySelector('.status:checked').value;  
       // alert(checkedValue);
       if(this.checked='on') 
        {
            $('#reward_points').attr('readonly',false);
             $('#reward_points').attr('required',true);
            $('#totalsale_from').attr('readonly',false);
            $('#totalsale_to').attr('readonly',false);
        }
        if(this.checked!='on') 
        {
            $('#reward_points').attr('readonly',true);
             
        }
        

}
         

</script>
    

@endpush

@endsection

