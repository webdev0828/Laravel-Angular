<html>
<body>
Hello {{ $user }},<br/><br/>
We have taken a listen to <a href="{{ url('/track/'.$track->slug) }}" >{{ $track->track_name }} </a> and feel that it deserves to be reposted on Sore Thumb Media's Soundcloud channel. Keep an eye out for your track as it will be reposted soon!<br/><br/>
Thanks,<br/><br/>
Sore Thumb Media Team 
</body>
</html>