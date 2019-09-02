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
        <div class="search p bg-light m-b-sm">
            <div class="row">
                <div class="col-md-2">
                    <input type="text" class="form-control custom-filter filter_start_date" id="start_date" name="start_date" placeholder="Start Date">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control custom-filter filter_end_date" id="end_date" name="end_date" placeholder="End Date">
                </div>
                
                @if(count($order) > 0)
                    <div class="col-md-8">
                        <a href="{{ url('admin/export') }}" class="btn btn-success pull-right" id="payment-export-btn">Export Data</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            <table id="payments_table" class="display table">
                <thead>
                    <tr>
                        <th>Artist Name</th>
                        <th>Plans</th>
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>Expire Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop