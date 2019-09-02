<?php

namespace App\Http\Controllers\FrontWeb;

use Illuminate\Http\Request;

class StreamlineController extends BaseController {

    public function getSingleStreamline(Request $request, $slug) {
        $streamline = \App\Streamline::join('artists_profile', 'streamlines.artist_name', '=', 'artists_profile.name', 'left outer')
                                        ->join('campaigns', 'streamlines.stm_track_id', '=', 'campaigns.id', 'left outer')
                                        ->where('streamlines.slug', $slug)
                                        ->select('streamlines.*', 'campaigns.slug AS camp_slug', 'artists_profile.slug AS artist_slug')
                                        ->first();

        if ($streamline->id) {
            $ip = $request->ip();
            $streamlineVisit = \App\libraries\GlobalHelper::streamlineVisits($ip, $streamline->id);
        }

        return response()->json(['data' => $streamline]);
    }

    public function saveStreamlineShares(Request $request) {
        $user = $this->user;

        if ($user) {
            $userId = $user->id;
        } else {
            $userId = 0;
        }

        $streamlineShares = new \App\StreamlineShares;

        $streamlineShares->streamline_id = $request->get('streamlineId');
        $streamlineShares->streamline_user_id = $request->get('streamlineUserId');
        $streamlineShares->user_id = $userId;
        $streamlineShares->share_service = $request->get('shareService');
        if ($userId != 0) {
            $streamlineShares->save();
        }

        return response()->json(['status'=>'success','data'=>$streamlineShares],200);
    }
}