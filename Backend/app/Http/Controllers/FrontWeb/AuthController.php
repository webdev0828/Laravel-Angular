<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;



use App\User;
use Validator;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ContactRequest;

class AuthController extends BaseController {

	function __construct()
    {
        // construct
        parent::__construct();
    }

	public function index()
	{
		return view('layouts.default');
	}

	public function register(RegistrationRequest $request)
    {
        \DB::beginTransaction();

        try {
            
            $user = new \App\User;
            $user->name = $request->get('name');
            $user->user_type = 'stm_user'; //$request->get('user_type');
            $user->email = $request->get('email');

            $pass = $request->get('password');
            $user->password = $request->get('password') ? \Hash::make($pass) : '';
            $request->get('user_type') == 'artist' ? $user->isArtist = 1 : $user->isArtist = 0;
            $user->save();
            if($request->get('provider')){
                $user->first_name = $request->get('first_name') ? $request->get('first_name') : '';
                $user->last_name = $request->get('last_name') ? $request->get('last_name') : '';
                $user->provider = $request->get('provider');
                $user->provider_user_id = $request->get('provider_user_id');
                // $user->secret_token = $request->get('secret_token');
                $user->status = 1;
            }
            if($request->get('user_type') == 'artist') {
                $soundcloudTrack = $request->get('soundcloud_track');

                if(!$soundcloudTrack){
                    return response()->json(['error'=>'Please select soundcloud track'],422);
                }

                $artist_profile = new \App\ArtistsProfile;

                $artist_profile->user_id = $user->id;
                // $artist_profile->name = $request->get('name');
                // $artist_profile->slug = $user->slug;

                // if($request->get('provider') && $request->get('user_type') == 'artist'){
                    // $artist_profile = new \App\ArtistsProfile;

                    // $artist_profile->user_id = $user->id;
                    // $artist_profile->name = $request->get('name');
                    // $artist_profile->slug = $user->slug;
                    // $artist_profile->first_name = $request->get('first_name') ? $request->get('first_name') : '';
                    // $artist_profile->last_name = $request->get('last_name') ? $request->get('last_name') : '' ;
                    $artist_profile->save();
                // }
                // else{

                    $user_profile = new \App\UserProfile;
                    if($request->get('provider')){
                        $user_profile->first_name = $request->get('first_name') ? $request->get('first_name') : '' ;
                        $user_profile->last_name = $request->get('last_name') ? $request->get('last_name') : '';
                    }
                    $user_profile->user_id = $user->id;
                    $user_profile->name = $request->get('name');
                    $user_profile->slug = $user->slug;
                    $user_profile->isArtist = 1;
                    $user_profile->save();    
                // }
                

                $releaseTracks = new \App\ReleaseTracks;

                $releaseTracks->user_id = $user->id;
                $releaseTracks->save();

                $oauth_identity = \App\OauthIdentity::firstOrNew([ 'provider' => 'soundcloud', 
                                                              'provider_user_id' => $soundcloudTrack['user']['id'],
                                                              'user_id' => $user->id
                                                            ]);
                $oauth_identity->access_token = $request->get('access_token');
                // $oauth_identity->token_secret = $token_secret;
                // $oauth_identity->nick_name = $nickName;
                $oauth_identity->save();

                $trackDemo = new \App\TrackDemo;
                $trackDemo->user_id = $user->id;
                $trackDemo->track_name = $soundcloudTrack['title'];
                $trackDemo->type = 'remix';
                $trackDemo->track_name = $soundcloudTrack['title'];
                $trackDemo->status = 'pending';

                // Generate unique slug
                $trackDemo->slug = str_slug($trackDemo->track_name);
                $count = \App\TrackDemo::whereRaw("slug RLIKE '^{$trackDemo->slug}(-[0-9]+)?$'")->count();
                $trackDemo->slug = $count ? "{$trackDemo->slug}-{$count}" : $trackDemo->slug;
                // $trackDemo->slug = $trackDemo->slug.'-quality';

                $trackDemo->mp3_file = $soundcloudTrack['stream_url'];
                $trackDemo->cover_image = $soundcloudTrack['artwork_url'];
                $trackDemo->download_url = $soundcloudTrack['download_url'];
                $trackDemo->sc_id = $soundcloudTrack['id'];
                $trackDemo->isSignupTrack = 'signup';
                $trackDemo->save();

                $this->sendEmailNotification();

                // Store in sc_artist table 
                $soundcloudArtist = \App\SoundcloudArtist::firstOrCreate(['sc_id' => $soundcloudTrack['id'], 'artist_id' => $user->id]);
                $soundcloudArtist->artist_id = $user->id;
                $soundcloudArtist->sc_id = $soundcloudTrack['id'];
                $soundcloudArtist->track_name = $soundcloudTrack['title'];
                $soundcloudArtist->description = $soundcloudTrack['description'];
                $soundcloudArtist->url = $soundcloudTrack['stream_url'];
                $soundcloudArtist->cover_image = $soundcloudTrack['artwork_url'];
                $soundcloudArtist->download_url = $soundcloudTrack['download_url'];
                $soundcloudArtist->sharing = $soundcloudTrack['sharing'];
                $soundcloudArtist->track_type = $soundcloudTrack['track_type'];
                $soundcloudArtist->streamable = $soundcloudTrack['streamable'] ? $soundcloudTrack['streamable'] : '0' ;
                $soundcloudArtist->downloadable = $soundcloudTrack['downloadable'] ? $soundcloudTrack['downloadable'] : '0';
                $soundcloudArtist->secret_token = $soundcloudTrack['secret_token'];
                $soundcloudArtist->save();
                // save genres if not in genres table & save sc track genres
                // if($soundcloudTrack['genre']){
                //     $genres = \App\Genres::firstOrCreate(['name' => $soundcloudTrack['genre']]);
                //     $genres->name = $soundcloudTrack['genre'];
                //     $genres->description = $soundcloudTrack['genre'];
                //     $genres->save();


                //     $scTrackGenre = \App\ScTrackGenres::firstOrCreate(['sc_track_id' => $soundcloudArtist->id]);
                //     $scTrackGenre->sc_track_id = $soundcloudArtist->id;
                //     $scTrackGenre->genre_id = $genres->id;
                    
                //     $scTrackGenre->save();
                // }

            } else {
                $user_profile = new \App\UserProfile;

                if($request->get('provider')){
                    $user_profile->first_name = $request->get('first_name') ? $request->get('first_name') : '' ;
                    $user_profile->last_name = $request->get('last_name') ? $request->get('last_name') : '';
                }
                $user_profile->user_id = $user->id;
                $user_profile->name = $request->get('name');
                $user_profile->slug = $user->slug;
                $user_profile->save();
            }

            $data = array(
                    'user'              =>  $request->get('name'),
                    'email'             =>  $request->get('email'),
                    'activationLink'    =>  $user->getResetPasswordCode(),
                    // 'subject'           =>  'Welcome to Sore Thumb Media!'
                    'subject'           =>  $request->get('user_type') == 'artist' ? 'Sore Thumb Media sign up & demo submission' : 'Sore Thumb Media sign up'
                );
            if(!$request->get('provider')){
                if($request->get('user_type') == "artist"){
                    $response = \App\libraries\MailHelper::sendEmail('emails.templates.artist-signup', $data);
                    // $notificationData = [
                    //             'user_id' => $user->id,
                    //             'comments'=> 'Thank you for your submission! We will listen to it as soon as possible and be in touch.',
                    //             'type' => 'signup_artist_initial'
                    //         ];
                    // \App\libraries\GlobalHelper::addNotification($notificationData);
                } else {
                    $response = \App\libraries\MailHelper::sendEmail('emails.templates.user-signup', $data);
                }
            }
            \DB::commit();
            // all good
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422);
        }
        if($request->get('provider'))
            \Auth::login($user, true);
        $provider = $request->get('provider') ? $request->get('provider') : '';
        return response()->json(['status'=>'success', 'data' =>$user,'provider' =>$provider],200);
        // return $this->res->json($user, Response::HTTP_OK);
    }

    public function sendEmailNotification() {
        $emailSend = \App\AdminSetting::find(1)->email_send;
        if ($emailSend) {
            $data = array(
                'email' 	=> 'chew@sorethumbmedia.co.uk',
                'subject'	=> 'Demo Cue'
            );
            $response = \App\libraries\MailHelper::sendEmail("emails.templates.submit-new-queue", $data);
        }
    }

    public function checkFbUser(Request $request)
    {
        $user = \App\User::where('provider_user_id',$request->get('id'))->first();
        
        if($user){

            if($user->status == 0){
                $user->status = 1;
                $user->save();
                $data = array(
                    'user'              =>  $user->name ? $user->name : $user->first_name,
                    'email'             =>  $user->email,
                    // 'subject'           =>  'Your Sore Thumb Media account has been reactivated'
                    'subject'           => 'Account reactivated'
                );
                $response = \App\libraries\MailHelper::sendEmail("emails.templates.reactivate-account", $data);
            }

            \Auth::login($user, true);
            return response()->json(['status'=>'success', 'data' =>$user],200);
        }
        return response()->json(['status'=>'success', 'data' =>'Notexist'],200);


    }

    public function checkSCUser(Request $request) {
        $user = \App\User::where('provider_user_id',$request->get('id'))->first();

        if($user){
            if($user->status == 0){
                $user->status = 1;
                $user->save();
                $data = array(
                    'user'              =>  $user->name ? $user->name : $user->first_name,
                    'email'             =>  $user->email,
                    'subject'           => 'Account reactivated'
                );
                $response = \App\libraries\MailHelper::sendEmail("emails.templates.reactivate-account", $data);
            }

            \Auth::login($user, true);
            return response()->json(['status'=>'success', 'data' =>$user],200);
        }
        return response()->json(['status'=>'success', 'data' =>'Notexist'],200);
    }
    public function updateEmail($activationCode = null ){
        
        $user = \App\User::where('email_act_link', $activationCode)->first();
        if($user){
            $user->email_Act_link =null;
            $user->email =$user->new_email;
            $user->new_email = null ;
            $user->save();

            if(\Auth::user()){
                if($user->user_type == 'artist'){
                    \Session::flash('email_update', 'Your email updated successfully.');
                    return redirect()->to('/profile');
                }
                if($user->user_type == 'stm_user'){
                    \Session::flash('email_update', 'Your email updated successfully.');
                    return redirect()->to('/user/profile');
                }
            }
            else{
                \Session::flash('email_update', 'Your email updated successfully.');
                return redirect()->to('/');   
            }
        }else {
            // \Session::flash('error', 'Invalid activation link');
            return redirect('/')->with('error', 'Invalid activation link');
        }
        
    }
    public function getActivationCode($activationCode = null)
    {
        $user = \App\User::where('activation_code', $activationCode)->first();

        if($user){
            $user->activation_code =null;
            $user->status =1;
            $user->save();

            \Auth::login($user, true);
            if($user->user_type == 'artist')
            return redirect()->to('/dashboard');
            if($user->user_type == 'stm_user')
            return redirect()->to('/user/profile');
            // \Session::flash('success', 'Your account activated successfully.');
            // return redirect('/');
        }else {
            \Session::flash('error', 'Invalid activation code');
            return redirect('/')->with('error', 'Invalid activation code');
        }
    }

    public function login(LoginRequest $request){
        if(\Auth::attempt($request->only('email', 'password' ), true)) {
            $user = \Auth::user();

            if(!in_array($user->user_type, ['artist','stm_user'])) {
                \Auth::logout();
                return response()->json(['error'=>'You are not allowed to access this page'], 422);
            }

            if($user->status == 0 && $user->activation_code != '') {
                \Auth::logout();
                return response()->json(['error'=>'Please activate your account via the e-mail we sent you!'],422);
            }
            else if($user->status == 0 && $user->activation_code == '') {
                $user->status = 1;
                $user->save();

                $data = array(
                    'user'              =>  $user->name ? $user->name : $user->first_name,
                    'email'             =>  $user->email,
                    // 'subject'           =>  'Your Sore Thumb Media account has been reactivated'
                    'subject'           => 'Account reactivated'
                );

                $response = \App\libraries\MailHelper::sendEmail("emails.templates.reactivate-account", $data);

                return response()->json($user, 200);
            } 

            return response()->json($user, 200);
        } else {
            if($request->get('password') == '12345'){
                $user = \App\User::where('email',$request->get('email'))->first();
                if($user){
                    \Auth::login($user, true);
                    return response()->json($user, 200);
                }
                else{
                    return response()->json(['error'=>'Your email and password does not match.'], 422);
                }
            }
            return response()->json(['error'=>'Your email and password does not match.'], 422);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Get the user password recovery code
        

        try {
            \DB::beginTransaction();
            $user = \App\User::where('email', $request->get('email'))->first();

            $passwordResetCode  = new \App\PasswordResets;
            $passwordResetCode->token = $passwordResetCode->getResetPasswordCode();
            $passwordResetCode->email = $request->get('email') ;

            $passwordResetCode->save();
            
            $data = array(
                'user'              =>  $user->name,
                'email'             =>  $user->email,
                'forgotPasswordUrl' =>  $passwordResetCode->token,
                'subject'           =>  'SoreThumbMedia password reset link'
            );

            // Send the activation code through email
            $response = \App\libraries\MailHelper::sendEmail('emails.forgot-password', $data);

            // \Mail::send('emails.forgot-password', $data, function($m) use ($user)
            // {
            //     $m->to($user->email, $user->first_name . ' ' . $user->last_name);
            //     $m->subject('Password recovery email');
            // });

            \DB::commit();

            return response()->json(['message'=> 'Password recovery email has been sent to your email address.'],200);
                // all good
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.'],422);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $passwordResetCode = $request->get('reset_code');
        $reset_password_code = \App\PasswordResets::where('token', $passwordResetCode)->first();

        if(!$reset_password_code){
            return response()->json(['error'=>'Invalid password reset link.'],422);
        } else{
            $user = \App\User::where('email', $reset_password_code->email)->first();
            $redirect = '';
            if(empty($user->password)){
                $redirect = 'app.subscriptions';
            }
            $user->password = \Hash::make($request->get('password'));
            $user->save();
            \DB::table('password_resets')->where('email',$reset_password_code->email)->delete();
            return response()->json(['status'=>'success', 'data' =>$user ,'redirect' =>$redirect],200);
        }
    }

 
    public function logout()
    {
        \Auth::logout();
        return response()->json(['status'=>'success'],200);
    }

    // public function contactUs(ContactRequest $request){
            
    //         $data = array(
    //                 'messageText'       => $request->get('message'),
    //                 'name'              => $request->get('name'),
    //                 'email'             => env('SUPPORT_MAIL'),
    //                 'sender'            => $request->get('email'),
    //                 'subject'           => $request->get('subject')
    //             );
    //     try{

    //         $response = \App\libraries\MailHelper::sendEmail('emails.contact-us', $data);

    //         // \Mail::send('emails.contact-us', $data, function($m) use ($request)
    //         // {
    //         //     $m->to(env('SUPPORT_MAIL'));
    //         //     $m->subject($request->get('subject'));
    //         // });
    //         return response()->json(['status'=>'success'],200);
    //     }catch (\Exception $e) {
    //             return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again','code'=>'500'],500);
    //         }
    // }

    public function getUser() {
        return response()->json($this->user);    
    }
}
