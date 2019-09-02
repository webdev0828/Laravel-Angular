<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Validator;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function getForgotPasswordPage(){
        if (property_exists($this, 'linkRequestView')) {
            return view($this->linkRequestView);
        }

        if (view()->exists('auth.passwords.email')) {
            return view('auth.passwords.email');
        }

        return view('auth.password');
    }

    public function postForgotPasswordEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return redirect('admin/forgot-password')
                        ->withErrors($validator)
                        ->withInput($request->all());
        }

        $user = \App\User::where('email', $request->input('email'))->first();
        if(!empty($user)){
            if ($user->user_type == 'admin' || $user->user_type == 'admin_user') {

                $passwordResetCode  = new \App\PasswordResets;
                $passwordResetCode->token = $passwordResetCode->getResetPasswordCode();
                $passwordResetCode->email = $request->input('email') ;

                $passwordResetCode->save();
                
                $data = array(
                    'user'              =>  $user,
                    'forgotPasswordUrl' =>  $passwordResetCode->token,
                    'subject'           =>  'Password recovery email',
                    'email'             =>  $user->email
                );

                // Send the activation code through email

                $response = \App\libraries\MailHelper::sendEmail('auth.emails.password', $data);
                // \Mail::send('auth.emails.password', $data, function($m) use ($user)
                // {
                //     $m->to($user->email, $user->first_name . ' ' . $user->last_name);
                //     $m->subject('Password recovery email');
                // });

                return redirect()->back()->with('status', 'Password recovery email has been sent to your email address.');

            }else {
                return redirect()->back()->withErrors(['email' => "You are not authorized to access."]);
            }
        }else{
            return redirect()->back()->withErrors(['email' => "Email address not found"]);
        }
        
    }

    public function getPasswordResetForm(Request $request, $token = null) {
        if (!empty($token)) {

            $email = $request->input('email');

            $reset_password_code = \App\PasswordResets::where('token', $token)->first();

            if (!$reset_password_code) {
                return redirect('admin/forgot-password')->withErrors(["Your password reset link has expired"]);
            }

            return view('auth.reset')->with(compact('token', 'email'));
        }
        return redirect('admin/forgot-password');
    }

    public function postPasswordReset(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $passwordResetCode = $request->get('token');
        $reset_password_code = \App\PasswordResets::where('token', $passwordResetCode)->first();

        if(!$reset_password_code){
            return redirect('admin/forgot-password')->withErrors(["Your password reset link has expired"]);
        } else{
            $user = \App\User::where('email', $reset_password_code->email)->first();

            $user->password = \Hash::make($request->get('password'));
            $user->save();
            //Delete password reset data of this user
            \DB::table('password_resets')->where('email',$reset_password_code->email)->delete();

            \Session::flash('success', 'Your password reset successfully.');
            return redirect('admin/login');
        }
    }

}
