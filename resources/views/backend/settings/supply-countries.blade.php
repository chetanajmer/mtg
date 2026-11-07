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
                                <h4 class="page-title">Supplies To</h4>
                                <ol class="breadcrumb">
                                    <li>The countries and flags shown at the top of every page of the website.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                                    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                                    <form method="post" action="{{ route('supply-countries.store') }}" class="row m-b-20">
                                        @csrf
                                        <div class="col-sm-5">
                                            <label for="supplyCode" class="control-label">Add a country</label>
                                            <select id="supplyCode" name="code" class="form-control" required>
                                                <option value="">Choose a country</option>
                                                @foreach($available as $code => $name)
                                                    <option value="{{ $code }}" {{ old('code') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="supplyRanking" class="control-label">Position (optional)</label>
                                            <input type="number" id="supplyRanking" name="ranking" class="form-control" min="0" max="9999" value="{{ old('ranking') }}" placeholder="Last">
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="control-label" style="display:block;">&nbsp;</label>
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Add Country</button>
                                        </div>
                                    </form>

                                    <p class="text-muted">The header shows up to 8 flags in position order, and any others as "+1", "+2" and so on. Hide a country to take it off the website without losing it.</p>

                                    <div class="table-responsive">
                                        <table class="table table-actions-bar">
                                            <thead>
                                                <tr>
                                                    <th style="width:60px;">Flag</th>
                                                    <th>Country</th>
                                                    <th style="width:130px;">Position</th>
                                                    <th style="width:110px;">On website</th>
                                                    <th style="min-width:170px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($items as $item)
                                                <tr>
                                                    <td><img src="{{ $item->flagUrl() }}" alt="" width="26" height="26" style="border-radius:50%;box-shadow:0 0 0 1px rgba(0,0,0,.12);"></td>
                                                    <td>{{ $item->name }}</td>
                                                    <td><input form="supplyUpdate{{ $item->id }}" type="number" name="ranking" class="form-control input-sm" min="0" max="9999" value="{{ $item->ranking }}" required aria-label="Position of {{ $item->name }}" style="width:90px;"></td>
                                                    <td><input form="supplyUpdate{{ $item->id }}" type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} aria-label="Show {{ $item->name }} on the website"></td>
                                                    <td>
                                                        <form id="supplyUpdate{{ $item->id }}" method="post" action="{{ route('supply-countries.update', $item->id) }}" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm waves-effect waves-light">Save</button>
                                                        </form>
                                                        <form method="post" action="{{ route('supply-countries.destroy', $item->id) }}" style="display:inline;" onsubmit="return confirm('Remove {{ addslashes($item->name) }} from the list?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">Remove</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="5" style="text-align:center;">No countries yet. The "Supplies to" section stays hidden until you add one.</td></tr>
                                                @endforelse
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
