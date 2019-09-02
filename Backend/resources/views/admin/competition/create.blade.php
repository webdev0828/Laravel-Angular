@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li><a href="{!! url('/admin/competition') !!}">Competition</a></li>
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
    @if(isset($competition))
        {!! Form::model($competition, array('id' => 'competitionForm', 'class'=>'competitionEditForm', 'name'=>'competitionForm', 'method' => 'PATCH','route' => array('admin.competition.update', $competition['id']), 'files'=>true)) !!}
        {!! Form::hidden('id',null,['id' => 'formId']) !!}
        <!-- {!! Form::model($competition,['method' => 'POST','name'=>'','id'=>'','class'=>' ','url'=>URL::to('/admin/job/save'),'files'=>'true']) !!} -->
    @else
      {!! Form::open(array('id' => 'competitionForm', 'class'=>'competitionForm', 'name'=>'competitionForm', 'route' => array('admin.competition.store'), 'files'=>'true')) !!}
    @endif


        <div class="col-md-12">
            
            <div class="form-group">
                <div>
                    <label for="track_name">Track</label>
                    @if(isset($competition) && isset($tracks))
                        {!! Form::select('track_id', []+$tracks, null, ['class' => 'form-control select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'competition-track', 'disabled']) !!}
                    @else
                        {!! Form::select('track_id', [], null, ['class' => 'form-control select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'competition-track']) !!}
                    @endif
                </div>
            </div>


            <div class="form-group">
                <label for="start_date">Start date</label>
                {!! Form::text('start_date', null, ['class' => 'form-control comp_start_date', 'id'=>'start_date']) !!}
                <span class="text-danger">{{ $errors->first('start_date') }}</span>
                @if($errors->has('dateError'))
                    <span class="text-danger">{{$errors->first()}}</span>
                @endif
            </div>


            <div class="form-group">
                <label for="end_date">End date</label>
                {!! Form::text('end_date', null, ['class' => 'form-control comp_end_date']) !!}
                <span class="text-danger">{{ $errors->first('end_date') }}</span>
                @if($errors->has('dateError'))
                    <span class="text-danger">{{$errors->first()}}</span>
                @endif
            </div>


            <div class="form-group">
                <label for="announcement_date">Winner announcement date</label>
                {!! Form::text('announcement_date', null, ['class' => 'form-control']) !!}
                <span class="text-danger">{{ $errors->first('announcement_date') }}</span>
            </div>


            <div class="form-group">
                <label for="price_1">First prize</label>
                {!! Form::text('price_1', null, ['class' => 'form-control']) !!}
                <span class="text-danger">{{ $errors->first('price_1') }}</span>
            </div>


            <div class="form-group">
                <label for="price_2">Second prize</label>
                {!! Form::text('price_2', null, ['class' => 'form-control']) !!}
                <span class="text-danger">{{ $errors->first('price_2') }}</span>
            </div>


            <div class="form-group">
                <label for="inputEmail">Third prize</label>
                {!! Form::text('price_3', null, ['class' => 'form-control']) !!}
                <span class="text-danger">{{ $errors->first('price_3') }}</span>
            </div>


            <div class="form-group">
                <label for="stem_file" class="btn-label">Stem file (Only DropBox URL)</label>
                {!! Form::text('stem_file', null, ['class' => 'form-control']) !!}
                {{-- <div class="fileUpload btn btn-primary">
                    <span>Browse</span>
                    {!! Form::file('stem_file',['class'=>'upload form-control', 'id'=>'', 'accept'=>'application/zip,application/x-zip,application/x-zip-compressed,application/octet-stream']) !!}
                </div> --}}
                @if(isset($competition->stem_file))
                   {{--  <a href="{!! URL::to($competition->stem_file) !!}" download  title="download stem file">
                        Stem file [DOWNLOAD]
                    </a>&nbsp; --}}
                @endif
                
                <span class="text-danger">{{ $errors->first('stem_file') }}</span>
                <span class="text-danger stem-error"></span>
            </div>
            

            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>"7",]) !!}
            </div>
        </div>


        <div class="col-md-12">
            <div class="form-group pull-right">
                <a href="{{URL::to('admin/competition')}}" class="btn btn-default">Cancel</a>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>

    {!! Form::close() !!}
    </div>
</div>

@stop