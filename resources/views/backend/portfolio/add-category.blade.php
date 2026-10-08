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
      <div class="row">
        <div class="col-sm-12">
          <h4 class="page-title">Add Category</h4>
          <ol class="breadcrumb"></ol>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <form action="{{ route('do_add_portfolio_category') }}"  name="form1"  id="choice_form" method="post" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-lg-6">
                <div class="card-box">
                  <h5 class="text-muted text-uppercase m-t-0 m-b-20"><b>General</b></h5>
                  
                  <div class="form-group m-b-20">
                    <label>Category name <span class="text-danger">*</span></label>
                    <input type="text" required name="name"  class="form-control" >
                  </div>
                 
                  <div class="form-group m-b-20">
                    <label class="m-b-15">Status <span class="text-danger">*</span></label>
                    <br/>
                    <div class="radio radio-inline">
                      <input type="radio" id="inlineRadio1" value="online" name="is_active" checked="">
                      <label for="inlineRadio1"> Online </label>
                    </div>
                    <div class="radio radio-inline">
                      <input type="radio" id="inlineRadio2" value="offline" name="is_active">
                      <label for="inlineRadio2"> Offline </label>
                    </div>
                  </div>
                </div>
              </div>

              
            </div>

           
        </div>

          

        

         

     

       

        
          <div class="row">

            <div class="col-sm-12">

              <div class="text-center p-20">

              <button type="Submit" class="btn w-sm btn-default waves-effect waves-light">Save</button>

              </div>

            </div>

          </div>

        </form>

      </div>

    </div>

  </div> <!-- container -->

</div> <!-- content -->











@push('header-scripts')



<style>

   .remove-option{

    color: #2271b1;

    border-color: #2271b1;

    background: #dfe7e7;

    vertical-align: top;

    padding: 7px;

}

</style>











@endpush















@push('custom-scripts')







<script>



                $('.summernote').summernote({



                    height: 250,                 // set editor height



                    minHeight: null,             // set minimum height of editor



                    maxHeight: null,             // set maximum height of editor



                    focus: false                 // set focus to editable area after initializing summernote



                });



                



                $('.inline-editor').summernote({



                    airMode: true            



                });







</script>




@endpush



@endsection





