<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Admin\RegistrationArtistRequest;
use App\Http\Controllers\Controller;
use Mail;
use App\Track;
use App\Campaign;
use App\ArtistsProfile;
use Datatable;
use App\User;
use Illuminate\Support\Facades\Input;

class ArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Artists';
        $subscriptions = \Config::get('app.subscriptions');
        return view('admin.artists.index',compact('title'));
    }

    function getUsers()
    {
        $query = \DB::table('users')
                    ->where('user_type', '=', 'artist');

        // $subscription = \Input::get('subscription');
        // if (\Request::isMethod('post') && !empty($subscription))
        // {
        //     $query->where('users.subscription', '=', $subscription);
        // }
        $query->join('artists_profile', 'users.id', '=', 'artists_profile.user_id')
              ->select('users.id','users.name','artists_profile.souncloud_url','users.email', 'artists_profile.country', 'artists_profile.city','users.created_at','users.status','users.slug');


        return Datatable::query($query)
        ->addColumn('name', function($model) { return ucfirst($model->name); })
        ->addColumn('email', function($model) { return $model->email; })
        ->addColumn('location', function($model){
          return $model->country && $model->city ? $model->city.', '.$model->country : '-';
        })
        ->addColumn('souncloud_url', function($model){
            if (substr($model->souncloud_url, -1) == "/") {
                return '<a class="decoration-none" target="_blank" href="'.$model->souncloud_url.'tracks"><span class="label label-orangered">SoundCloud</span></a>';
            } else {
                return '<a class="decoration-none" target="_blank" href="'.$model->souncloud_url.'/tracks"><span class="label label-orangered">SoundCloud</span></a>';
            }
        })
        ->addColumn('created_at', function($model){
          return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));
        })
        ->addColumn('status', function($model) { 
          $status = 'Active';
          if($model->status == 0){
            $status = 'Inactive';  
          }
          return $status; 
        })
        ->addColumn('actions', function($model) {
        $editBtn = '<a href="javascript:void(0);" id="' . \URL::to('admin/artists/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#ArtistEditModal" title="Edit" class="ArtistEdit"><i class="fa fa-edit"></i></a> &nbsp;';

        $emailBtn = '<a href="javascript:void(0);" class="sendMailToArtist" id="' . \URL::to('admin/artists/sendmail/'.$model->id) . '"><i title="Send email" class="fa fa-envelope"></i></a> &nbsp;';

        // $showTrackBtn = '<a href="artists/campaigns/'.$model->id.'" class="showArtistCampaigns"><i title="Tracks" class="fa fa-music"></i></a> &nbsp;';

        $infoBtn = '<a href="' . \URL::to('admin/artists/'.$model->id) . '"><i title="Show info" class="fa fa-info-circle"></i></a> &nbsp';

        $deleteBtn = '<a href="javascript:void(0);" id="' . \URL::to('admin/artists/'.$model->id) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a> &nbsp;';
        if (env('STM_ARTIST_ACCOUNT') == $model->slug) {
           $deleteBtn = '';
        }
         return  $editBtn.$emailBtn.$infoBtn.$deleteBtn;

         // return  $editBtn.$emailBtn.$showTrackBtn.$infoBtn.$deleteBtn;
       })
        ->searchColumns('users.name','users.email')
        ->make();
    }

    function sendMail($id)
    {
        $user = \App\ArtistsProfile::where('user_id',$id)->first();
        return view('admin.artists.mail', compact('user'));
    }

    function sendmailtoArtist()
    {
        $id = \Input::get('user_id');
        $user = User::findOrFail($id);
        // $subject = \Input::get('subject');
        
        $data = array(
                    'username'      =>  $user->name,
                    'mail_message'  =>  \Input::get('message'),
                    'email'         =>  $user->email,
                    'subject'       =>  \Input::get('subject')
                );
        
        $response = \App\libraries\MailHelper::sendEmail('emails.artist_mail', $data);


        // Mail::send('emails.artist_mail', ['username' => $user->name, 'mail_message' => \Input::get('message')], function ($m) use ($user, $subject) {
        //   $m->to($user->email, $user->name)->subject($subject);
        // });

        \Session::flash('message','Mail has been successfully sent');
        return \Redirect::route('admin.artists.index');
    }
    
    public function getTracks($id){
        $data = Track::getArtistTracks($id);
        $title = 'Track List';
        return view('admin.artists.tracks', compact('data','title'));
    }

    //show artist campaigns list
    public function getTrackDemos($id){
        $user = ArtistsProfile::where('user_id', $id)->first();
        $title = $user->name;
        return view('admin.artists.campaigns', compact(['title','user']));
    }

    function getArtistTrackDemos($id){
        $query = \App\TrackDemo::where('user_id', $id);

        return Datatable::query($query)
            ->addColumn('track_name', function($model) { return '<a class="decoration-none trackPlay" data-track="'.$model->mp3_file.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name); })

            ->addColumn('type', function($model) { return ucfirst($model->type); })
            ->addColumn('status', function($model) { return ucfirst($model->status); })
            ->addColumn('created_at', function($model) {return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));})
            ->searchColumns('track_name', 'type', 'status')
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.artists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        
        $input = \Input::all();

        $input['user_type'] = 'artist';
        $input['status'] = '1';
        // $input['password'] = \Hash::make(\Input::get('password'));
        
        $user = User::create($input);

        $artist_profile = new \App\ArtistsProfile;

        $artist_profile->user_id = $user->id;
        $artist_profile->name = \Input::get('name');
        $artist_profile->save();

        $releaseTracks = new \App\ReleaseTracks;

        $releaseTracks->user_id = $user->id;
        $releaseTracks->save();

        \Session::flash('message','Artist has been created successfully');


        $passwordResetCode  = new \App\PasswordResets;
        $passwordResetCode->token = $passwordResetCode->getResetPasswordCode();
        $passwordResetCode->email = $input['email'] ;

        $passwordResetCode->save();
        
        $data = array(
            'user'              => \Input::get('name'),
            'forgotPasswordUrl' =>  $passwordResetCode->token,
            'email'             =>  $user->email,
            'subject'           =>  'SoreThumbMedia account activation'
        );
        $response = \App\libraries\MailHelper::sendEmail('emails.welcome', $data);

        // Send the activation code through email
        // \Mail::send('emails.welcome', $data, function($m) use ($user)
        // {
        //     $m->to($user->email, $user->first_name . ' ' . $user->last_name);
        //     $m->subject('SoreThumbMedia account activation');
        // });

        return \Redirect::route('admin.artists.index');

    }   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $socialActivities = \App\User::join('track_shares', 'users.id', '=', 'track_shares.artist_id')
                                    ->select('users.id',
                                             'track_shares.artist_id',
                                             'track_shares.share_type', 
                                             \DB::raw('sum(case when share_action = "follow" then 1 else 0 end) as follow_count'), 
                                             \DB::raw('sum(case when share_action = "tweet" then 1 else 0 end) as tweet_count'),
                                             \DB::raw('sum(case when share_action = "like" then 1 else 0 end) as like_count'),
                                             \DB::raw('sum(case when share_action = "comment" then 1 else 0 end) as comment_count'),
                                             \DB::raw('sum(case when share_action = "subscribe" then 1 else 0 end) as subscribe_count'),
                                             \DB::raw('sum(case when share_action = "share" then 1 else 0 end) as share_count'),
                                             \DB::raw('sum(case when share_action = "repost" then 1 else 0 end) as repost_count')
                                    )
                                    ->where('track_shares.artist_id', $id)
                                    ->groupBy('track_shares.share_type')
                                    ->groupBy('artist_id')
                                    ->get()
                                    ->toArray();

        $social_medias = array('facebook','soundcloud','twitter','youtube','instagram');
        $social = [];                                    
        foreach ($socialActivities as $activities) {            
            $social[$activities['share_type']] = ['like_count' => $activities['like_count'],
                                                  'follow_count' => $activities['follow_count'],
                                                  'tweet_count' => $activities['tweet_count'],
                                                  'comment_count' => $activities['comment_count'],
                                                  'subscribe_count' => $activities['subscribe_count'],
                                                  'share_count' => $activities['share_count'],
                                                  'repost_count' => $activities['repost_count']
                                                 ];
        }
        $diff = array_diff($social_medias, array_keys($social));

        $user = User::with(['artistprofile','lableRelease','artistGenres'=>function($q){
                                            $q->select('name');
                              },'artistLabelGenres' => function($q){
                                            $q->select('name');
                              }])
                              ->find($id);
        $title = ucfirst($user->artistprofile->name);
        if (is_null($user))
        {
            return \Redirect::route('admin.users.index');
            \Session::flash('error','Artist not found');
        }

        return view('admin.artists.detail', compact('user','title','social','diff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        
        if (is_null($user))
        {
            return \Redirect::route('admin.artists.index');
        }
        $subscriptions = \Config::get('app.subscriptions');
        
        return view('admin.artists.edit', compact('user','subscriptions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $user = User::findOrFail($id);
        $data = \Input::all();
        $password = \Input::get('password');
        if (!empty($password))
        {
            $data['password'] = \Hash::make($password);
            $this->passwordChangeMail($data, $password);
        }
        else
        {
            $data['password'] = $user->password;
        }
        $user->update($data);

        $artist = \App\ArtistsProfile::where('user_id',$user->id)->first();
        $artist->name = \Input::get('name');
        $artist->save();
        
        \Session::flash('message','Artist has been updated successfully');
        return \Redirect::route('admin.artists.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $artist = ArtistsProfile::where('user_id',$id)->first();
        if($artist)
          $artist->delete();

        $user = User::find($id);
        if($user)
          $user->delete();
        
        \Session::flash('message','Artist has been deleted successfully');
    }

    function passwordChangeMail($user, $password)
    {
        $data = array(
            'username'  =>  $user['name'],
            'pass'      =>  $password,
            'email'     =>  $user['email'],
            'subject'   =>  'Password changed'
        );
        $response = \App\libraries\MailHelper::sendEmail('emails.changed_pass', $data);

        // $subject = "Password changed";
        // Mail::send('emails.changed_pass', ['username' => $user['name'], 'pass'=> $password], function ($m) use ($user, $subject) {
        //   $m->to($user['email'], $user['name'])->subject($subject);
        // });
    }


    public function getArtistList(Request $request){ 
      $term = $request->get('term');
      $artistList = \App\User::where('user_type', 'artist')
                              ->where('name', 'like', '%'.$term.'%')
                              ->select('name','id')
                              ->get();

      return response()->json(compact('artistList'));
    }
}
