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
                                    <button type="button" data-toggle="modal" data-target="#con-close-modal" class="btn btn-info waves-effect waves-light">Add New user</button>
                                  
                                </div>

                                <h4 class="page-title">All Admins Users</h4>
                                <ol class="breadcrumb">
                                    
                                </ol>
                            </div>
                        </div>
                        
                        <div class="row">
                        	<div class="col-lg-12">
                        		<div class="card-box">
                        	        <div class="row m-t-10 m-b-10">
                                        <div class="col-sm-10 col-lg-10">
                                           <!-- <form role="form">
                                                <div class="form-group contact-search m-b-30">
                                                    <input type="text" id="search" class="form-control" placeholder="Search...">
                                                    <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                                                </div>
                                            </form> -->
                                        </div>

                                        <!-- <div class="col-sm-2 col-lg-2">
                                            <div class="h5 m-0">
                                               <a href="{{url('show-slider')}}"> <button type="button" class="btn btn-info waves-effect waves-light"></button></a>
                                            </div>
                                        </div> -->
                                    </div>


                        			<div class="table-responsive">
                                        <table class="table table-actions-bar">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Password</th>
                                                    <th>Role</th>
                                                    <th style="min-width: 80px;">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach($items_array as $items)
                                                <tr>
                                                   
                                                    <td>{{$items->name}}</td>
                                                    <td>{{$items->email}}</td>
                                                    <td>{{$items->password}}</td>
                                                    <td>{{$items->role}}</td>
                                                     <td>
                                                        <button type="button" data-toggle="modal" data-target="#edit-close-modal"   class=" editbutton btn btn-success waves-effect waves-light" value="{{$items->id}}" ><i class="fa fa-edit"></i>
                                                        </button>

                                                        <button type="button"   class="deleteshipping btn btn-danger waves-effect waves-light" value="{{$items->id}}" ><i class="fa fa-remove"></i>
                                                        </button>
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

            
            <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog"> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                                <h4 class="modal-title">Add Users</h4> 
                        </div> 
                        <div class="modal-body"> 
                            <div class="row"> 
                                <div class="col-md-6"> 
                                    <div class="form-group"> 
                                        <label for="field-1"  class="control-label">Name</label> 
                                            <input type="text" id="name" class="form-control"  placeholder="Name" > 
                                    </div> 
                                </div> 
                                <div class="col-md-6"> 
                                    <div class="form-group"> 
                                        <label for="field-2"  class="control-label">email</label> 
                                        <input type="email" id="email"class="form-control"  placeholder="Email"> 
                                    </div> 
                                </div> 
                            </div> 

                            <div class="row"> 
                                <div class="col-md-6"> 
                                    <div class="form-group"> 
                                        <label for="field-1"  class="control-label">Password</label> 
                                        <input type="password"  class="form-control"  id="password" placeholder="Password"> 
                                    </div> 
                                </div> 
                                <div class="col-md-6"> 
                                    <div class="form-group"> 
                                        <label for="field-2"  class="control-label">Role</label> 
                                            <select class="form-control" name="role" id="role">
                                                <option value="admin">Admin</option>
                                                <option value="staff">Staff</option>    
                                            </select> 
                                    </div> 
                                </div> 
                            </div> 

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Allow Permission</label><br>
                                    <input type="checkbox" id="brand_status" name="brand_status">
                                    <label for="brand_status">Brands</label><br>

                                    <input type="checkbox" id="parentcategory_status" name="parentcategory_status" >
                                    <label for="parentcategory_status">Parentcategories</label><br>

                                    <input type="checkbox" id="category_status" name="category_status">
                                    <label for="category_status">Categories</label><br>

                                    <input type="checkbox" id="product_status" name="product_status">
                                    <label for="product_status">Products</label><br>
                                </div>

                                <div class="col-md-4">
                                    <lable></lable><br>
                                                            
                                    <input type="checkbox" id="order_status" name="order_status">
                                    <label for="order_status">Orders</label><br>

                                    <input type="checkbox" id="slider_status" name="slider_status">
                                    <label for="slider_status">Slider</label><br>
                                                             
                                    <input type="checkbox" id="subscriber_status" name="subscriber_status">
                                    <label for="subscriber_status">Subscribers</label><br>

                                    <input type="checkbox" id="banner_status" name="banner_status">
                                    <label for="banner_status">Banners</label><br>
                                </div>

                                <div class="col-md-4">
                                    <lable></lable><br>
                                    <input type="checkbox" id="config_status" name="config_status">
                                    <label for="config_status">Setup & Config</label><br>
                                </div>
                            </div>
                        </div> 
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> 
                            <button type="button" id="saveadminuser" class="btn btn-info waves-effect waves-light">Save changes</button> 
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
                    <h4 class="modal-title">Edit Admin Users</h4> 
            </div> 
            <input type="hidden" name="" id="editid">    
            <div class="modal-body"> 
                <div class="row"> 
                    <div class="col-md-6"> 
                        <div class="form-group"> 
                            <label for="field-1"  class="control-label">Name</label> 
                                <input type="text" id="updatename" class="form-control"  placeholder="Sharjah"> 
                        </div> 
                    </div> 
                    <div class="col-md-6"> 
                        <div class="form-group"> 
                            <label for="field-2"  class="control-label">email</label> 
                            <input type="email" id="updateemail"class="form-control"  placeholder="100"> 
                        </div> 
                    </div> 
                </div> 

                <div class="row"> 
                    <div class="col-md-6"> 
                        <div class="form-group"> 
                            <label for="field-1"  class="control-label">Password</label> 
                                <input type="password"  class="form-control"  id="updatepassword" placeholder="Sharjah"> 
                        </div> 
                    </div> 
                    <div class="col-md-6"> 
                        <div class="form-group"> 
                            <label for="field-2"  class="control-label">Role</label> 
                                <select class="form-control" name="role" id="updaterole">
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>    
                                </select> 
                        </div> 
                    </div> 
                </div> 

                <div class="row">
                    <div class="col-md-4">
                        <label>Allow Permission</label><br>

                        <input type="checkbox" id="updatebrand_status" name="updatebrand_status">
                        <label for="updatebrand_status">Brands</label><br>

                        <input type="checkbox" id="updateparentcategory_status" name="updateparentcategory_status" >
                        <label for="updateparentcategory_status">Parentcategories</label><br>

                        <input type="checkbox" id="updatecategory_status" name="updatecategory_status">
                        <label for="updatecategory_status">Categories</label><br>

                        <input type="checkbox" id="updateproduct_status" name="updateproduct_status">
                        <label for="updateproduct_status">Products</label><br>
                    </div>

                    <div class="col-md-4">
                        <lable></lable><br>
                                                            
                        <input type="checkbox" id="updateorder_status" name="updateorder_status">
                        <label for="updateorder_status">Orders</label><br>

                        <input type="checkbox" id="updateslider_status" name="updateslider_status">
                        <label for="updateslider_status">Slider</label><br>
                                                             
                        <input type="checkbox" id="updatesubscriber_status" name="updatesubscriber_status">
                        <label for="updatesubscriber_status">Subscribers</label><br>

                        <input type="checkbox" id="updatebanner_status" name="updatebanner_status">
                        <label for="updatebanner_status">Banners</label><br>

                    </div>

                    <div class="col-md-4">
                        <lable></lable><br>
                        <input type="checkbox" id="updateconfig_status" name="updateconfig_status">
                        <label for="updateconfig_status">Setup & Config</label><br>
                    </div>
                </div>
            </div> 
            <div class="modal-footer"> 
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> 
                <button type="button" id="updateadminuser" class="updateadminuser btn btn-info waves-effect waves-light">Update changes</button> 
            </div> 
            </div> 
             </div>
            </div><!-- /.modal -->  
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->

