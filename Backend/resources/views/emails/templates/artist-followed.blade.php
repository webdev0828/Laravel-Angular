<html>
<body>
Hello {{ $artist }},<br/><br/>
@if($user->user_type == 'stm_user')
	<a href="{{ url('user/'.$user->slug.'/favourites') }}"> {{ $user->first_name ? $user->first_name : $user->name }} </a>followed you.<br/><br/>
@endif
@if($user->user_type == 'artist')
	<a href="{{ url($user->slug.'/tracks') }}"> {{ $user->name ? $user->name : $user->first_name }} </a>followed you.<br/><br/>
@endif
Thanks,<br/><br/>
Sore Thumb Media Team 
</body>
</html>