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
                <select class="js-example-responsive form-control video-custom-filter select2-size" size="1" multiple="multiple" id="artistId" tabindex="0">
                </select>
            </div>

            <div>
                <label>Genres</label>
                {!! Form::select('geners', [''=>'- Please select -']+$genresList, null, ['class' => 'form-control video-custom-filter', 'id'=>'genre_name']) !!}
            </div>

            <a type="button" class="btn btn-success m-b-sm pull-right btn-top-margin" href="{{ URL::route('admin.music_video_release.create') }}">Add New</a>
        </div>

        <div class="table-responsive">
            <table id="releases_video_table" class="display table">
                <thead>
                    <tr>
                        <th>Track</th>
                        <th>Artist</th>
                        <th class="no-sort">Genres</th>
                        <th>Date</th>
                        <th class="no-sort">Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop

@section('player')
    @include('admin.player.video-player')
@stop