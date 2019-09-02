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
        <button type="button" class="btn btn-success m-b-sm" id="addVideoTemplate">Add Template</button>
        <!-- Modal -->
        <div class="modal fade" id="templatesModal" tabindex="-1" role="dialog" aria-labelledby="templatesModalLabel" aria-hidden="true">
            
        </div>
        <div class="table-responsive">
            <table id="video_template_table" class="display table">
                <thead>
                    <tr>
                        <th>Template Name</th>
                        <th>Template URL</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop