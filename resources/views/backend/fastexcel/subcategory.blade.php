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

                              



                                <h4 class="page-title"></h4>

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

                                                    <th>ID</th>
                                                    <th style="min-width: 80px;">Error</th>

                                                </tr>

                                            </thead>



                                            <tbody>
                                                  @foreach($data as $items)

                                                <tr>

                                                   

                                                    <td>{{$items['id']}}</td>

                                                     <td>

                                                        @if(empty($items['catname']))

                                                        <p>Category Name is empty.</p>
                                                        @endif

                                                        @if(empty($items['is_active']))

                                                        <p>Category Status is empty</p>
                                                        @endif

                                                        @if(empty($items['catdescription']))

                                                        <p>Category Description is empty</p>
                                                        @endif

                                                         @if(empty($items['slug']))

                                                          <p>Category Slug is empty</p>
                                                        @endif

                                                         @if(empty($items['ranking']))

                                                        <p>Category ranking is empty</p>
                                                        @endif

                                                    </td>

                                                </tr>

                                                @endforeach  

                                                



                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                                

                            </div> <!-- end col -->



                            

                        </div>


                         <div class="row">

                             <div class="col-lg-12">
                                <a href="{{url('listsubcat')}}" class="m-r-5"> <button type="button" class="btn btn-info waves-effect waves-light">Back</button></a>
                             </div>
                        </div>
                        

                        

                        



                    </div> <!-- container -->

                               

                </div> <!-- content -->



                



            </div>



            

            

            <!-- ============================================================== -->

            <!-- End Right content here -->

            <!-- ============================================================== -->





@endsection