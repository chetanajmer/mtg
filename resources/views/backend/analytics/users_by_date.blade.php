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

                                    <form action="{{route('usersbydate')}}"  method="post" >
                                                {{ csrf_field() }} 
                                                <div class="row">

                                                   <div class="col-md-3">
                                                    <div class="form-group">
                                                      <input type="date" name="start_date" class="form-control" required="">
                                                    </div>
                                                  </div>

                                                  <div class="col-md-3">
                                                    <div class="form-group">
                                                      <input type="date" name="end_date" class="form-control" required="">
                                                    </div>
                                                  </div>
                                               
                                                  <div class="col-md-3" >      
                                                    <button type="submit" class="btn btn-primary " style="float:left;">Search</button>
                                                
                                                  </div>

                                                  
                                              </form>
                                    </div>

                                    <div class="table-responsive">

                                        <table class="table table-actions-bar" id="datatable">

                                            <thead>

                                                <tr>

                                                    <th>Date</th>
                                                    <th>Views</th>
                                                </tr>

                                            </thead>



                                            <tbody>
                                              
                                              @if(!empty($users))
                                                @foreach($users as $items)
                                                  @if(!empty($items))
                                                    <tr>
                                                      @php
                                                        $date=substr($items['date'], 0, 4)."-".substr($items['date'],4,2)."-".substr($items['date'],6,2);
                                                         $all_date=date('d-M-Y',strtotime($date));
                                                      @endphp
                                                    <td>{{$all_date}}</td>
                                                    <td>{{$items['totalUsers']}}</td>
                                                        
                                                    </tr>
                                                  @endif
                                                @endforeach
                                              @else

                                               
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