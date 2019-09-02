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
<div class="row">
	@if(count($comments) > 0)
		<div class="col-sm-12">
			<h3>{{ count($comments) }} Comments </h3>
		</div>
	@else
		<div class="col-sm-12">
			<span class="center">No Comments Available on this News</span>
		</div>
	@endif
</div>
<div class="panel panel-white">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">&nbsp;
 			</div><!-- /col-sm-12 -->
		</div><!-- /row -->
		@foreach ($comments as $comment)
		<div class="row">
			<div class="col-sm-1">
				<div class="thumbnail">
					<img class="img-responsive user-photo" src="{{ asset('/images/avatarlogo.png')}}">
				</div><!-- /thumbnail -->
			</div><!-- /col-sm-1 -->

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-12 page-breadcrumb">
						<strong>{{ $comment->name }}</strong> <span class="text-muted pull-right">{{ $comment->created_at->diffForHumans() }}</span>
					</div>

				</div>
				<div class="row">
					<div class="col-sm-12">
						<p>{{ $comment->comment_text }}</p>
						<a href="{{ \URL::to('admin/news/comments/delete/'.$comment->id) }}" onclick="return confirmDelete();" class="pull-right"><i class="fa fa-trash"></i></a>
					</div>
				</div>
 			</div><!-- /col-sm-10 -->
		</div><!-- /row -->
		@endforeach
	</div><!-- /container -->
</div>
@stop