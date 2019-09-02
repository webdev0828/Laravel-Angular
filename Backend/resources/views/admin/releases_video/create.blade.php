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
            {!! Form::open(array('id' => 'newReleaseAddForm', 'class'=>'releases-form', 'route' => array('admin.music_video_release.store'), 'files'=>true, 'novalidate')) !!}
                <div class="form-group">
                    <div>
                        <label>Artist Name</label>
                        {!! Form::select('artist_id',[], null, ['class' => 'form-control select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'videoRelease']) !!}
                        <small class="text-danger">{{ $errors->first('artist_id') }}</small>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <label>Track</label>
                        <!-- {!! Form::text('track_name',null, ['class'=>'form-control', 'placeholder'=>'Track Name']) !!} -->
                        {!! Form::select('track_id', [], null, ['class' => 'form-control select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'track-data']) !!}
                        <small class="text-danger">{{ $errors->first('track_name') }}</small>
                    </div>
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

                <!-- <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('artwork_file', 'Artwork Image') !!}
                        
                        <div class="fileUpload btn btn-primary">
                            <span>Browse</span>
                            {!! Form::file('artwork_file',['class'=>'upload form-control', 'id'=>'videoArtworkFile', 'accept'=>'image/*']) !!}
                        </div>
                        <div class="clearfix"></div>
                        
                        <img id="previewArtworkFile" name="image" class="preview-img">
                        <div class="clearfix"></div>
                        <small class="text-danger">{{ $errors->first('artwork_file') }}</small>
                    </div>
                </div> -->


                <div class="form-group">
                    <label for="description">Description</label>
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>"7",]) !!}
                </div>

                {{-- <div class="form-group">
                    {!! Form::label('background_file', 'Background Image') !!}
                    
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('background_file',['class'=>'upload form-control', 'id'=>'videoBackgroundFile', 'accept'=>'image/*']) !!}
                    </div>

                    <div class="clearfix"></div>
                    <img id="previewBackgroundFile" name="image" class="preview-img">
                    <div class="clearfix"></div>
                </div> --}}

               

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