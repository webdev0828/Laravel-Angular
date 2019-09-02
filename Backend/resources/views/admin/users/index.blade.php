@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
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
            <div class="modal fade" id="UserEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            </div>
        <div class="table-responsive">
            <table id="stm_users_table" class="display table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="no-sort">Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="no-sort">Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop