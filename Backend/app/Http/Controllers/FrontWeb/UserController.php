<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PlanBilling;
use App\Http\Requests\Auth\UpdateArtistRequest;
use App\Http\Requests\Auth\UpdateProfileImageRequest;

class UserController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }
    
	public function getProfile($slug){
        
        $user = \App\User::where('slug', $slug)->first();

        if($user) {
            $userProfile = \App\UserProfile::firstOrCreate(['user_id' => $user->id, 'slug' => $user->slug]);
            $userProfile->user_type = $user->user_type;
            $userProfile->genres = $user->genres;

            $userProfile->email_notification = $user->email_notification;
            $userProfile->web_notification = $user->web_notification;

            $followingCount = \App\Follower::where('user_id', $user->id)->count();

            $favoriteTrackCount = \App\Favourite::where('user_id', $user->id)->count();
            $favoriteVideoCount = \App\FavouriteVideo::where('user_id', $user->id)->count();
            $favouriteCount = $favoriteTrackCount + $favoriteVideoCount;

            $followerIds = \App\Follower::where('user_id',$user->id)->lists('follower_id');
            $userProfile['followers'] = \App\User::whereIn('id', $followerIds)->get();
            $ids = $followerIds;
            $ids[] =  $user->id;
            $userProfile['activities'] = \App\Activity::where('user_id', $user->id)
                                                ->with(['owner','track','userProfile','follower','following','favouriteCampaign','favouriteRemix','favouriteVideo','downloadCampaign','downloadRemix','downloadVideo','downloadTrack'])
                                                ->where('object_id','!=',$user->id)
                                                ->orderBy('created_at','desc')
                                                ->paginate(10);

            $userProfile['activities'] = array(
                    'data' => $userProfile['activities']->items(), 
                    'current_page' =>  $userProfile['activities']->currentPage(),
                    'last_page' =>  $userProfile['activities']->lastPage()
                );                                                
            $countries =   config('constants.countries');
            if($countries){
                foreach ($countries as $key => $value) {
                    if($value['name'] == $userProfile->country)
                        $userProfile->country_code = $value['alpha2'];
                }
            }
            return response()->json(['status'=>'success','data'=>$userProfile, 'followingCount' => $followingCount, 'favouriteCount' => $favouriteCount],200);
        }
        
        return response()->json(['status'=>'error','message'=>'User not found '], 422);
    }

    public function getMoreActivities(Request $request){
        $user = $this->user;
        $limit = $request->get('limit',10);
        $artist['activities'] = \App\Activity::where('user_id', $user->id)
                                            ->with(['owner','track','profile','follower','following'])
                                            ->orderBy('created_at','desc')
                                            ->paginate($limit);
        $artist['activities'] = array(
                    'data' => $artist['activities']->items(), 
                    'current_page' =>  $artist['activities']->currentPage(),
                    'last_page' =>  $artist['activities']->lastPage()
                );                                             
        return response()->json(['status'=>'success','data'=>$artist],200);                                            
    }

    public function updateProfile(UpdateArtistRequest $request){

        $user = $this->user;
        
        if($user){
            $userProfile = \App\UserProfile::where('user_id',$user->id)->first();
            $userProfile->name          = $request->get('name');
            $userProfile->first_name    = $request->get('first_name');
            $userProfile->last_name     = $request->get('last_name');
            // $userProfile->website       = $request->get('website');
            $userProfile->country       = $request->get('country');
            // $userProfile->city          = $request->get('city');
            $town = explode(",",$request->get('city'));
            $userProfile->city = $town[0];
            $userProfile->souncloud_url = $request->get('souncloud_url' , '');
            $userProfile->facebook_url  = $request->get('facebook_url' , '');
            $userProfile->youtube_url   = $request->get('youtube_url' , '');
            $userProfile->twitter_url   = $request->get('twitter_url' , '');
            $userProfile->instagram_url = $request->get('instagram_url' , '');
            $userProfile->save();

            // update user information like name , first_name, last_name, password
            $user->name = $request->get('name');
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');

            // $genres = $request->get('genresIds');
            
            // if($genres){
            //     $genres = explode(",",$request->get('genresIds'));
            //     $user->genres()->sync($genres);
            // }
            // else{
            //     $user->genres()->sync([]);
            // }
            // $userProfile['genres'] = $user->genres;

            if($request->get('password')){
                $user->password = \Hash::make($request->get('password'));
            }
            
            $user->email_notification = $request->get('email_notification', $user->email_notification);
            $user->web_notification = $request->get('web_notification', $user->web_notification);
            $user->save();

            $userProfile->email_notification = $user->email_notification;
            $userProfile->web_notification = $user->web_notification;

            return response()->json(['status'=>'success', 'message'=> 'Your profile updated successfully ','data' =>$userProfile],200);
        }
        return response()->json(['status'=>'error','message'=>'Artist not found '], 422); 
    }   
    
    public function updateBio(Request $request){
        $user = $this->user;
        if($user){
             $userprofile = \App\UserProfile::where('user_id',$user->id)->first();
            //handle
            $bioData =$request->get('bio');
            $bioData = str_replace("<div>",'<br/>', $bioData);
            $bioData = str_replace("<br></div>",'', $bioData);
            $bioData = str_replace("</div>",'', $bioData);

            $userprofile->bio = $bioData == "<br>" ? '' : $bioData;
            // $userprofile->bio = $bioData;//$request->get('bio');
            $userprofile->save();
            return response()->json(['status'=>'success','data'=>$userprofile],200);
        }
        return response()->json(['status'=>'error','message'=>'User not found '], 422);
    }

    public function updateProfileImage(UpdateProfileImageRequest $request)
    {
        $user = $this->user;

        if($user) {
            /*if($request->hasFile('file'))
            {
                $file = $request->file('file');
                $destinationPath = public_path('uploads/user/profiles');
                $fileName = \App\libraries\GlobalHelper::uploadImage($file,$destinationPath);
                $filePath = 'uploads/user/profiles'.'/'.$fileName;
                
                $userprofile = \App\UserProfile::where('user_id',$user->id)->first();
                $userprofile->avatar = $filePath;
                $userprofile->save();

                // update user information avatar
                $user->avatar = $filePath;
                $user->save();
                return response()->json(['status'=>'success','data'=>$userprofile],200);   
            } else {
                return response()->json(['status'=>'error','message'=>'File not found '], 422);   
            }*/

            if ($request['file']) {
                $data = $request['file'];
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);

                $fileName = strtolower(rand(11111,99999).'.jpg');
                $filePath = 'uploads/user/profiles/'.$fileName;
                file_put_contents($filePath, $data);

                $userprofile = \App\UserProfile::where('user_id',$user->id)->first();
                $userprofile->avatar = $filePath;
                $userprofile->save();

                // update user information avatar
                $user->avatar = $filePath;
                $user->save();
                return response()->json(['status'=>'success','data'=>$userprofile],200);
            } else {
                return response()->json(['status'=>'error','message'=>'File not found '], 422);
            }
        }
        return response()->json(['status'=>'error','message'=>'User not found '], 422);
    }

    public function updateBannerImage(Request $request)
    {   
        $user = $this->user;
        if($user) {
            /*$inputs = $request->all();
            if($request->hasFile('file'))
            {
                $file = $request->file('file');
                $destinationPath = public_path('frontweb/images/user/banners');
                $fileName = \App\libraries\GlobalHelper::uploadImage($file,$destinationPath);
                $filePath = 'frontweb/images/user/banners'.'/'.$fileName;
               
                $userprofile = \App\UserProfile::where('user_id',$user->id)->first();
                $userprofile->cover = $filePath;
                $userprofile->save();
                return response()->json(['status'=>'success','data'=>$userprofile],200);   
            }
            return response()->json(['status'=>'error','message'=>'File not found '], 422);*/

            if ($request['file']) {
                $data = $request['file'];
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);

                $fileName = strtolower(rand(11111,99999).'.jpg');
                $filePath = 'frontweb/images/user/banners/'.$fileName;
                file_put_contents($filePath, $data);

                $userprofile = \App\UserProfile::where('user_id',$user->id)->first();
                $userprofile->cover = $filePath;
                $userprofile->save();

                return response()->json(['status'=>'success','data'=>$userprofile],200);
            } else {
                return response()->json(['status'=>'error','message'=>'File not found '], 422);
            }
        }
        return response()->json(['status'=>'error','message'=>'User not found '], 422);   
    }

    public function destroy()
    {
        $user = $this->user;
        if($user){
            $user->status = '0';
            $user->save();
        }
        \Auth::logout();
        // $userProfile = \App\UserProfile::where('user_id',$user->id)->first();
        // if($userProfile)
        //   $userProfile->delete();

        // if($user)
        //   $user->delete();
        
        return response()->json(['status'=>'success'], 200);
    }

    public function selectBannerImage(Request $request) {
        $fileUrl = $request->get('file');
        if($fileUrl) {
            $fileInfo = explode('.', $fileUrl);
            $extension = end($fileInfo);
            $filename = strtolower(date("dmYhis").'.'.$extension);
            $destinationPath = public_path('frontweb/images/user/banners').'/'.$filename;
            $filename = 'frontweb/images/user/banners'.'/'.$filename;

            $user = $this->user;
            $userprofile = \App\UserProfile::where('user_id',$user->id)->first();
            $userprofile->cover = $filename;
            $userprofile->save();
            @copy($fileUrl,$destinationPath);

            return response()->json(['status'=>'success','data'=>$userprofile->cover],200);
        }
        return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again.','code'=>'500'],422); 
    }

}