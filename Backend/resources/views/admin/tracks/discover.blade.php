@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Listening Queues</li>
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
<style>
.radio-inline + .radio-inline, .checkbox-inline + .checkbox-inline {
    margin-top: 0;
    margin-left: 0px;
}
</style>
<div class="panel panel-white">
    <div class="panel-body">
        @include('admin.tracks.partials.top-list-modal')

        <div class="col-md-7 col-sm-12 filter-form">
            <!-- <button class="btn btn-success m-b-sm spotlight-btn" data-toggle="modal" data-target="#topListModal">Spotlist</button> -->
            <div class="fix-width">
                <label>Artist Name</label>
                <select class="js-example-responsive form-control custom-filter select2-size" size="1" multiple="multiple" id="artist_name" tabindex="0">
                </select>
                
            </div>

            <div class="fix-width">
                <label>Start Date</label>
                <input type="text" class="form-control custom-filter filter_start_date" id="start_date" name="start_date">    
            </div>

            <div class="fix-width">
                <label>End Date</label>
                <input type="text" class="form-control custom-filter filter_end_date" id="end_date" name="end_date">
            </div>

            <div class="clearfix"></div>
            
            <div>
                <br/>
                <label>Genres</label>
                {!! Form::select('geners', [''=>'- Please select -']+$genresList, null, ['class' => 'form-control custom-filter', 'id'=>'genre_name']) !!}
            </div>
        </div>

        <div class="col-md-5 col-sm-12 filter_radio_btn text-right filter-form">
            <label class="radio-inline">
              <input type="radio" value="" class="radio-filter" name="optradio" checked>All
            </label>
            <label class="radio-inline">
              <input type="radio" value="pending" class="radio-filter" name="optradio">Pending
            </label>
            <label class="radio-inline">
              <input type="radio" value="approved" class="radio-filter" name="optradio">Approved
            </label>
            <label class="radio-inline">
              <input type="radio" class="radio-filter" value="rejected" name="optradio">Rejected
            </label>
             <label class="radio-inline">
              <input type="radio" class="radio-filter" value="dripfeed" name="optradio">Drip Feed
            </label>
        </div>

        <div class="clearfix"></div>
        <div>&nbsp;</div>

        <div class="table-responsive">
            <table id="tracks_table" class="display table" data-name="discover">
                <thead>
                    <tr>
                        <th>Track</th>
                        <th>Artist</th>
{{--                         <th>Genre</th> --}}
                        <th>Status</th>
                        <th>Downloads</th>
                        <th>Play</th>
                        <th>Favourite</th>
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