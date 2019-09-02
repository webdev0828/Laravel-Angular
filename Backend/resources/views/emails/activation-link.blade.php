<html>
<body>
<p>Hello {{ $user }},</p>

<p>Welcome to SoreThumbMedia ! Please click on the following link to confirm your SoreThumbMedia account:</p>

<p><a href="{{ url('activate/'.$activationLink) }}">Activate Account</a></p>
<p>Thanks,<br>
Sore Thumb Media Team
</p>
</body>
</html>