@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";
</script>
@endif

@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Edit Brand</h4>
                        <ol class="breadcrumb"></ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form action="{{route('newbrand.update', $brand->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group m-b-20">
                                    <label>Brand Name <span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" name="brand_name" placeholder="Enter brand name" value="{{$brand->brand_name}}">
                                </div>

                                <div class="form-group m-b-20">
                                    <label>Brand Logo</label>
                                    @if(!empty($brand->logo))
                                        <div class="m-b-10">
                                            <img src="{{ asset('upload/newbrand/'.$brand->logo) }}" class="img-rounded" style="width:100px; height:100px; object-fit:cover;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="logo" accept="image/*">
                                    <small class="text-muted">Upload brand logo (JPEG, PNG, JPG, GIF - Max 2MB). Leave empty to keep existing logo.</small>
                                </div>

                                <div class="text-center p-20">
                                    <button type="submit" class="btn w-sm btn-default waves-effect waves-light">Update</button>
                                    <a href="{{route('newbrand.index')}}" class="btn w-sm btn-white waves-effect">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
