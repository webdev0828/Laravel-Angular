<html>
<body>
Hi {{$username}},<br/><br/>
{{ Auth::user()->name }} sent you a message:<br/><br/>
{{$mail_message}}
<br/><br/>
Thanks,<br/>
Sore Thumb Media Team
</body>
</html>
