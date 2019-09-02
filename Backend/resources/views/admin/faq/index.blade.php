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
        <a type="button" class="btn btn-success m-b-sm" href="{{ URL::route('admin.faqs.create') }}">Add FAQ</a>
        <div class="table-responsive">
            <table id="faqs_table" class="display table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Answer</th>
                        <th>Created</th>
                        <th class="no-sort">Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop