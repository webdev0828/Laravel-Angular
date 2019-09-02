<html>
<body>
Hi {{ $user }},<br/><br/>
Thank you for updating the email address connected to your Sore Thumb Media profile.<br/><br/>
Please confirm your new email address by using the activation link below:<br/><br/>
<a href="{{ url('change-email/'.$activationLink) }}">Confirm Email</a><br/><br/>
If you did NOT request this change, please contact Sore Thumb Media immediately on info@sorethumbmedia.co.uk.<br/><br/>
Thanks,<br>
Sore Thumb Media Team
</body>
</html>