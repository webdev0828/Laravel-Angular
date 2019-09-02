@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Settings</li>
        <li><a href="{!! url('/admin/faqs') !!}">FAQ's</a></li>
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
            @if(isset($faq_data))
              {!! Form::model($faq_data, array('id' => 'faqsForm', 'class'=>'faqsForm', 'method' => 'PATCH','route' => array('admin.faqs.update', $faq_data['id']), 'files'=>true)) !!}
            @else
              {!! Form::open(array('id' => 'faqsForm', 'class'=>'faqsForm', 'route' => array('admin.faqs.store'), 'files'=>true)) !!}
            @endif
            {{-- faq user_id of current user --}}
            {!! Form::hidden('user_id', Auth::user()->id ) !!}
            {{-- faq title --}}
            <div class="form-group @if($errors->first('title')) has-error @endif">
                {!! Form::label('title', 'FAQ Title') !!}
                {!! Form::text('title', null, ['class' => 'form-control','placeholder'=>'FAQ Title']) !!}
                <small class="text-danger">{{ $errors->first('title') }}</small>
            </div>
            {{-- news description --}}
            <div class="form-group @if($errors->first('comment')) has-error @endif">
                {!! Form::label('comment', 'News Description') !!}
                {!! Form::textarea('comment', null, ['class' => 'form-control','rows'=>'10' ,'contenteditable'=>'true','cols'=>'50', 'required' => 'required', 'id'=>'news_textarea']) !!}
            </div>
            <div class="form-group pull-right">
                <a href="{!! URL::to('admin/faqs') !!}" class="btn btn-default">Cancel</a>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
