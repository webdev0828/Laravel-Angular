@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Campaigns</li>
        <li><a href="{!! url('/admin/music_video_release') !!}">Stm Releases</a></li>
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
        <div class="col-md-12">
            {!! Form::model($new_releases, array('id' => 'newReleaseEditForm', 'class'=>'edit-releases-form', 'method' => 'PATCH','route' => array('admin.music_video_release.update', $new_releases['id']), 'files'=>true)) !!}
                {!! Form::hidden('id', $new_releases->id) !!}
                <div class="form-group">
                    <label>Artist Name</label>
                    {!! Form::select('artist_id', []+$artists, null, ['class' => 'form-control select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'videoRelease', 'disabled']) !!}
                    <small class="text-danger">{{ $errors->first('artist_id') }}</small>
                </div>

                <div class="form-group">
                    <label>Track</label>
                    {!! Form::select('track_id', []+$artistTracks, $new_releases->track_id, ['class' => 'form-control select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'track-data', 'disabled']) !!}
                    <small class="text-danger">{{ $errors->first('track_name') }}</small>
                </div>

                <div class="form-group">
                    <label>URL for video (Youtube)</label>
                    {!! Form::text('url',null, ['class'=>'form-control', 'placeholder'=>'URL for Youtube Video']) !!}
                    <small class="text-danger">{{ $errors->first('url') }}</small>
                </div>

                <!-- <div class="form-group">
                    <label>Download Link</label>
                    {!! Form::url('download_link',null, ['class'=>'form-control', 'placeholder'=>'Download Link']) !!}
                    <small class="text-danger">{{ $errors->first('download_link') }}</small>
                </div> -->

                <div class="form-group">
                    <label for="description">Description</label>
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>"7",]) !!}
                </div>
                
                {{-- <div class="form-group">
                    {!! Form::label('background_file', 'Background Image') !!}
                    
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('background_file',['class'=>'upload form-control', 'id'=>'editVideoBackgroundFile', 'accept'=>'image/*']) !!}
                    </div>
                    <div class="clearfix"></div>
                    @if($new_releases->background_file!='')
                        <br/>{!! HTML::Image($new_releases->background_file, null, array('style'=>'width:200px;height:200px', 'id'=>'editPreviewBackgroundFile')) !!}
                    @endif
                    <small class="text-danger">{{ $errors->first('background_file') }}</small>
                </div> --}}
                
                

                
                <div class="clearfix"></div>
                <div class="form-group pull-right">
                    <a href="{!! URL::to('admin/music_video_release') !!}" class="btn btn-default">Cancel</a>
                    <button type="submit" id="add-row" class="btn btn-success submitRelease">Save</button>
                </div>
            {!! Form::close() !!}
        </div>    
    </div>
  </div>
</div>

@stop