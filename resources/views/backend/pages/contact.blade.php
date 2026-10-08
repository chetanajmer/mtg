@if(Session::has('role'))

@else

<script>

window.location.href = "{{url('/admin')}}";</script>

</script>   

@endif
@extends('layouts.app')

@section('content')

	 <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        
                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                              

                                <h4 class="page-title">Contact us </h4>
                                <ol class="breadcrumb">
                                    
                                </ol>
                            </div>
                        </div>

                      



                        <div class="row">
                            <div class="col-sm-12">


                                    <form action="{{ route('updatecontactus') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                                       {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Contact us Section 1</b></h5>
                                                    
                                                    <div class="form-group m-b-20">
                                                        <label>Map Link<span class="text-danger">*</span></label>
                                                        <input type="text" name="map" class="form-control" placeholder="e.g : Home Slider " value="{{$items->map}}">
                                                    </div>


                                                    <div class="form-group m-b-20">
                                                        <label>Contact us Description<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="description" rows="1" placeholder="Please enter description">{{$items->description}}</textarea>
                                                    </div>

                                                    <div class="form-group m-b-20">
                                                        <label>Contact us Address<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="address" rows="1" placeholder="Please enter description">{{$items->address}}</textarea>
                                                    </div>

                                                 </div>

                                                


                                            </div>
                                            <div class="col-lg-6">
                                               <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Contact us Section 2</b></h5>
                                                    
                                                    <div class="form-group m-b-20">
                                                        <label>Phone Numbers<span class="text-danger">*</span></label>
                                                        <input type="text" name="phone1" class="form-control" placeholder="e.g : Home Slider " value="{{$items->phone1}}" style="margin-bottom: 10px;">

                                                        <input type="text" name="phone2" class="form-control" placeholder="e.g : Home Slider " value="{{$items->phone2}}">
                                                    </div>


                                                    <div class="form-group m-b-20">
                                                        <label>Emails<span class="text-danger">*</span></label>
                                                        <input type="text" name="email1" class="form-control" placeholder="e.g : Home Slider " value="{{$items->email1}}" style="margin-bottom: 10px;">

                                                        <input type="text" name="email2" class="form-control" placeholder="e.g : Home Slider " value="{{$items->email2}}">
                                                    </div>


                                                </div>
                                            </div>
                                             <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button>
                                                 <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>
                                        </div>



                                        

                                                </div>
                                            </div>


                                            

                                          


                                        </div>
                                        
                                       
                                    </form>
                                </div>
                            </div>
                        </div>    

@push('custom-scripts')

<script>
  // $("body").on("change", ".image", function(e){
    $("#files1").on("change", function () {
            if (typeof ($("#files1")[0].files) != "undefined") 
            {
                var size = parseFloat($("#files1")[0].files[0].size / 1024).toFixed(2);
                
                if(size>350)
                {
                    //alert("Image Size is ="+size+". Upload Image Less Then 350 Kb");

                    //swal("Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb");

                    swal("Error", "Image Size is ="+size+"Kb. Upload Image Less Then 350 Kb")
                } 

                else
                {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                    document.getElementById("imagepreview1").src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }   
                //alert(size + " KB.");
            } 
            else 
            {
                alert("This browser does not support HTML5.");
            }
        });

   
</script>

@endpush
@endsection