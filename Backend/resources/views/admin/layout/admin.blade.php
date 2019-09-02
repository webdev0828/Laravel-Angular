<!DOCTYPE html>
<html>
	@section('head')
		@include('admin.layout.header')
	@show
	<body class="page-header-fixed compact-menu page-horizontal-bar">

		@section('navigation')
			@include('admin.layout.navigation')
		@show
		
		@yield('breadcrumb')
		
		<div id="main-wrapper" class="container">
		    <div class="row">
		        <div class="col-md-12">
        			@section('messages')
						@include('admin.layout.messages')
					@show

					@yield('content')

		        </div>
		    </div><!-- Row --> 
		</div><!-- Main Wrapper -->

		@section('footer')
			@include('admin.layout.footer')
		@show
		
		@yield('player')
	</body>
</html>