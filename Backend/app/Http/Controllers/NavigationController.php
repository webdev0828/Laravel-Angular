<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

class NavigationController extends Controller
{

    public function getNewQueue(Request $request) {
        /*$items = null;
        if ($request->get('queue_type') == 'quality-control') {
            $items = \App\TrackDemo::where('type', 'remix')
                                                ->whereNotNull('isSignupTrack')
                                                ->where('repost_track_id', null)
                                                ->where('status', 'pending')
                                                ->where('check', false)
                                                ->select('id')
                                                ->get();
        } else if ($request->get('queue_type') == 'discover') {
            $items = \App\TrackDemo::where('type', 'discover')
                                                ->where('repost_track_id', null)
                                                ->where('status', 'pending')
                                                ->where('check', false)
                                                ->select('id')
                                                ->get();
        } else if ($request->get('queue_type') == 'remix') {
            $items = \App\TrackDemo::where('type', 'remix')
                                        ->where('isSignupTrack', null)
                                        ->where('repost_track_id', null)
                                        ->where('status', 'pending')
                                        ->where('check', false)
                                        ->select('id')
                                        ->get();
        } else if ($request->get('queue_type') == 'music-video') {
            $items = \App\TrackDemo::where('type', 'music_video')
                                            ->where('repost_track_id', null)
                                            ->where('status', 'pending')
                                            ->where('check', false)
                                            ->select('id')
                                            ->get();
        } else if ($request->get('queue_type') == 'remix-competition') {
            $competitions = \App\CompetitionArtist::where('status', null)
                                                    ->where('check', false)
                                                    ->select('id')
                                                    ->get();
            foreach ($competitions as $item) {
                $competitionArtist = \App\CompetitionArtist::find($item->id);
                $competitionArtist->check = true;
                //$competitionArtist->save();
            }
        }

        if ($items != null) {
            foreach ($items as $item) {
                $trackDemo = \App\TrackDemo::find($item->id);
                $trackDemo->check = true;
                //$trackDemo->save();
            }
        }*/

        $qualityControlCounts = \App\TrackDemo::where('type', 'remix')
                                                ->whereNotNull('isSignupTrack')
                                                ->where('repost_track_id', null)
                                                ->where('status', 'pending')
                                                ->where('check', false)
                                                ->count();

        $repostCounts = \App\TrackDemo::where('type', 'remix')
                                        ->where('isSignupTrack', null)
                                        ->where('repost_track_id', null)
                                        ->where('status', 'pending')
                                        ->where('check', false)
                                        ->count();

        $discoverCounts = \App\TrackDemo::where('type', 'discover')
                                            ->where('repost_track_id', null)
                                            ->where('status', 'pending')
                                            ->where('check', false)
                                            ->count();

        $musicVideoCounts = \App\TrackDemo::where('type', 'music_video')
                                            ->where('repost_track_id', null)
                                            ->where('status', 'pending')
                                            ->where('check', false)
                                            ->count();


        $now = date('Y-m-d');
        $letestCompetition = \App\Competition::where('start_date','<=',$now)
                                                ->where('end_date','>=',$now)
                                                ->where('status', 1)
                                                ->select('id')
                                                ->first();
        $competitionCounts = 0;
        if ($letestCompetition) {
            $competitionCounts = \App\CompetitionArtist::where('status', null)
                ->where('competition_id', $letestCompetition->id)
                ->where('check', false)
                ->count();
        }

        $data = array(
            'quality-control' => $qualityControlCounts,
            'discover' => $discoverCounts,
            'remix' => $repostCounts,
            'music-video' => $musicVideoCounts,
            'remix-competition' => $competitionCounts,
        );

        /*if (array_key_exists($request->get('queue_type'), $data)) {
            $data[$request->get('queue_type')] = 0;
        }*/

        $totalCounts = array_sum($data);
        $data['total'] = $totalCounts;

        return $data;
    }
}