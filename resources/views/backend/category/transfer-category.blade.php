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

                              



                                <h4 class="page-title">Transfer Category</h4>

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>







                        <div class="row">

                            <div class="col-sm-12">
                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <form action="{{ route('dotransfercategory') }}" method="post" class="form-horizontal" enctype="multipart/form-data"> {{ csrf_field() }}


                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Subcategory To Subcategory Transfer</b></h5>

                                            @if(Session::has('successMsg'))

                                            <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('successMsg') }}</div>

                                            @endif

                                            <div class="form-group m-b-20">

                                                <label>Subcategory From <span class="text-danger">*</span></label>

                                                <select class="form-control select2" name="cat_from" >

                                                    <option value="">Select Sub Category</option>
                                                    @foreach($subcategories as $item)

                                                    <option value="{{$item->id}}">{{$item->catname}}</option>

                                                    @endforeach

                                                </select>

                                            </div>

                                            <div class="form-group m-b-20">

                                                <label>Subcategory To <span class="text-danger">*</span></label>
                                             
                                                <select class="form-control select2" name="cat_to">
                                                 <option value="">Select Subcategory</option>
                                                    @foreach($subcategories as $item)

                                                    <option value="{{$item->id}}">{{$item->catname}}</option>

                                                    @endforeach

                                                </select>

                                            </div>
                                             <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                                        </div>
                                        </form>
                                    </div>

                                    <div class="col-lg-6">
                                        <form action="{{ route('dotransfersubcategory') }}" method="post" class="form-horizontal" enctype="multipart/form-data"> {{ csrf_field() }}
                                        

                                        <div class="card-box">

                                            <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>Subcategory To Category Transfer</b></h5>

                                            @if(Session::has('successMsg'))

                                            <div class="alert alert-success" id="msg" role="alert"> {{ Session::get('successMsg') }}</div>

                                            @endif

                                            <div class="form-group m-b-20">

                                                <label>Subcategory From <span class="text-danger">*</span></label>

                                                <select class="form-control select2" name="subcat_from" >

                                                    <option value="">Select Category</option>
                                                    @foreach($subcategories as $item)

                                                    <option value="{{$item->id}}">{{$item->catname}}</option>

                                                    @endforeach

                                                </select>

                                            </div>

                                            <div class="form-group m-b-20">

                                                <label>Category To <span class="text-danger">*</span></label>
                                             
                                                <select class="form-control select2" name="cat_to">
                                                 <option value="">Select Category</option>
                                                    @foreach($categories as $item)

                                                    <option value="{{$item->id}}">{{$item->catname}}</option>

                                                    @endforeach

                                                </select>

                                            </div>
                                             <button type="submit"  id="save" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                                        </div>
                                        </form>
                                    </div>

                                    
                                </div>
                            </div>

                        </div>






                    </div> <!-- container -->

                               

    

                </div> <!-- content -->





@push('header-scripts')



<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">





<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>



<style type="text/css">

img {

display: block;

max-width: 100%;

}

.preview {

overflow: hidden;

width: 160px; 

height: 160px;

margin: 10px;

border: 1px solid red;

}

.modal-lg{

max-width: 1000px !important;

}

</style>



@endpush







@push('custom-scripts')




@endpush

@endsection

