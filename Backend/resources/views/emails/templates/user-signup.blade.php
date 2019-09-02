<html>
<body>
Hello {{ $user }},<br/><br/>
Thanks for signing up as a Sore Thumb Media user!<br/><br/>
To get started, click on the link below to confirm your Sore Thumb Media account:<br/><br/>
<a href="{{ url('activate/'.$activationLink) }}">Confirmation Link</a><br/><br/>
Once this has been done, you’re free to explore the platform.<br/><br/>
Enjoy!<br/><br/>
Sore Thumb Media Team 
</body>
</html>