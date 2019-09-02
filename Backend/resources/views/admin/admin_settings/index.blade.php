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
        <label class="checkbox-inline">
            @if($emailSend)
                <input class="email-notification" type="checkbox" data-toggle="toggle" checked data-onstyle="info" data-offstyle="warning" /> Notification
            @else
                <input class="email-notification" type="checkbox" data-toggle="toggle" data-onstyle="info" data-offstyle="warning" /> Notification
            @endif
        </label>
    </div>
</div>
@stop