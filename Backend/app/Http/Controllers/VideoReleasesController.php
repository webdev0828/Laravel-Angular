<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests; 
use Datatable;
use Validator;
use App\StmVideoRelease;

class VideoReleasesController extends Controller
{
    public function index()
    {
        $title = 'Music videos';
        $genresList = \App\Genres::whereNull('parent_id')->lists('name','id')->toArray();
        return view('admin.releases_video.index',compact('title', 'genresList'));
    }

    public function getNewReleases(Request $request){
        $query = StmVideoRelease::join('users', 'users.id', '=', 'stm_video_releases.artist_id')
                                ->with(array('campaign'=>function($q){
                                        $q->select('id','track_name');
                                    }
                                ))
                                ->select('stm_video_releases.*', 'users.name');

        if($request->get('artist') && !empty($request->get('artist'))) {
            $query = $query->where('users.id', $request->get('artist'));
        }

        if($request->get('genre') && !empty($request->get('genre'))) {
            $query = $query->join('campaign_genres', 'stm_video_releases.track_id', '=', 'campaign_genres.campaign_id')->where('campaign_genres.genre_id', $request->get('genre'));
        }

        return Datatable::query($query)
        ->addColumn('track_name', function($model) {
            return '<a class="decoration-none videoPlay" data-toggle="modal" data-target="#videoReleasesPlayer" data-track="'.$model->url.'" data-title="'.$model->campaign->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->campaign->track_name);
        })

        ->addColumn('name', function($model) {
            return $model->name;
        })

        ->addColumn('genres', function($model) { 
            return isset($model->campaign->track_genres[0]) ? $model->campaign->track_genres[0]->name : '';
        })
        
