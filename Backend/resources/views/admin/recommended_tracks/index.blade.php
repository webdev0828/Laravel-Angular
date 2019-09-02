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
        <button type="button" class="btn btn-success m-b-sm" id="addrecommendedtrack">Add Recommended Tracks </button>
        <!-- Modal -->
        <div class="modal fade" id="recommendedtrackModal" tabindex="-1" role="dialog" aria-labelledby="recommendedtrackModalLabel" aria-hidden="true">
            
        </div>
        <div class="table-responsive">
            <table id="recommended_tracks_table" class="display table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop