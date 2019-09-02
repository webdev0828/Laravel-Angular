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
        <div class="filter-form">
            <!-- <button class="btn btn-success m-b-sm spotlight-btn" data-toggle="modal" data-target="#topListModal">Spotlist</button> -->
            <div>
                <label>Artist Name</label>
                {!! Form::select('artist_id', [''=>'-Please select-']+$artistList, null, ['class' => 'form-control donation-custom-filter', 'id'=>'donation_artist_name']) !!}
            </div>
            <div>
                <label>Start Date</label>
                <input type="text" class="form-control donation-custom-filter filter_start_date" id="donation_start_date" name="start_date">    
            </div>
            <div>
                <label>End Date</label>
                <input type="text" class="form-control donation-custom-filter filter_end_date" id="donation_end_date" name="end_date">
            </div>
        </div>
        <div class="table-responsive">
            <table id="donation_table" class="display table">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Artist Name</th>
                        <th class="no-sort">Amount</th>
                        <th class="no-sort">Created</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop