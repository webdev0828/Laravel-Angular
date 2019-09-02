<p>Hello {{ $user->name }},</p>

<p>Please click on below link to reset your password.</p>

<p><a href= "{!! url('admin/reset-password', $forgotPasswordUrl) !!}"> Reset Password </a></p>
<p>Thanks & Regards <br>
STM Team
</p>
