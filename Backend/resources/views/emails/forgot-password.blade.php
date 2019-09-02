<p>Hello {{ $user }},</p>

<p>Please click on the following link to reset your password:</p>

<p><a href="{{ url('/reset-password/'.$forgotPasswordUrl) }}">Reset Password </a></p>

<p>Thanks & Regards <br>
STM Team
</p>


{{-- Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a> --}}
