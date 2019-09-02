<html>
<body>
Hello {{ $artist }},<br/><br/>
@if($user->user_type == 'stm_user')
	<a href="{{ url('user/'.$user->slug.'/favourites') }}"> {{ $user->name ? $user->name : $user->first_name }}  </a> favorited your track 
	@if($type == 'track')
		<a href="{{ url('/track/'.$track->slug) }}" >{{ $track->track_name }}</a>.<br/><br/>
	@endif	
	@if($type == 'campaign')
		<a href="{{ url('/campaign/'.$track->slug) }}" >{{ $track->track_name }}</a>.<br/><br/>
	@endif	
	@if($type == 'video')
		<a href="{{ url('/video/'.$track->slug) }}" >{{ $track->track_name }}</a>.<br/><br/>
	@endif	
@endif

@if($user->user_type == 'artist')
	<a href="{{ url($user->slug.'/tracks') }}">{{ $user->name ? $user->name : $user->first_name }}  </a> favorited your track 
	@if($type == 'track')
		<a href="{{ url('/track/'.$track->slug) }}" >{{ $track->track_name }}</a>.<br/><br/>
	@endif	
	@if($type == 'campaign')
		<a href="{{ url('/campaign/'.$track->slug) }}" >{{ $track->track_name }}</a>.<br/><br/>
	@endif	
	@if($type == 'video')
		<a href="{{ url('/video/'.$track->slug) }}" >{{ $track->track_name }}</a>.<br/><br/>
	@endif	
@endif

Thanks,<br/><br/>
Sore Thumb Media Team 
</body>
</html>