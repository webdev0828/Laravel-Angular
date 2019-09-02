@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Listening Queues</li>
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
        {!! Form::open(array('id' => 'competitionForm', 'class'=>'competitionForm', 'name'=>'competitionForm', 'url' => '/admin/competition-state')) !!}
        {{--{!! Form::open(array('id' => 'competitionForm', 'class'=>'competitionForm', 'name'=>'competitionForm', 'url' => '/api/remix-competition/change-state')) !!}--}}
       <div class="col-md-7 col-sm-12 filter-form">
            <!-- <button class="btn btn-success m-b-sm spotlight-btn" data-toggle="modal" data-target="#topListModal">Spotlist</button> -->
            <div class="fix-width">
                <label>Competition Name</label>
                {!! Form::select('competition_id', []+$competitionList, $letestCompetition ? $letestCompetition->id : '', ['class' => 'form-control competition-name-filter custom_width select2-size', 'tabindex'=>'0', 'multiple'=>'multiple', 'id'=>'remix_competition_name']) !!}
                <!-- <select class="form-control competition-name-filter select2-size" size="1" multiple="multiple" id="remix_competition_name" tabindex="0"> -->
                </select>
                
            </div>
        </div>

        <div class="col-md-5 col-sm-12 filter_radio_btn text-right filter-form">
            <label class="radio-inline">
              <input type="radio" value="" class="radio-filter" name="optradio" checked>All
            </label>
            <label class="radio-inline">
              <input type="radio" value="bad" class="radio-filter" name="optradio">Bad
            </label>
            <label class="radio-inline">
              <input type="radio" value="good" class="radio-filter" name="optradio">Good
            </label>
            <label class="radio-inline">
              <input type="radio" value="finalist" class="radio-filter" name="optradio">Finalist
            </label>
        </div>

        <div class="clearfix"></div>
        <div>&nbsp;</div>

        <div class="table-responsive">
            <table id="remix-competition-table" class="display table">
                <thead>
                    <tr>
                        <th>Track</th>
                        <th>Artist</th>
                        <th class="no-sort">Bad</th>
                        <th class="no-sort">Good</th>
                        <th class="no-sort">Finalist</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="pull-right">
            <input type="button" class="btn btn-success submit-btn" value="Save Selection">
        </div>    
    {!! Form::close() !!}
    </div>
</div>
@stop

@section('player')
    @include('admin.player.audio-player')
@stop