@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{{ url('admin/dashboard') }}">Home</a></li>
        <li><a href="{{ url('/admin/artists') }}">Artists</a></li>
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
        <div class="table-responsive">
        	{!! Form::hidden('id', $user->user_id,['id'=>'artistId']) !!}
            <table id="artist_campaigns_table" class="display table">
                <thead>
                    <tr>
                        <th>Track</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Uploaded At</th>
                        <!-- <th class="no-sort">Genres</th> -->
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