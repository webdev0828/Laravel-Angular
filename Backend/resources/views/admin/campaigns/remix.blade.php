@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li><a href="javascript:void(0)">Campaigns</a></li>
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
    <div class="filter-form">
        <!-- <button class="btn btn-success m-b-sm spotlight-btn" data-toggle="modal" data-target="#topListModal">Spotlist</button> -->
        <div class="fix-width">
            <label>Artist Name</label>
            <select class="js-example-responsive form-control remix-custom-filter select2-size" size="1" multiple="multiple" id="remix_artist_name" tabindex="0">
            </select>
            
        </div>

        <div class="fix-width">
            <label>Start Date</label>
            <input type="text" class="form-control remix-custom-filter filter_start_date" id="remix_start_date" name="start_date">    
        </div>

        <div class="fix-width">
            <label>End Date</label>
            <input type="text" class="form-control remix-custom-filter filter_end_date" id="remix_end_date" name="end_date">
        </div>
    </div>
        <div class="table-responsive">
            <table id="campaign_remix_table" class="display table">
                <thead>
                    <tr>
                        <th>Track</th>
                        <th>Artist</th>
                        <th>Downloads</th>
                        <th>Play</th>
                        <th>Date</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop

@section('player')
    @include('admin.player.audio-player')
@stop