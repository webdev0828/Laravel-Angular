<html>
<body>
Hello {{ $user }},<br/><br/>
We have taken a listen to <a href="{{ url('/track/'.$track->slug) }}" >{{ $track->track_name }} </a> and feel that it deserves to be promoted in the Discover section of Sore Thumb Media. Keep an eye out for your track as it will be dropping into Discover soon!<br/><br/>
Thanks,<br/><br/>
Sore Thumb Media Team 
</body>
</html>