@push('custom-scripts')


<script>
    


$('#saveadminuser').on('click',function(e) {

        
        var name=$("#name").val();
        var email=$("#email").val();
        var password=$("#password").val();
        var role=$("#role").val();
        if($("#brand_status").is(":checked"))
        {
             var brand_status=$("#brand_status").val();
        }
        if($("#parentcategory_status").is(":checked"))
        {
             var parentcategory_status=$("#parentcategory_status").val();
        }
        if($("#category_status").is(":checked"))
        {
             var category_status=$("#category_status").val();
        }
        if($("#product_status").is(":checked"))
        {
             var product_status=$("#product_status").val();
        }
        if($("#order_status").is(":checked"))
        {
             var order_status=$("#order_status").val();
        }
        if($("#slider_status").is(":checked"))
        {
             var slider_status=$("#slider_status").val();
        }
        if($("#subscriber_status").is(":checked"))
        {
             var subscriber_status=$("#subscriber_status").val();
        }
        if($("#banner_status").is(":checked"))
        {
             var banner_status=$("#banner_status").val();
        }
        if($("#config_status").is(":checked"))
        {
             var config_status=$("#config_status").val();
        }
       

        if(name=='')
        {
            swal("Warning","Name is required");
            
        }
        else if(email=='')
        {
            swal("Warning","Email is required");
        }
        else if(password=='')
        {
            swal("Warning","Password is required");
        }
        else
        {
            $.ajax({

            url:"{{ route('addadminuser') }}",
                method:"POST",
                data: {"_token": "{{ csrf_token() }}",
                "name": name,
                "email":email,
                "password":password,
                "role":role,
                "brand_status":brand_status,
                "parentcategory_status":parentcategory_status,
                "category_status":category_status,
                "product_status":product_status,
                "order_status":order_status,
                "slider_status":slider_status,
                "subscriber_status":subscriber_status,
                "banner_status":banner_status,
                "config_status":config_status,
                },
                success:function (data) 
                {
                console.log(data);

                swal("Info",data);
                $('#con-close-modal').modal('hide');
                location.reload();

                }

            })
        }
        


});

