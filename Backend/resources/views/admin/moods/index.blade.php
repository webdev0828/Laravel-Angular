@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Settings</li>
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
    @include('admin.moods.partials.add-mood-modal')
        <button type="button" class="btn btn-success m-b-sm" data-toggle="modal" data-target="#moodModal">Add Vibe</button>
                
        <div class="table-responsive">
            <table id="moods_table" class="display table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="no-sort">Description</th>
                        <th>Date</th>
                        <th class="no-sort">Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop