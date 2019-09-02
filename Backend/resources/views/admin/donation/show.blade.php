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
        <div class="table-responsive">
            {{-- {!! Form::open(array('id' => 'donationForm', 'route' => array('admin.donation.store')) !!} --}}
            <h1>Donation form</h1>
            <div class="well">
                <span><p>Donate the amount to <b>{{ $data->name }}</b></p></span>
            </div>
            {!! Form::open(['route' => ['admin.donation.store']]) !!}
                {!! Form::hidden('user_id', Auth::user()->id ) !!}
                {!! Form::hidden('artist_id', $data->artistId ) !!}
                {!! Form::hidden('track_id', $data->trackId ) !!}
                <div class="form-group col-md-5">
                {!! Form::text('donation_amount', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Amount']) !!}
                </div>
                <button type="submit" id="add-row" class="btn btn-success">Submit</button>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop