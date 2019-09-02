<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests\Admin\CompetitionRequest;

use App\Http\Controllers\Controller;
use Datatable;

class RemixCompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Remix Competition';
        $now = date('Y-m-d');
        $competitionList = \App\Competition::join('campaigns', 'competitions.track_id', '=', 'campaigns.id')
                                            ->lists('campaigns.track_name','competitions.id')
                                            ->toArray();

        $letestCompetition = \App\Competition::where('start_date','<=',$now)
                                                ->where('end_date','>=',$now)
                                                ->where('status', 1)
                                                ->select('id','announcement_date')
                                                ->first();
                                                // echo '<pre>'; print_r($letestCompetition); die; 
        if($letestCompetition){
            // $isClosed =  (date("Y-m-d") >  date('Y-m-d', strtotime($letestCompetition->announcement_date))) ? 'closed' : 'open' ;
            // echo '<pre>'; print_r($isClosed); die; 
        }                                        
        return view('admin.tracks.remix-competition',compact('title','competitionList','letestCompetition'));
    }

     public function getPrivateTrackPlay($sc_id){
         if($sc_id){
          // $scId = $trackData->sc_id;
           $trackInfo = \App\SoundcloudArtist::where('sc_id', $sc_id)->select('secret_token','download_url')->first();
           // print_r($trackInfo);
           if($trackInfo){
            $token = $trackInfo->secret_token;
             $playURL = "?secret_token=".$token."&client_id=".env('SOUNDCLOUD_KEY');
                return $playURL;
           }
        }
        return "";

    }

    public function getRemixCompetition(Request $request){
        
        if($request->get('competition_id') && !empty($request->get('competition_id'))) {
            $competitionTracks = \App\CompetitionArtist::select('competition_artists.*')->orderBy('created_at','DESC');
            $competitionTracks = $competitionTracks->where('competition_id', $request->get('competition_id'));
            if($request->get('status') && !empty($request->get('status'))) {
                $competitionTracks = $competitionTracks->where('status', $request->get('status'));
            }
        }else{
            $competitionTracks = \App\CompetitionArtist::where('id', 0);
        }

            return Datatable::query($competitionTracks)

            ->addColumn('track name', function($model) {
                 $MP3URL = $model->mp3_file;
                 $clientURL='';
                 $clientURL = $this->getPrivateTrackPlay($model->sc_id);
                 return '<a class="decoration-none trackPlay" data-type="'.$model->type.'" data-track="'.$model->mp3_file.$clientURL.'" data-title="'.$model->track_name.'" href="javascript:void(0)"><span class="fa fa-play-circle-o text-success"></span></a>&nbsp;'.ucfirst($model->track_name);
                // return $model->track_name;
            })

            ->addColumn('artist name', function($model) {
                return ucfirst($model->artist_name);
            })
            ->addColumn('bad', function($model) {
                $checked = $model->status == "bad" ? 'checked="checked"' : '';
                return '<input type="radio" value="bad" data-id="'.$model->id.'" class="radio-status" '.$checked.' name="'.$model->id.'">';
            })
            ->addColumn('good', function($model) {
                $checked = $model->status == "good" ? 'checked="checked"' : '';
                return '<input type="radio" value="good" data-id="'.$model->id.'" class="radio-status" '.$checked.' name="'.$model->id.'">';
            })
            ->addColumn('finalist', function($model) {
                $checked = $model->status == "finalist" ? 'checked="checked"' : '';
                return '<input type="radio" value="finalist" data-id="'.$model->id.'" class="radio-status" '.$checked.' name="'.$model->id.'">';
            })
            
            ->searchColumns('track_name')
            ->orderColumns('artist_name')
            ->make();  
    }


    /*public function changeState(Request $request){
        if($request->get('competition_id')){
            $competitionId = $request->get('competition_id');
            // $competitionTrack = \App\CompetitionWinner::where('competition_id', $competitionId)->delete();
            $competition = \App\Competition::find($competitionId);
            if($competition->published_date == null){
                $competitionWinner = \App\CompetitionWinner::where('competition_id', $competitionId)->delete();

                $inputs = $request->all();
                // echo '<pre>'; print_r($inputs); die; 
                foreach ($inputs as $key => $value) {
                    if(is_int($key)){
                        $competitionTrack = \App\CompetitionArtist::find($key);
                        $competitionTrack->status = $value;
                        $competitionTrack->check = true;
                        $competitionTrack->save();
                    }
                }
                return redirect()->back()->withInput();
            }else{
                \Session::flash('error','Competition is closed');
                return redirect()->back()->withInput();
            }
            
        }
        return redirect()->back();
    }*/

    public function changeState(Request $request){
        if($request->get('competition_id')){
            $competitionId = $request->get('competition_id');
            // $competitionTrack = \App\CompetitionWinner::where('competition_id', $competitionId)->delete();
            $competition = \App\Competition::find($competitionId);
            if($competition->published_date == null){
                $competitionWinner = \App\CompetitionWinner::where('competition_id', $competitionId)->delete();

                $inputs = $request->all();
                $counts = 0;
                // echo '<pre>'; print_r($inputs); die;
                foreach ($inputs as $key => $value) {
                    if(is_int($key)){
                        $competitionTrack = \App\CompetitionArtist::find($key);
                        $competitionTrack->status = $value;
                        $competitionTrack->check = true;
                        $competitionTrack->save();

                        $counts ++;
                    }
                }
                return response()->json(['count'=>$counts]);
            }else{
                \Session::flash('error','Competition is closed');
                return response()->json(['message'=>'Competition is closed']);
            }

        }
        return response()->json(['message'=>'Competition is not exist']);
    }


    public function getCompetitionList(Request $request){ 
        $term = $request->get('term');
        $competitionList = \App\Competition::join('campaigns', 'competitions.track_id', '=', 'campaigns.id')
                                        ->where('campaigns.track_name', 'like', '%'.$term.'%')
                                        ->select('campaigns.track_name','competitions.id')
                                        ->get();

        return response()->json(compact('competitionList'));
    }


}