@if(Session::has('role'))
@else
<script>
window.location.href = "{{url('/admin')}}";</script>
@endif
@extends('layouts.app')
@section('content')
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Brands &amp; Clients</h4>
                                <ol class="breadcrumb">
                                    <li>The logos in the Our Brands and Our Clientele sections of the homepage.</li>
                                </ol>
                            </div>
                        </div>
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                        @foreach($types as $type => $title)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20">{{ $title }}</h4>

                                    <form method="post" action="{{ route('home-logos.store') }}" enctype="multipart/form-data" class="row m-b-20">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <div class="col-sm-4">
                                            <label for="name{{ $type }}" class="control-label">{{ $type === 'brand' ? 'Brand' : 'Client' }} name</label>
                                            <input type="text" id="name{{ $type }}" name="name" class="form-control" maxlength="100" required value="{{ old('type') === $type ? old('name') : '' }}">
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="logo{{ $type }}" class="control-label">Upload logo</label>
                                            <input type="file" id="logo{{ $type }}" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="ranking{{ $type }}" class="control-label">Position (optional)</label>
                                            <input type="number" id="ranking{{ $type }}" name="ranking" class="form-control" min="0" max="9999" placeholder="Last">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label" style="display:block;">&nbsp;</label>
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Add {{ $type === 'brand' ? 'Brand' : 'Client' }}</button>
                                        </div>
                                    </form>
                                    <p class="text-muted">JPG, PNG or WEBP, up to 2 MB. A wide logo on a white or transparent background looks best. Hide a logo to take it off the homepage without losing it.</p>

                                    <div class="table-responsive">
                                        <table class="table table-actions-bar">
                                            <thead>
                                                <tr>
                                                    <th style="width:150px;">Logo</th>
                                                    <th>Name</th>
                                                    <th style="width:230px;">Replace logo</th>
                                                    <th style="width:110px;">Position</th>
                                                    <th style="width:100px;">On website</th>
                                                    <th style="min-width:160px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($items[$type] ?? [] as $item)
                                                <tr>
                                                    <td><img src="{{ $item->logoUrl() }}" alt="" style="display:block;max-width:130px;max-height:40px;object-fit:contain;"></td>
                                                    <td><input form="logoUpdate{{ $item->id }}" type="text" name="name" class="form-control input-sm" maxlength="100" required value="{{ $item->name }}" aria-label="Name"></td>
                                                    <td><input form="logoUpdate{{ $item->id }}" type="file" name="logo" class="form-control input-sm" accept=".jpg,.jpeg,.png,.webp" aria-label="Replace the logo of {{ $item->name }}"></td>
                                                    <td><input form="logoUpdate{{ $item->id }}" type="number" name="ranking" class="form-control input-sm" min="0" max="9999" required value="{{ $item->ranking }}" aria-label="Position of {{ $item->name }}"></td>
                                                    <td><input form="logoUpdate{{ $item->id }}" type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} aria-label="Show {{ $item->name }} on the homepage"></td>
                                                    <td>
                                                        <form id="logoUpdate{{ $item->id }}" method="post" action="{{ route('home-logos.update', $item->id) }}" enctype="multipart/form-data" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm waves-effect waves-light">Save</button>
                                                        </form>
                                                        <form method="post" action="{{ route('home-logos.destroy', $item->id) }}" style="display:inline;" onsubmit="return confirm('Remove {{ addslashes($item->name) }}?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">Remove</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" style="text-align:center;">Nothing here yet. The section stays hidden on the homepage until you add one.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
@endsection
