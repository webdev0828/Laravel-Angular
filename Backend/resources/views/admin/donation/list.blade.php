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
        <div class="col-md-6 col-md-offset-3">
            @foreach ($listData as $data)
            <div class="well row">
                <div class="pull-left">
                    <h3>{{ $data->userName }}</h3>
                    <p><b>Artist : </b>{{ $data->artistName }}</p> 
                    <p><b>Track : </b>{{ $data->title }}</p>
                </div>
                <h1 class="pull-right"> {{ $data->donation_amount }} <i class="fa fa-inr"></i></h1> 
            </div>
            @endforeach
        </div>
    </div>
</div>
@stop