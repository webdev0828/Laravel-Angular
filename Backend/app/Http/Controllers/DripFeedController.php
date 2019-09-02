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

class DripFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function startDripFeedJobs()
    // {
    //     // \DB::table('drip_feeds')->truncate();
    //     $dripFeeds = \App\TrackDemo::where('status','dripfeed')->select('id','type','user_id')->get();
    //     if($dripFeeds){
    //         foreach ($dripFeeds as  $value) {
    //           $dripFeed = \App\DripFeed::firstOrCreate(['track_id'=> $value->id,'track_type'=> $value->type]);
    //           $dripFeed->track_id = $value->id;
    //           $dripFeed->track_type = $value->type;
    //           $dripFeed->user_id = $value->user_id;
    //           $dripFeed->save();
    //         }
    //     }

    // }

    // public function stopDripFeed(){
    //     // \DB::table('drip_feeds')->truncate();
    // }


    public function getDripFeedData(Request $request){
      $dripFeedData = \App\Setting::where('name', 'dripfeed')->first();
      return response()->json($dripFeedData);
    }


     public function saveDripFeedData(Request $request){
      $dripFeedTimer = $request->get('dripfeed_timer');
      $name = $request->get('name');
      $dripFeedData = \App\Setting::where('name', $name)->first();
      $dripFeedData->dripfeed_timer = $dripFeedTimer;
      $dripFeedData->save();
      \Session::flash('message','Drip Feed timer has been updated successfully');
      return redirect()->back();
    }


    public function DripFeedStart(Request $request){
      $dripFeedData = \App\Setting::where('name', 'dripfeed')->first();
      $dripFeedData->current_status = 1;
      $dripFeedData->save();
      return redirect()->back();
    }

    public function DripFeedStop(Request $request){
      $dripFeedData = \App\Setting::where('name', 'dripfeed')->first();
      $dripFeedData->current_status = 0;
      $dripFeedData->save();
      return redirect()->back();
    }


}
