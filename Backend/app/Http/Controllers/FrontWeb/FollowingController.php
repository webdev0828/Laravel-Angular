<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FollowingController extends BaseController {

	function __construct()
    {
        // construct
        parent::__construct();
    }


    public function FollowArtist(Request $request){
    	$artist_slug = $request->get('slug');
    	$following = new \App\Follower;
    	$user = $this->user;
        if($user){
            $artist = \App\ArtistsProfile::where('slug',$artist_slug)->first();
            $following->user_id = $user->id;
            $following->follower_id = $artist->user_id;
            $following->save();
            $following->name = $artist->name;

            $artist_id = \App\User::where('id',$artist->user_id)->first();
            if($artist_id){
                $data = array(
                    'user'              =>  $user,
                    'artist'            =>  $artist->name ? $artist->name : $artist->first_name,
                    'email'             =>  $artist_id->email,
                    'subject'           =>  $user->name ? $user->name.' followed you' : $user->first_name.' followed you'
                );
                if($artist_id->email_notification == 1){
                    $response = \App\libraries\MailHelper::sendEmail("emails.templates.artist-followed", $data);    
                }
                
                $user_name = $user->name ? $user->name : $user->first_name;
                $artist_name = $artist->name ? $artist->name : $artist->first_name;
                if($artist_id->web_notification == 1){
                    $followingLink = $user->user_type == 'artist' ? url('/'.$user->slug).'/tracks' : url('/user/'.$user->slug).'/favourites';
                    $notificationData = [
                                    'user_id' => $artist_id->id,
                                    'comments'=> '<a href="'.$followingLink.'">'.$user_name.'</a> started following you',//.$artist_name.'</a>',
                                    'type' => 'followed'
                                ];
                    \App\libraries\GlobalHelper::addNotification($notificationData);
                }

            }
            return response()->json(['status'=>'success','data'=>$following],200);
        }
        return response()->json(['status'=>'error','message'=>'Please login to follow artist.'],422);
    }

    public function UnfollowArtist(Request $request){
        $user = $this->user;
        if($user){
            $artist = \App\ArtistsProfile::where('slug',$request->get('slug'))->first();
            $following = \App\Follower::where('user_id',$user->id)
                                      ->where('follower_id',$artist->user_id)
                                      ->delete();                            
            if($following){
                $activity = \App\Activity::where('user_id',$user->id)
                                         ->where('sender_id',$artist->user_id)
                                         ->where('object','follow')
                                         ->delete();
                $data['id'] = $artist->user_id;
                $data['name'] = $artist->name;
                return response()->json(['status'=>'success','data'=>$data],200);
            } 
        }
        return response()->json(['status'=>'error','message'=>'Please login to follow artist.'],422);
    }


    public function getFollowing(Request $request){
        $slug = $request->get('slug');
        $user = \App\User::where('slug', $slug)->first();

        $followerIds = \App\Follower::where('user_id', $user->id)->orderBy('created_at','DESC')->lists('follower_id');

        $key = array_search($user->id, $followerIds->toArray());
        if($key){
            unset($followerIds[$key]);
        }
         $stringIds = implode(",", $followerIds->toArray());
        $followingUser = \App\ArtistsProfile::whereIn('user_id', $followerIds)->orderBy(\DB::raw("  FIELD(user_id, ".$stringIds.")"))
                                            ->paginate(10);


        // $followingUser = \App\ArtistsProfile::whereIn('user_id', $followerIds)->orderBy('user_id',$followerIds)
        //                                     ->paginate(10);

        $data = array(
                    'followingUser' => $followingUser->items(), 
                    'current_page' =>  $followingUser->currentPage(),
                    'last_page' =>  $followingUser->lastPage()
                );

        return response()->json(['status'=>'success','data'=>$data],200);
    }

    public function getMoreFollowing(Request $request){
        $slug = $request->get('slug');
        $limit = $request->get('limit',10);
        $user = \App\User::where('slug', $slug)->first();

        $followerIds = \App\Follower::where('user_id', $user->id)->orderBy('created_at','DESC')->lists('follower_id');
        $key = array_search($user->id, $followerIds->toArray());
        if($key){
            unset($followerIds[$key]);
        }
         $stringIds = implode(",", $followerIds->toArray());
        $followingUser = \App\ArtistsProfile::whereIn('user_id', $followerIds)->orderBy(\DB::raw("  FIELD(user_id, ".$stringIds.")"))
                                            ->paginate($limit);


        // $followingUser = \App\ArtistsProfile::whereIn('user_id', $followerIds)->orderBy('user_id',$followerIds)
        //                                     ->paginate($limit);

        $data = array(
                    'followingUser' => $followingUser->items(), 
                    'current_page' =>  $followingUser->currentPage(),
                    'last_page' =>  $followingUser->lastPage()
                );

        return response()->json(['status'=>'success','data'=>$data],200);
    }



    public function getFans(Request $request){
        $slug = $request->get('slug');
        $user = \App\User::where('slug', $slug)->first();

        $fanIds = \App\Follower::where('follower_id', $user->id)->orderBy('created_at','DESC')->lists('user_id');

        //remove current user id from list
        $key = array_search($user->id, $fanIds->toArray());
        if($key){
            unset($fanIds[$key]);
        }
         $stringIds = implode(",", $fanIds->toArray());
         $fanUser = \App\User::select('name', 'user_type', 'avatar', 'slug', 'id as user_id')->whereIn('id', $fanIds)->orderBy(\DB::raw("  FIELD(id, ".$stringIds.")"))
                                        ->paginate(10);
        // $fanUser = \App\User::select('name', 'user_type', 'avatar', 'slug', 'id as user_id')->whereIn('id', $fanIds)->orderBy('id',$fanIds)
        //                                 ->paginate(10);

        $data = array(
                    'fanUser' => $fanUser->items(), 
                    'current_page' =>  $fanUser->currentPage(),
                    'last_page' =>  $fanUser->lastPage()
                );

        return response()->json(['status'=>'success','data'=>$data],200);
    }
    

    public function getMoreFans(Request $request){
        $slug = $request->get('slug');
        // $limit = $request->get('limit',10);
        $user = \App\User::where('slug', $slug)->first();

        $fanIds = \App\Follower::where('follower_id', $user->id)->orderBy('created_at','DESC')->lists('user_id');
        $key = array_search($user->id, $fanIds->toArray());
        if($key){
            unset($fanIds[$key]);
        }
        $stringIds = implode(",", $fanIds->toArray());
        $fanUser = \App\User::select('name', 'user_type', 'avatar', 'slug', 'id as user_id')->whereIn('id', $fanIds)->orderBy(\DB::raw("  FIELD(id, ".$stringIds.")"))
                                        ->paginate(10);
        // $fanUser = \App\User::select('name', 'user_type', 'avatar', 'slug', 'id as user_id')->whereIn('id', $fanIds)->orderBy('id',$fanIds)
        //                                 ->paginate(10);

        $data = array(
                    'fanUser' => $fanUser->items(), 
                    'current_page' =>  $fanUser->currentPage(),
                    'last_page' =>  $fanUser->lastPage()
                );

        return response()->json(['status'=>'success','data'=>$data],200);
    }

}