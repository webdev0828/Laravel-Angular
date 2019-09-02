@extends('artists.layouts.app')
@section('content')
<div ng-controller="ProfileController">
	<h1>{{ $title }}</h1>
	{{-- <ul>
		<li ng-repeat="artist in artists"><a href="{{ url('/allartists') }}"><% artist.name %></a></li>
	</ul> --}}
	<ul>
	@foreach ($data as $artist)
		<li><a href="{{ url('artist/artists', $artist->id) }}">{{ $artist->name }}</a></li>
	@endforeach
	</ul>
</div>
@stop