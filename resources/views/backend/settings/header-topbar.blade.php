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
                                <h4 class="page-title">Header Top Bar</h4>
                                <ol class="breadcrumb">
                                    <li>The left side of the black bar at the very top of every page: the language and currency menus and the offer texts, shown left to right by position.</li>
                                </ol>
                            </div>
                        </div>
                        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                        <div class="card-box">
                            <h4 class="m-t-0 m-b-20">Add an offer</h4>
                            <form method="post" action="{{ route('header-topbar.store') }}" class="row">
                                @csrf
                                <div class="col-sm-4">
                                    <label for="tbText" class="control-label">Text</label>
                                    <input type="text" id="tbText" name="text" class="form-control" maxlength="60" required value="{{ old('text') }}" placeholder="e.g. FREE DELIVERY OVER AED 500">
                                </div>
                                <div class="col-sm-4">
                                    <label for="tbLink" class="control-label">Link when clicked (optional)</label>
                                    <input type="text" id="tbLink" name="link" class="form-control" maxlength="255" value="{{ old('link') }}" placeholder="/search?list=sale">
                                </div>
                                <div class="col-sm-2">
                                    <label for="tbRanking" class="control-label">Position (optional)</label>
                                    <input type="number" id="tbRanking" name="ranking" class="form-control" min="0" max="9999" placeholder="Last">
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label" style="display:block;">&nbsp;</label>
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Add offer</button>
                                </div>
                            </form>
                            <p class="text-muted m-t-10">Make sure any code or discount shown here really exists. Switch an offer off to hide it without losing it.</p>
                        </div>

                        <div class="card-box">
                            <div class="table-responsive">
                                <table class="table table-actions-bar">
                                    <thead>
                                        <tr>
                                            <th style="width:150px;">Item</th>
                                            <th>Text</th>
                                            <th>Link (offers only)</th>
                                            <th style="width:100px;">Position</th>
                                            <th style="width:100px;">On website</th>
                                            <th style="min-width:150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                        @php $form = 'tbUpdate' . $item->id; @endphp
                                        <tr>
                                            <td>{{ $types[$item->type] ?? $item->type }}</td>
                                            <td><input form="{{ $form }}" type="text" name="text" class="form-control input-sm" maxlength="60" required value="{{ $item->text }}" aria-label="Text"></td>
                                            <td>
                                                @if($item->type === 'offer')
                                                    <input form="{{ $form }}" type="text" name="link" class="form-control input-sm" maxlength="255" value="{{ $item->link }}" placeholder="No link" aria-label="Link">
                                                @else
                                                    <span class="text-muted">{{ $item->type === 'language' ? 'The site is in English only' : 'Prices are in AED only' }}</span>
                                                @endif
                                            </td>
                                            <td><input form="{{ $form }}" type="number" name="ranking" class="form-control input-sm" min="0" max="9999" required value="{{ $item->ranking }}" aria-label="Position"></td>
                                            <td><input form="{{ $form }}" type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} aria-label="Show on the website"></td>
                                            <td>
                                                <form id="{{ $form }}" method="post" action="{{ route('header-topbar.update', $item->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm waves-effect waves-light">Save</button>
                                                </form>
                                                @if($item->type === 'offer')
                                                <form method="post" action="{{ route('header-topbar.destroy', $item->id) }}" style="display:inline;" onsubmit="return confirm('Remove this offer?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">Remove</button>
                                                </form>
                                                @endif
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
@endsection