        ->addColumn('created_at', function($model) {
            return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));
        })
        
        ->addColumn('actions', function($model) {

            $type = \App\TopItems::where('object_id', $model->id)->select('object_type')->get();
            $everyThing = 0;
            $spootLight = 0;
            foreach ($type as $value) {
                if($value->object_type == "admin_video"){
                    $everyThing = 1;
                }
                if($value->object_type == "spotlight_video"){
                    $spootLight = 1;
                }             
            }


            // $topitem = $everyThing ? '<span class="top-video label label-info" id="InTop" title="Top video" data-cmd="admin_video" data-id="'.$model->id.'">Top video</span>&nbsp;&nbsp;' : '<span class="top-video label background-color" id="OutTop" title="Top video" data-cmd="admin_video" data-id="'.$model->id.'">Top video</span>&nbsp;&nbsp;'; 
            $spotlight = $spootLight ? '<span class="top-video label label-info" id="InTop" title="spotlight video" data-cmd="spotlight_video" data-id="'.$model->id.'">SpotLight</span>&nbsp;&nbsp;' : '<span class="top-video label background-color" id="OutTop" title="spotlight video" data-cmd="spotlight_video" data-id="'.$model->id.'">SpotLight</span>&nbsp;&nbsp;'; 
            
            $otherAction = '<a href="'.\URL::to('admin/music_video_release/'.$model->id.'/edit') . '"><i class="fa fa-edit"></i></a>&nbsp;
                            <a href="' . \URL::to('admin/music_video_release/'.$model->id.'/delete') . '" onclick="return confirmDelete();"><i class="fa fa-trash"></i></a>';

            return $spotlight . $otherAction;
            // return $topitem. $spotlight . $otherAction;
                
        })
        ->searchColumns('track_name')
        ->orderColumns('created_at')
        ->make();
    }

    public function create(){
        $title = 'Add new';
        // $moods = \App\Mood::lists('name', 'id')->toArray();
        // $genres = \App\Genres::where('parent_id',null)->lists('name', 'id')->toArray();
        $artists = \App\User::where('user_type','artist')->where('status', 1)->lists('name', 'id')->toArray();
        // $sub_genres = [];
        // return view('admin.releases_video.create',compact(['title', 'moods', 'genres', 'sub_genres','artists']));
        return view('admin.releases_video.create',compact(['title', 'artists']));
    }

    public function getSubGenres(Request $request){

        $genre = $request->get('genreId'); //@explode(',', $genreIds); 
        $sub_genres = \App\Genres::where('parent_id', $genre)->lists('name', 'id')->toArray();
        return response($sub_genres);
    }

    public function store(Request $request){
        
        $validator = Validator::make($request->all(), [
            'track_id' => 'required',
            'url' => 'required|url',
            'artist_id' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect('admin/music_video_release/create')
                        ->withInput()
                        ->withErrors($validator);
        }

        $stmVideoRelease = new StmVideoRelease;
        $stmVideoRelease->track_id = $request->get('track_id');
        $stmVideoRelease->url = $request->get('url');
        $stmVideoRelease->artist_id = $request->get('artist_id');
        // $stmVideoRelease->download_link = $request->get('download_link');
        $stmVideoRelease->description = $request->get('description');
        // if($request->hasFile('artwork_file')){
        //     $originalFile = $request->file('artwork_file');
        //     $destinationPath = 'uploads/new_releases/artwork_file';
        //     $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
        //     $stmVideoRelease->artwork_file = $destinationPath.'/'.$fileName;
        // }

        if($request->hasFile('background_file')){
            $originalFile = $request->file('background_file');
            $destinationPath = 'uploads/new_releases/background_file';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $stmVideoRelease->background_file = $destinationPath.'/'.$fileName;
        }

       
        $demoTrack = \App\Campaign::where('id', $request->get('track_id'))
                                    ->first();

        $stmVideoRelease->slug = \App\libraries\GlobalHelper::getEloquentUniqueSlug('\App\StmVideoRelease', 'slug', $demoTrack->track_name ? $demoTrack->track_name : "music-video");
        $stmVideoRelease->save();



        \Session::flash('message','New release has been created successfully');
        return \Redirect::route('admin.music_video_release.index');
    }

    public function edit($id){
        $title = 'Edit';
        // $moods = \App\Mood::lists('name', 'id')->toArray();
        // $genres = \App\Genres::where('parent_id',null)->lists('name', 'id')->toArray();
        $artists = \App\User::where('user_type','artist')->where('status', 1)->lists('name', 'id')->toArray();
        $new_releases = StmVideoRelease::where('id', $id)->select('id', 'artist_id', 'track_id', 'url', 'artwork_file', 'slug', 'background_file', 'description')->first();
        $artistTracks = $trackList = \App\Campaign::where('user_id', $new_releases->artist_id)
                                                    ->where('type', 'remix')
                                                    ->lists('track_name', 'id')
                                                    ->toArray();

        // $genreId = \App\StmReleasesGenre::where('type', 'genre')
        //                             ->where('stm_releases_id', $id)
        //                             ->select('genre_id')
        //                             ->first();

        // $subGenreId = \App\StmReleasesGenre::where('type', 'sub_genre')
        //                                 ->where('stm_releases_id', $id)
        //                                 ->select('genre_id')
        //                                 ->first();

        // $sub_genres = [];
        // if($genreId){
        //     $sub_genres = \App\Genres::where('parent_id', $genreId->genre_id)->lists('name', 'id')->toArray();
        // }
        
        // $moodId = \App\StmVideoRelease::join('stm_releases_moods', 'stm_video_releases.id', '=', 'stm_releases_moods.stm_releases_id')
        //                                 ->where('stm_releases_moods.stm_releases_id', $id)
        //                                 ->select('stm_releases_moods.moods_id')
        //                                 ->first();
        
        return view('admin.releases_video.edit', compact(['title', 'artists', 'new_releases', 'artistTracks']));
        
        // return view('admin.releases_video.edit', compact(['title', 'moods', 'genres', 'genreId', 'subGenreId', 'moodId', 'sub_genres', 'artists', 'new_releases']));
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            // 'track_id' => 'required',
            'url' => 'required|url',
            // 'artwork_file' => 'mimes:jpeg,bmp,png',
            // 'artist_id' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect('admin/music_video_release/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        // echo '<pre>'; print_r($request->all()); die; 

        $stmVideoRelease = StmVideoRelease::find($id);
        

        // if($request->hasFile('artwork_file')){
        //     $originalFile = $request->file('artwork_file');
        //     $destinationPath = 'uploads/new_releases/artwork_file';
        //     if($stmVideoRelease->artwork_file){
        //       \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->artwork_file);
        //     }
        //     $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
        //     $stmVideoRelease->artwork_file = $destinationPath.'/'.$fileName;
        // }


        if($request->hasFile('background_file')){
            $originalFile = $request->file('background_file');
            $destinationPath = 'uploads/new_releases/background_file';
            if($stmVideoRelease->background_file){
              \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->background_file);
            }
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $stmVideoRelease->background_file = $destinationPath.'/'.$fileName;
        }
        
        $stmVideoRelease->url = $request->get('url');
        // $stmVideoRelease->download_link = $request->get('download_link');
        $stmVideoRelease->description = $request->get('description');
        $stmVideoRelease->save();


        \Session::flash('message','New release has been updated successfully');
        return \Redirect::route('admin.music_video_release.index');
    }

    public function delete($id)
    {
        $stmVideoRelease = StmVideoRelease::with(['genres','subGenres','moods','favourites'])->find($id);
        if((!empty($stmVideoRelease))||(count($stmVideoRelease) > 0)) {
            $stmVideoRelease->genres()->delete();
            $stmVideoRelease->subGenres()->delete();
            $stmVideoRelease->moods()->delete();
            $stmVideoRelease->favourites()->delete();
            // if($stmVideoRelease->artwork_file){
            //   \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->artwork_file);
            // }
            if($stmVideoRelease->download_link){
              \App\libraries\GlobalHelper::deleteFile($stmVideoRelease->download_link);
            }
            if($id){
                \App\TopItems::where('object_id', $id)
                            ->where('object_type', 'admin_video')
                            ->orWhere('object_type', 'spotlight_video')
                            ->delete();
            }
            $stmVideoRelease->delete();
            \Session::flash('message','New release has been deleted successfully');
            return redirect('admin/music_video_release');
        }else {
            \Session::flash('error','Something went wrong');
            return redirect()->back();
        }
    }

    public function addTop(Request $request) {

        if($request->get('top') == 'InTop'){
            \App\TopItems::where('object_id', $request->get('trackId'))
                            ->where('object_type', $request->get('type'))
                            ->delete();
            if ($request->get('type') == "admin_video") {
                return response()->json(['msg'=>'removed from toplist', 'status'=>'removed']);
            }else{
                return response()->json(['msg'=>'removed from spotlight', 'status'=>'removed']);
            }
        }
        else{
            $type =$request->get('type');
            if($type == "admin_video"){
                $count = \App\TopItems::where('object_type', $request->get('type'))
                                        ->count();
                if ($count < 2 ) {
                    $topList = \App\TopItems::firstOrCreate(['object_id'=> $request->input('trackId'),'object_type'=> $request->input('type')]);
                    $topList->object_type = $type;
                    $topList->object_id = $request->get('trackId');
                    $topList->sequence = 1; //Need to change for use sequence
                    $topList->save();
                    return response()->json(['msg'=>'successfully added in toplist', 'status'=>'added']);
                }
                else{
                    return response()->json(['You can not select more video for toplist']);
                }
            }

            if($type == "spotlight_video"){
                $id = $request->get('trackId');
                $campaignId = \App\StmVideoRelease::where('id', $id)->select('track_id')->first();
                $genre = \App\CampaignGenres::where('campaign_id', $campaignId->track_id)->select('genre_id')->first(); 
                $count = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')
                                                ->join('campaign_genres', 'stm_video_releases.track_id', '=', 'campaign_genres.campaign_id')
                                                ->where('top_items.object_type', 'spotlight_video')
                                                ->where('campaign_genres.genre_id', $genre->genre_id)
                                                ->count();
                
                if($count < 1 ) {
                    $topList = \App\TopItems::firstOrCreate(['object_id'=> $request->input('trackId'),'object_type'=> $request->input('type')]);
                    $topList->object_type = $type;
                    $topList->object_id = $request->get('trackId');
                    $topList->sequence = 1; //Need to change for use sequence
                    $topList->save();
                    return response()->json(['msg'=>'successfully added in spotlight','status'=>'added']);
                }else{
                    return response()->json(['You can not select more video for spotlight']); 
                }
                
            }
        }
    }



    public function getArtistTracks(Request $request){
        // echo '<pre>'; print_r($request->get('artistId')); die; 
        $term = $request->get('term');
        $artistId = $request->get('artistId');
        // $trackList = [];
        $trackIds = \App\StmVideoRelease::where('artist_id', $artistId)->lists('track_id');
        if($artistId){
            $trackList = \App\Campaign::where('user_id', $artistId)
                                        ->where('track_name', 'like', '%'.$term.'%')
                                        ->where('type', 'remix')
                                        ->whereNotIn('id', $trackIds)
                                        ->select('track_name','id')
                                        ->get();
        }


        // return response($trackList);
        return response()->json(compact('trackList'));
    }


}
