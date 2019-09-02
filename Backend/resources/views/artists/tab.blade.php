@extends('artists.layouts.app')
@section('content')
	<tabset ng-controller="ProfileController">
	    <tab style="cursor: pointer;" ng-repeat="tab in tabs"
	         heading="<% tab.heading %>"
	         select="setTabContent(tab.content)" active="tab.active">
	    </tab>
	    {{-- <ng-include src="tabContentUrl"></ng-include> --}}
	    @include('artists.dashboard')
		@include('artists.profile')
	</tabset>
@stop