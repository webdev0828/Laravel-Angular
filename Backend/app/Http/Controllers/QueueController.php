<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Track;
use Datatable;


class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tracks list';
        $unregistered = Track::getUnregisteredUsersTracks();
        $members = Track::getMembersTracks();
        $prousers = Track::getProUsersTracks();
        $favourites = Track::getFavouritesTracks();
        $deletedTracks = Track::getDeletedTracks();
        return view('admin.artists.queue',compact('title','unregistered','members','prousers','favourites','deletedTracks'));
    }

    function get_queue_counts()
    {
        $unregistered = Track::getUnregisteredUsersTracks();
        $members = Track::getMembersTracks();
        $prousers = Track::getProUsersTracks();
        $favourites = Track::getFavouritesTracks();
        $deletedTracks = Track::getDeletedTracks();
        echo json_encode(array('unregistered' => $unregistered->count(), 'members' => $members->count(),'prousers' => $prousers->count(), 'favourites' => $favourites->count(), 'deletedTracks' => $deletedTracks->count()));
    }

    function getMembersTracks()
    {
        $members = Track::getMembersTracks();
        return Datatable::query($members)
        ->addColumn('username', function($model) { 
            $status = "approve";
            $thumb = "up";
            if($model->status == '')
            {
                $status = "approve";
                $title = "";
            }
            else if($model->status == '1')
            {
                $status = "unapprove";
                $title = "Approved";
            }
            else if($model->status == 0)
            {
                $status = "approve";
                $thumb = "down";
                $title = "Unapproved";
            }
        return 
        '<div class="search-item well">
            <div class="row">
                <div class="col-md-2" style="padding:0px">
                    <img src="'.$model->avatar_url.'" class="img-responsive">
                </div>
                <div class="col-md-10" style="padding:0px">
                    <div class="col-md-8">
                        <b>'.$model->title.'</b><br>
                        <i class="fa fa-user"></i>&nbsp;&nbsp;'.$model->username.'
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-facebook m-b-xs pull-right favourite_track" id="'.$model->id.'"><i class="fa fa-heart-o"></i></button>
                        <button type="button" class="btn btn-youtube m-b-xs pull-right delete_track" id="'.$model->id.'"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-twitter m-b-xs pull-right like_track" id="'.$model->id.'" data-item="'.$status.'" data-id="'.$model->status.'" title="'.$title.'"><i class="fa fa-thumbs-o-'.$thumb.'"></i></button>
                    </div>
                    <div class="col-md-12">
                    </div>
                    
                    <h3 class="no-m"> </h3>
                    <a class="search-link" href="javascript:void(0);"></a>
                    <a href="'.$model->soundcloud_url.'" class="playAudio"></a>
                    <p><iframe width="100%" height="134" frameborder="no" src="https://w.soundcloud.com/player/?url='.$model->soundcloud_url.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true" scrolling="no"></iframe></p>
                </div>
            </div>
        </div>'; })
        ->searchColumns('username','title')
        ->make();
    }

    function getUnregisteredTracks()
    {
        $tracks = Track::getUnregisteredUsersTracks();
        return Datatable::query($tracks)
        ->addColumn('username', function($model) { 
            $status = "approve";
            $thumb = "up";
            if($model->status == '')
            {
                $status = "approve";
                $title = "";
            }
            else if($model->status == '1')
            {
                $status = "unapprove";
                $title = "Approved";
            }
            else if($model->status == 0)
            {
                $status = "approve";
                $thumb = "down";
                $title = "Unapproved";
            }
        return 
        '<div class="search-item well">
            <div class="row">
                <div class="col-md-2" style="padding:0px">
                    <img src="'.$model->avatar_url.'" class="img-responsive">
                </div>
                <div class="col-md-10" style="padding:0px">
                    <div class="col-md-8">
                        <b>'.$model->title.'</b><br>
                        <i class="fa fa-user"></i>&nbsp;&nbsp;'.$model->username.'
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-facebook m-b-xs pull-right favourite_track" id="'.$model->id.'"><i class="fa fa-heart-o"></i></button>
                        <button type="button" class="btn btn-youtube m-b-xs pull-right delete_track" id="'.$model->id.'"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-twitter m-b-xs pull-right like_track" id="'.$model->id.'" data-item="'.$status.'" data-id="'.$model->status.'" title="'.$title.'"><i class="fa fa-thumbs-o-'.$thumb.'"></i></button>
                    </div>
                    <div class="col-md-12">
                    </div>
            
                    <h3 class="no-m"> </h3>
                    <a class="search-link" href="javascript:void(0);"></a>
                    <a href="'.$model->soundcloud_url.'" class="playAudio"></a>
                    <p><iframe width="100%" height="134" frameborder="no" src="https://w.soundcloud.com/player/?url='.$model->soundcloud_url.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true" scrolling="no"></iframe></p>
                </div>
            </div>
        </div>'; })
        ->searchColumns('username','title')
        ->make();
    }

    function getProUsersTracks()
    {
        $tracks = Track::getProUsersTracks();
        return Datatable::query($tracks)
        ->addColumn('username', function($model) { 
            $status = "approve";
            $thumb = "up";
            if($model->status == '')
            {
                $status = "approve";
                $title = "";
            }
            else if($model->status == '1')
            {
                $status = "unapprove";
                $title = "Approved";
            }
            else if($model->status == 0)
            {
                $status = "approve";
                $thumb = "down";
                $title = "Unapproved";
            }
        return 
        '<div class="search-item well">
            <div class="row">
                <div class="col-md-2" style="padding:0px">
                    <img src="'.$model->avatar_url.'" class="img-responsive">
                </div>
                <div class="col-md-10" style="padding:0px">
                    <div class="col-md-8">
                        <b>'.$model->title.'</b><br>
                        <i class="fa fa-user"></i>&nbsp;&nbsp;'.$model->username.'
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-facebook m-b-xs pull-right favourite_track" id="'.$model->id.'"><i class="fa fa-heart-o"></i></button>
                        <button type="button" class="btn btn-youtube m-b-xs pull-right delete_track" id="'.$model->id.'"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-twitter m-b-xs pull-right like_track" id="'.$model->id.'" data-item="'.$status.'" data-id="'.$model->status.'" title="'.$title.'"><i class="fa fa-thumbs-o-'.$thumb.'"></i></button>
                    </div>
                    <div class="col-md-12">
                    </div>
            
                    <h3 class="no-m"> </h3>
                    <a class="search-link" href="javascript:void(0);"></a>
                    <a href="'.$model->soundcloud_url.'" class="playAudio"></a>
                    <p><iframe width="100%" height="134" frameborder="no" src="https://w.soundcloud.com/player/?url='.$model->soundcloud_url.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true" scrolling="no"></iframe></p>
                </div>
            </div>
        </div>'; })
        ->searchColumns('username','title')
        ->make();
    }

    function getFavouritesTracks()
    {
        $members = Track::getFavouritesTracks();
        return Datatable::query($members)
        ->addColumn('username', function($model) { 
            $status = "approve";
            $thumb = "up";
            if($model->status == '')
            {
                $status = "approve";
                $title = "";
            }
            else if($model->status == '1')
            {
                $status = "unapprove";
                $title = "Approved";
            }
            else if($model->status == 0)
            {
                $status = "approve";
                $thumb = "down";
                $title = "Unapproved";
            }

        return 
        '<div class="search-item well">
            <div class="row">
                <div class="col-md-2" style="padding:0px">
                    <img src="'.$model->avatar_url.'" class="img-responsive">
                </div>
                <div class="col-md-10" style="padding:0px">
                    <div class="col-md-8">
                        <b>'.$model->title.'</b><br>
                        <i class="fa fa-user"></i>&nbsp;&nbsp;'.$model->username.'
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-youtube m-b-xs pull-right delete_track" id="'.$model->id.'"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-twitter m-b-xs pull-right like_track" id="'.$model->id.'" data-item="'.$status.'" data-id="'.$model->status.'" title="'.$title.'"><i class="fa fa-thumbs-o-'.$thumb.'"></i></button>
                    </div>
                    <div class="col-md-12">
                    </div>
            
                    <h3 class="no-m"> </h3>
                    <a class="search-link" href="javascript:void(0);"></a>
                    <a href="'.$model->soundcloud_url.'" class="playAudio"></a>
                    <p><iframe width="100%" height="134" frameborder="no" src="https://w.soundcloud.com/player/?url='.$model->soundcloud_url.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true" scrolling="no"></iframe></p>
                </div>
            </div>
        </div>'; })
        ->searchColumns('username','title')
        ->make();
    }

    function getBinTracks()
    {
        $deletedTracks = Track::getDeletedTracks();
        return Datatable::query($deletedTracks)
        ->addColumn('username', function($model) { return 
        '<div class="search-item well">
            <div class="row">
                <div class="col-md-2" style="padding:0px">
                    <img src="'.$model->avatar_url.'" class="img-responsive">
                </div>
                <div class="col-md-10" style="padding:0px">
                    <div class="col-md-8">
                        <b>'.$model->title.'</b><br>
                        <i class="fa fa-user"></i>&nbsp;&nbsp;'.$model->username.'
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-youtube m-b-xs pull-right delete_track" id="delete_'.$model->id.'"><i class="fa fa-trash"></i></button>
                    </div>
                    <div class="col-md-12">
                    </div>
            
                    <h3 class="no-m"> </h3>
                    <a class="search-link" href="javascript:void(0);"></a>
                    <a href="'.$model->soundcloud_url.'" class="playAudio"></a>
                    <p><iframe width="100%" height="134" frameborder="no" src="https://w.soundcloud.com/player/?url='.$model->soundcloud_url.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true" scrolling="no"></iframe></p>
                </div>
            </div>
        </div>'; })
        ->searchColumns('username','title')
        ->make();
    }

    public function trackLike()
    {
        $trackID = \Input::get('track_id');
        $status = \Input::get('status');
        $tracks = Track::find($trackID);
        $tracks->status = 1;
        if(!empty($status))
        {
            $tracks->status = 0;
        }
        $tracks->save();

        return $tracks->status;
    }

    function favouriteTrack()
    {
        $trackID = \Input::get('track_id');
        // $userID = \Auth::id();
        // $user = \App\User::find($userID);
        // $user->tracks()->attach($trackID);
        $tracks = Track::find($trackID);
        $tracks->favourite = 1;
        $tracks->save();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (strpos($id,'delete_') !== false) {
            $id = str_replace('delete_', '', $id);
            $tracks = Track::withTrashed()->find($id);
            $tracks->forceDelete();
        }
        else
        {
            $tracks = Track::find($id);
            $tracks->delete();  
        }
    }
}
