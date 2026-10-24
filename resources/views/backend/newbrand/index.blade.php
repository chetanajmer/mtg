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
                        <div class="btn-group pull-right m-t-15">
                            <a href="{{route('newbrand.create')}}">
                                <button type="button" class="btn btn-info waves-effect waves-light">Add New Brand</button>
                            </a>
                        </div>
                        <h4 class="page-title">All Brands</h4>
                        <ol class="breadcrumb"></ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th>Logo</th>
                                            <th>Brand Name</th>
                                            <th>Slug</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($brands as $brand)
                                        <tr>
                                            <td>
                                                @if(!empty($brand->logo))
                                                    <img src="{{ asset('upload/newbrand/'.$brand->logo) }}" class="img-rounded" style="width:80px; height:80px; object-fit:cover;">
                                                @else
                                                    <span class="text-muted">No Logo</span>
                                                @endif
                                            </td>
                                            <td>{{ $brand->brand_name }}</td>
                                            <td>{{ $brand->slug }}</td>
                                            <td>
                                                <a href="{{route('newbrand.edit', $brand->id)}}" class="table-action-btn">
                                                    <i class="md md-edit"></i>
                                                </a>
                                                <form action="{{route('newbrand.destroy', $brand->id)}}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="table-action-btn" onclick="return confirm('Are you sure you want to delete this brand?')">
                                                        <i class="md md-close"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
