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
                              

                                <h4 class="page-title">About us </h4>
                                <ol class="breadcrumb">
                                    
                                </ol>
                            </div>
                        </div>

                      



                        <div class="row">
                            <div class="col-sm-12">


                                    <form action="{{ route('updateaboutus') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                                       {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Section 1</b></h5>
                                                    
                                                    <div class="form-group m-b-20">
                                                        <label>Heading 1<span class="text-danger">*</span></label>
                                                        <input type="text" name="h1title" class="form-control" placeholder="e.g : Home Slider " value="{{$items->h1title}}">
                                                    </div>


                                                    <div class="form-group m-b-20">
                                                        <label>Description<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="h1desc" rows="5" placeholder="Please enter description">{{$items->h1desc}}</textarea>
                                                    </div>


                                                 </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Image</b></h5>
                                                    

                                                    <div class="form-group m-b-20">
                                                            
                                                         @if($items->image1)
                                                        <img id="imagepreview1" class="img-rounded" src="{{ URL::asset('upload/pages/'.$items->image1) }}" alt="your image" style="width:120px;margin-bottom: 20px;">
                                                        @else
                                                        <img id="imagepreview1" class="img-rounded" src="/assets/images/slide1.jpg" alt="your image" style="width:120px;margin-bottom: 20px;">
                                                        @endif
                                                         <input type="file" id="files1" name="image1" class=""  >
                                                    </div>


                                                 </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            

                                            <div class="col-lg-4">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Section 2</b></h5>
                                                    
                                                    <div class="form-group m-b-20">
                                                        <label>Heading 2<span class="text-danger">*</span></label>
                                                        <input type="text" name="h2title" class="form-control" placeholder="e.g : Home Slider" value="{{$items->h2title}}">
                                                    </div>


                                                    <div class="form-group m-b-20">
                                                        <label>Description<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="h2desc" rows="5" placeholder="Please enter description">{{$items->h2desc}}</textarea>
                                                    </div>


                                                 </div>
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Section 3</b></h5>
                                                    
                                                    <div class="form-group m-b-20">
                                                        <label>Heading 3<span class="text-danger">*</span></label>
                                                        <input type="text" name="h3title" class="form-control" placeholder="e.g : Home Slider" value="{{$items->h3title}}">
                                                    </div>


                                                    <div class="form-group m-b-20">
                                                        <label>Description<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="h3desc" rows="5" placeholder="Please enter description">{{$items->h3desc}}</textarea>
                                                    </div>


                                                 </div>
                                            </div>


                                            <div class="col-lg-4">
                                                <div class="card-box">
                                                    <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Section 1</b></h5>
                                                    
                                                    <div class="form-group m-b-20">
                                                        <label>Heading 4 <span class="text-danger">*</span></label>
                                                        <input type="text" name="h4title" class="form-control" placeholder="e.g : Home Slider" value="{{$items->h4title}}">
                                                    </div>


                                                    <div class="form-group m-b-20">
                                                        <label>Description<span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="h4desc" rows="5" placeholder="Please enter description">{{$items->h4desc}}</textarea>
                                                    </div>


                                                 </div>
                                            </div>
                                        </div>


                                                     <button type="button" class="btn w-sm btn-white waves-effect">Cancel</button>
                                                     <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Save</button>

                                                </div>
                                            </div>


                                            

                                          


                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <hr />
                                                <div class="text-center p-20">
                                                   
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