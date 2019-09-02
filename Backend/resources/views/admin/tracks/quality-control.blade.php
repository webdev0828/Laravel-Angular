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
<div class="panel panel-white">
    <div class="panel-body">
        <!-- @include('admin.tracks.partials.top-list-modal') -->

        <div class="col-md-7 col-sm-12 filter-form">
            <!-- <button class="btn btn-success m-b-sm spotlight-btn" data-toggle="modal" data-target="#topListModal">Spotlist</button> -->
       <!--      <div>
                <label>Artist Name</label>
                {!! Form::select('artist_id', [''=>'-Please select-']+$artistList, null, ['class' => 'form-control custom-filter', 'id'=>'artist_name']) !!}
            </div> -->
            <div>
                <label>Start Date</label>
                <input type="text" class="form-control custom-filter filter_start_date" id="start_date" name="start_date">    
            </div>
            <div>
                <label>End Date</label>
                <input type="text" class="form-control custom-filter filter_end_date" id="end_date" name="end_date">
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
        </div>  

        <div class="clearfix"></div>
        <div>&nbsp;</div>

        <div class="table-responsive">
            <table id="quality_control_table" class="display table" data-name="quality_control">
                <thead>
                    <tr>
                        <th>Track</th>
                        <th>SoundCloud</th>
                        <th>Artist</th>
                        <th>Status</th>
                       <!--  <th>Total Downloads</th>
                        <th>Total Play</th>
                        <th>Total Favourite</th> -->
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