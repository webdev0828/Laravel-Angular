<html>
<body>
Hello {{ $username }},<br/><br/>
<b>{{ Auth::user()->name }}</b> sent you a message:<br/><br/>
{{$mail_message}}<br/><br/>
Thanks,<br/><br/>
Sore Thumb Media Team 
</body>
</html>