$(document).on('click', '.editbutton', function() { 
//$('.').on('click',function(e) {

       //var cityname=
        var id=$(this).val();
       //alert(id);

        $.ajax({

            url:"{{ route('findadminuser') }}",
                method:"POST",
                data: {"_token": "{{ csrf_token() }}","id": id},
                success:function (data) 
                {
                    console.log(data);

                    //swal("success",'Shipping Added Successfully !');
                    $("#updatename").val(data['name']);
                    $("#updateemail").val(data['email']);
                    $("#updatepassword").val(data['password']);
                    $("#updaterole").val(data['role']);
                    $("#editid").val(data['id']);

                    if(data.brand_status=="on")
                    {
                        $("#updatebrand_status").prop("checked",true);
                            
                    }

                    if(data.parentcategory_status=="on")
                    {
                        $("#updateparentcategory_status").prop("checked",true);
                            
                    }

                    if(data.category_status=="on")
                    {
                        $("#updatecategory_status").prop("checked",true);
                            
                    }

                    if(data.product_status=="on")
                    {
                        $("#updateproduct_status").prop("checked",true);
                            
                    }

                    if(data.order_status=="on")
                    {
                        $("#updateorder_status").prop("checked",true);
                            
                    }

                    if(data.slider_status=="on")
                    {
                        $("#updateslider_status").prop("checked",true);
                            
                    }

                    if(data.subscriber_status=="on")
                    {
                        $("#updatesubscriber_status").prop("checked",true);
                            
                    }

                    if(data.banner_status=="on")
                    {
                        $("#updatebanner_status").prop("checked",true);
                            
                    }

                    if(data.config_status=="on")
                    {
                        $("#updateconfig_status").prop("checked",true);
                            
                    }
                }

            })



});



$(document).on('click', '.updateadminuser', function() {
//$('.').on('click',function(e) {

        var name=$("#updatename").val();
        var email=$("#updateemail").val();
        var password=$("#updatepassword").val();
        var role=$("#updaterole").val();
        var id=$('#editid').val();
        if($("#updatebrand_status").is(":checked"))
        {
             var updatebrand_status=$("#updatebrand_status").val();
        }
        if($("#updateparentcategory_status").is(":checked"))
        {
             var updateparentcategory_status=$("#updateparentcategory_status").val();
        }
        if($("#updatecategory_status").is(":checked"))
        {
             var updatecategory_status=$("#updatecategory_status").val();
        }
        if($("#updateproduct_status").is(":checked"))
        {
             var updateproduct_status=$("#updateproduct_status").val();
        }
        if($("#updateorder_status").is(":checked"))
        {
             var updateorder_status=$("#updateorder_status").val();
        }
        if($("#updateslider_status").is(":checked"))
        {
             var updateslider_status=$("#updateslider_status").val();
        }
        if($("#updatesubscriber_status").is(":checked"))
        {
             var updatesubscriber_status=$("#updatesubscriber_status").val();
        }
        if($("#updatebanner_status").is(":checked"))
        {
             var updatebanner_status=$("#updatebanner_status").val();
        }
        if($("#updateconfig_status").is(":checked"))
        {
             var updateconfig_status=$("#updateconfig_status").val();
        }
       
       //var id=$(this).val();
       //alert(id+email+password);
       if(name=='')
        {
            swal("Warning","Name is required");
            
        }
        else if(email=='')
        {
            swal("Warning","Email is required");
        }
        else if(password=='')
        {
            swal("Warning","Password is required");
        }
        else
        {
            $.ajax({

            url:"{{ route('updateadminuser') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}",
                "name": name,
                "email":email,
                "password":password,
                "role":role,
                "id":id,
                "brand_status":updatebrand_status,
                "parentcategory_status":updateparentcategory_status,
                "category_status":updatecategory_status,
                "product_status":updateproduct_status,
                "order_status":updateorder_status,
                "slider_status":updateslider_status,
                "subscriber_status":updatesubscriber_status,
                "banner_status":updatebanner_status,
                "config_status":updateconfig_status,

                },
                success:function (data) 
                {
                    console.log(data);

                    swal("Info",data);
                   
                    $('#edit-close-modal').modal('hide');
                    location.reload();
                }

            })
        }
        



});



$(document).on('click', '.deleteshipping', function() {

       var id=$(this).val();
     //  alert(id);

        $.ajax({

            url:"{{ route('deleteadminuser') }}",
            method:"POST",
            data: {"_token": "{{ csrf_token() }}","id": id},
            success:function (data) 
                {
                console.log(data);

                swal("success",data);
               location.reload();
                
                }

            })



});

</script>
@endpush
@endsection