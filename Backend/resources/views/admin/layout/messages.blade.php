@if (Session::has('message'))
	<?php $msg = Session::get('message');
	$class = 'success';?>
@elseif (Session::has('error'))
	<?php $msg = Session::get('error');
	$class = 'danger';?>
@endif

@if (!empty($msg))
	<div class="row">
        <div class="col-md-12">
			<div role="alert" class="alert alert-{{$class}} alert-dismissible" id="alertMessage">
				<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
				{{ $msg }}
			</div>
		</div>
	</div>
@endif

{{-- @if ($errors->any()) --}}
{{-- <div role="alert" class="alert alert-danger alert-dismissible">
    <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
    <ul>
        @foreach($errors->all(':message') as $error_msg)
        	<li>{{$error_msg}}</li>
    	@endforeach
	</ul>
</div> --}}
{{-- @endif --}}