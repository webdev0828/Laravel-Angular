@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
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
            {!! Form::open(array('id' => 'newReleaseForm', 'route' => array('admin.music-video.store'), 'files'=>true)) !!}
                <div class="form-group">
                    <label>Track Name</label>
                    <input type="text" name="track_name" id="track_name" class="form-control" placeholder="Track Name" value="" required>
                </div>
                <!-- <div class="form-group">
                    {!! Form::label('moods','Select Moods') !!}
                    {!! Form::select('moods[]', $moods, null, ['class' => 'tokenize-sample', 'id'=>'tokenize', 'multiple' =>'true']) !!}
                </div> -->
              
                <div class="form-group">
                    <label>URL for video (Youtube)</label>
                    <input type="text" name="url_y_tube" id="y_tube_url" class="form-control" placeholder="URL for Youtube Video" value="" required>
                </div>
                <div class="form-group">
                    <label>Select Genres (max 4)</label>
                    {!! Form::select('genres[]', $genres, null, ['class' => 'tokenize-sample genresTokenize','multiple' =>'true', 'id'=>'genres']) !!}
                </div>
                <div class="form-group" id="sub-genres-block">
                    <label>Select Sub Genre</label>
                    {!! Form::select('sub_genres[]', $sub_genres, null, ['class' => 'tokenize-sample subGenresTokenize','multiple' =>'true', 'id'=>'sub_genres']) !!}
                </div>
                <div class="form-group">
                    <label for="moods">Select Vibes (max 4)</label>
                    {!! Form::select('moods[]', $moods, null, ['class' => 'tokenize-sample moodsTokenize','multiple' =>'true', 'id'=>'moods']) !!}
                    <span class="text-danger">{{ $errors->first('moods') }}</span>
                </div>
                <div class="form-group">
                    {!! Form::label('artwork_image', 'Artwork Image') !!}
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('image',['class'=>'upload form-control', 'id'=>'adminUserImage', 'accept'=>'images/*']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('download_link', 'Download mp3') !!}
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('image',['class'=>'upload form-control', 'id'=>'adminUserImage', 'accept'=>'images/*']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label>Artist Name</label>
                    {!! Form::select('artist_id', [''=>'- Please select -']+$artists, null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="add-row" class="btn btn-success">Save</button>
                </div>
            {!! Form::close() !!}
        </div>    
    </div>
  </div>
</div>

@stop