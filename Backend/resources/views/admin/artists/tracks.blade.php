@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{{ url('admin/dashboard') }}">Home</a></li>
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
        <ul>
            @foreach($data as $track)
                <li><a>{{ $track->title }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
@stop