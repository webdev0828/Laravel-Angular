@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Settings</li>
        <li><a href="{!! url('/admin/news') !!}">News</a></li>
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
        <div class="col-md-12">
            @if(isset($news))
            {!! Form::model($news, array('id' => 'newsForm', 'class' =>'newsEditForm', 'method' => 'PATCH','route' => array('admin.news.update', $news['id']), 'files'=>true)) !!}
            @else
              {!! Form::open(array('id' => 'newsForm', 'class' =>'newsForm', 'route' => array('admin.news.store'), 'files'=>true)) !!}
            @endif
            {{-- news user_id of current user --}}
         
            {{-- select box for news category type --}}
            <div class="form-group">
              {!! Form::label('category_id','Select Category') !!}
              {!! Form::select('category_id', [''=>'- Please select -']+$categories, null, ['class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('category_id') }}</small>
            </div>

             <div class="form-group">
              {!! Form::label('user_id','Select User') !!}
              {!! Form::select('user_id', [''=>'- Please select -']+$adminUsers, null, ['class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('user_id') }}</small>
            </div> 

            {{-- news title --}}
            <div class="form-group @if($errors->first('title')) has-error @endif">
                {!! Form::label('title', 'News Title') !!}
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('title') }}</small>
            </div>
            {{-- news description --}}
            <div class="form-group @if($errors->first('description')) has-error @endif">
                {!! Form::label('description', 'News Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','rows'=>'10' ,'contenteditable'=>'true','cols'=>'50', 'required' => 'required', 'id'=>'news_textarea']) !!}
                <small class="text-danger">{{ $errors->first('description') }}</small>
            </div>
            {{-- news Tags --}}
            <div class="form-group @if($errors->first('tags')) has-error @endif">
              {!! Form::label('tags', 'News Tags') !!}
              {!! Form::select('tags[]', $taglist, null, ['class' => 'tokenize-sample', 'id'=> 'tokenize', 'multiple'=>'multiple']) !!}
              <small class="text-danger">{{ $errors->first('tags') }}</small>
            </div>

            
            <div class="form-group">
              <img id="newsPhoto" name="image_name"  style="{!! !empty($news->image_name) ? '' : 'display:none' !!}" src="{!! asset(!empty($news->image_name) ? asset('/timthumb.php?src='.$news->image_name.'&w=500&h=500&q=100') : Config::get('constants.default_track_image')) !!}" class="img-thumbnail default-img-lg" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="News Image">
            </div>
            
            
            <div class="form-group @if($errors->first('image_name')) has-error @endif">
                {!! Form::label('image_name', 'Upload Image') !!}
                <div class="fileUpload btn btn-primary">
                    <span>Browse</span>
                    {!! Form::file('image_name',['class'=>'upload form-control', 'id'=>'newsImage', 'accept'=>'image/*']) !!}
                </div>
                <div class="upload-error"></div>
                <small class="text-danger">{{ $errors->first('image_name') }}</small>
                <small class="text-danger">Note: Image size must be 1200 x 600px or more.</small>

            </div>
            <input type="hidden" id="image-valid">

            <div class="form-group pull-right">
                <a href="{!! URL::to('admin/news') !!}" class="btn btn-default">Cancel</a>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>

@stop