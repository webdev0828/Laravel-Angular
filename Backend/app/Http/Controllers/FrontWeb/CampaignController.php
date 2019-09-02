<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignController extends BaseController {


	public function getTracks(Request $request, $slug= null) {
        $campaigns = \App\Campaign::query();
        $limit = $request->get('limit',9) ;
    
        // if($type){
        //     $campaigns = $campaigns->where('campaigns.type',$type);
        // }

        if($slug){
            $user = \App\User::where('slug',$slug)->first();
            if(!$user)
                return response()->json(['status'=>'error','message'=>'Artist not found '], 422);
            $campaigns = $campaigns->where('user_id', $user->id);
        }

        // if($request->get('pagination')){
        //     $pagination = $request->get('pagination');
        //     $campaigns = $campaigns->where('status',1)->orderBy('campaigns.created_at', 'desc')->select('campaigns.id','campaigns.user_id','campaigns.soundcloud_tracks as sc_id', 'campaigns.external_download_link' , 'campaigns.track_name', 'campaigns.slug', 'campaigns.mp3_file', 'campaigns.cover_image', 'campaigns.background_file','campaigns.type')->paginate($limit);
        // }else{
            $campaigns = $campaigns->where('status',1)->select('campaigns.id','campaigns.user_id','campaigns.soundcloud_tracks as sc_id', 'campaigns.external_download_link' , 'campaigns.track_name', 'campaigns.slug', 'campaigns.mp3_file', 'campaigns.cover_image', 'campaigns.background_file','campaigns.type')->orderBy('campaigns.created_at', 'desc')->paginate($limit);

            foreach($campaigns->items() as $campaign) {
                if ($campaign->type == 'original' && $campaign->external_download_link != null && $campaign->external_download_link != '') {
                    $campaign->mp3_file = $campaign->external_download_link;
                }
            }
        // }
        
        $data = array(
            'data' => $campaigns->items(), 
            'current_page' =>  $campaigns->currentPage(),
            'last_page' =>  $campaigns->lastPage()
        );
        
        return response()->json($data);
    }

    public function getMoreTracks(Request $request, $slug= null) {
        $campaigns = \App\Campaign::query();
        $limit = $request->get('limit',9) ;
        
        // if($type){
        //     $campaigns = $campaigns->where('campaigns.type',$type);
        // }

        if($slug){
            $user = \App\User::where('slug',$slug)->first();
            if(!$user)
                return response()->json(['status'=>'error','message'=>'Artist not found '], 422);
            $campaigns = $campaigns->where('user_id', $user->id);
        }

         $campaigns = $campaigns->where('status',1)->select('campaigns.id','campaigns.user_id','campaigns.soundcloud_tracks as sc_id', 'campaigns.external_download_link' , 'campaigns.track_name', 'campaigns.slug', 'campaigns.mp3_file', 'campaigns.cover_image', 'campaigns.background_file','campaigns.type')->orderBy('campaigns.created_at', 'desc')->paginate($limit);

        foreach($campaigns->items() as $campaign) {
            if ($campaign->type == 'original' && $campaign->external_download_link != null && $campaign->external_download_link != '') {
                $campaign->mp3_file = $campaign->external_download_link;
            }
        }

        $data = array(
            'data' => $campaigns->items(), 
            'current_page' =>  $campaigns->currentPage(),
            'last_page' =>  $campaigns->lastPage()
        );
        return response()->json($data);
    }


    public function getEverythingData(Request $request,$slug){
        
        $user = \App\User::where('slug',$slug)->first();
        $limit = $request->get('limit',9) ;

        $tracks = \App\Campaign::where('user_id', $user->id)
                                    ->select('campaigns.*')
                                    ->where('status', 1)
                                    ->whereIn('type', ['original','remix'])
                                    ->orderBy('campaigns.created_at', 'desc')
                                    ->paginate($limit);

        foreach($tracks->items() as $campaign) {
            if ($campaign->type == 'original' && $campaign->external_download_link != null && $campaign->external_download_link != '') {
                $campaign->mp3_file = $campaign->external_download_link;
            }
        }
                                    
        $topVideos = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')//Need to change as par client suggestion
                                    ->where('top_items.object_type', 'admin_video')
                                    ->select('stm_video_releases.*')
                                    ->orderBy('stm_video_releases.created_at', 'desc')
                                    ->get();
        $data = array(
            'tracks' => $tracks->items(), 
            'current_page' =>  $tracks->currentPage(),
            'last_page' =>  $tracks->lastPage()
        );
        return response()->json(['data'=>$data , 'videos' => $topVideos]);
    }

    public function getMoreEverythingData(Request $request,$slug){

        $user = \App\User::where('slug',$slug)->first();

        $limit = $request->get('limit',9) ;

        // $tracks = \App\TrackDemo::where('user_id', $user->id)
        //                             ->select('track_demos.*')
        //                             ->where('status', "approved")
        //                             ->whereIn('type', ['discover','remix'])
        //                             ->orderBy('track_demos.created_at', 'desc')
        //                             ->paginate($limit);
        $tracks = \App\Campaign::where('user_id', $user->id)
                                    ->select('campaigns.*')
                                    ->where('status', 1)
                                    ->whereIn('type', ['original','remix'])
                                    ->orderBy('campaigns.created_at', 'desc')
                                    ->paginate($limit);

        foreach($tracks->items() as $campaign) {
            if ($campaign->type == 'original' && $campaign->external_download_link != null && $campaign->external_download_link != '') {
                $campaign->mp3_file = $campaign->external_download_link;
            }
        }

        $topVideos = \App\StmVideoRelease::join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')//Need to change as par client suggestion
                                    ->where('top_items.object_type', 'admin_video')
                                    ->select('stm_video_releases.*')
                                    ->orderBy('stm_video_releases.created_at', 'desc')
                                    ->get();
        $data = array(
            'tracks' => $tracks->items(), 
            'current_page' =>  $tracks->currentPage(),
            'last_page' =>  $tracks->lastPage()
        );
        return response()->json(['data'=>$data , 'videos' => $topVideos]);                                    
        // return response()->json(['tracks' => $tracks, 'videos' => $topVideos]);
    }
}