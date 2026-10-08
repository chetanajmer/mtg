@php
$headersettings=\App\Models\Header_setting::where('id','1')->first();
@endphp

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

                                   

                                  

                                </div>



                              

                                <ol class="breadcrumb">

                                    

                                </ol>

                            </div>

                        </div>

                        

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card-box">

                                    <form action="{{route('mostvisitedpage')}}"  method="post" >
                                                {{ csrf_field() }} 
                                                <div class="row">
                                                   
                                                 <!--  <div class="col-md-3">
                                                    <div class="form-group" >
                                                      <select class="form-control select2" name="options"  >
                                                        <option value="" @if($options=='') selected="" @endif>Select</option>
                                                        <option value="1" @if($options==1) selected="" @endif>1 Month</option>
                                                        <option value="3" @if($options==3) selected="" @endif>3 Months</option>
                                                        <option value="6" @if($options==6) selected="" @endif>6 Months</option>
                                                        <option value="1year" @if($options=='1year') selected="" @endif>1 Year</option>
                                                      </select>
                                                    </div>
                                                  </div> -->

                                                   <div class="col-md-3">
                                                    <div class="form-group">
                                                      <input type="date" name="start_date" class="form-control" >
                                                    </div>
                                                  </div>

                                                  <div class="col-md-3">
                                                    <div class="form-group">
                                                      <input type="date" name="end_date" class="form-control">
                                                    </div>
                                                  </div>
                                               
                                                  <div class="col-md-3" >      
                                                    <button type="submit" class="btn btn-primary " style="float:left;">Search</button>
                                                
                                                  </div>

                                                  
                                              </form>
                                    </div>

                                    <div class="table-responsive" >

                                        <table class="table table-actions-bar" id="datatable">

                                            <thead>

                                                <tr>

                                                    <th>Pages</th>
                                                    <th>Views</th>
                                                </tr>

                                            </thead>



                                            <tbody>

                                                @if(count($visitedpages)>0)
                                                @foreach($visitedpages as $val)
                                                <tr>
                                                
                                          <td><a href="#" target="_blank">{{$val['fullPageUrl']}}</a></td>
                                                <td>{{$val['screenPageViews']}}</td>
                                              </tr>
                                          @endforeach
                                                @else
                                                <p>No data Available !</p>
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



            

            

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->


<style>
    .dataTables_length {
 
    display: none!important;
}

#datatable_filter
{
     display: none!important;
}
</style>

@endsection