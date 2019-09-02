@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{{ url('admin/dashboard') }}">Home</a></li>
        <li class="active">{{ $title }}</li>        
    </ol>
</div>
<div class="page-title">
    <div class="container">
        <h3>{{ $title }}</h3>
    </div>
</div>
@stop
@section('content')
<div class="panel panel-white">
    <div class="panel-body">
        {{-- @include('admin.artists.partials.add-artist-modal') --}}
        {{-- <button type="button" class="btn btn-success m-b-sm" data-toggle="modal" data-target="#addartist">Add Artist</button> --}}
        <!-- <button type="button" class="btn btn-success m-b-sm" id="addartist">Add New Artist</button> -->
        <div class="modal fade" id="ArtistEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            
        </div>
        <!-- <div class="form-group row">
            <label class="col-sm-2 control-label">Filter by Subscriptions</label>
            <div class="col-sm-3">
                <select class="form-control m-b-sm" name="subscription">
                    <option value="">Select Subscription</option>
                    @if(!empty($subscriptions))
                        @foreach($subscriptions as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div> -->
        <div class="table-responsive">
            <table id="artists_table" class="display table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="no-sort">Location</th>
                        <th>Soundcloud</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="no-sort">Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop