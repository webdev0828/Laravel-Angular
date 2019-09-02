@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">{!! $title !!}</li>
    </ol>
</div>
<div class="page-title">
    <div class="container">
        <h3>{!! $title !!}</h3>
    </div>
</div>
@stop
@section('content')
<div class="panel panel-white">
    <div class="panel-body">
        <a class="btn btn-success m-b-sm" href="{{ URL::route('admin.competition.create') }}">Add competition</a>
       @include('admin.competition.partials.choose-winner-modal')
       @include('admin.competition.partials.view-winner-modal')
       @include('admin.competition.partials.view-participates-list')
        <div class="table-responsive">
            <table id="competition_table" class="display table">
                <thead>
                    <tr>
                        <th>Track</th>
                        <!-- <th class="row-size">Competition name</th> -->
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Winner announcement date</th>
                        <th>Status</th>
                        <th class="no-sort">Actions</th>
                        <!-- <th class="no-sort">Winners</th> -->
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