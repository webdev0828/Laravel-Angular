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

        @if(count($plans) > 0)
            <div class="row">
                <div class="well">
                    <h2>Discover Demo Limit</h2><hr>
            {!! Form::open(array('id' => 'planForm', 'files'=>true)) !!}
                @foreach($plans as $plan)
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('demo_limit_'.$plan->id, $plan->name,['class'=>'text-sm']) !!}
                            {!! Form::text('demo_limit_'.$plan->id, $plan->discover_demo_limit, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            
            <div class="row">
                <div class="well">
                    <h2>Remix Demo Limit</h2><hr>
                @foreach($plans as $plan)
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('remix_limit_'.$plan->id, $plan->name,['class'=>'text-sm']) !!}
                            {!! Form::text('remix_limit_'.$plan->id, $plan->remix_demo_limit, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            <div class="row">
                <div class="well">
                    <h2>Video Demo Limit</h2><hr>
                @foreach($plans as $plan)
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('video_limit_'.$plan->id, $plan->name,['class'=>'text-sm']) !!}
                            {!! Form::text('video_limit_'.$plan->id, $plan->video_demo_limit, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 pull-right">
                      <div class="form-group">
                        <button type="submit" id="add-row" class="btn btn-success pull-right">Save</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        @else
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center">No data found</h3>
                </div>
            </div>
        @endif

    </div>
</div>
@stop