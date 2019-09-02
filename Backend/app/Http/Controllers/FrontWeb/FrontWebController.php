<?php

namespace App\Http\Controllers\FrontWeb;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class FrontWebController extends BaseController
{
    function __construct()
    {
        // construct
        parent::__construct();
    }

    public function index()
    {
        return view('layouts.default');
        
    }

    public function showCampaignWithMeta($slug) {
        if(!empty($slug)){
            $track = \App\Campaign::select('cover_image','type','track_name','id')
                                    ->where('slug', $slug)
                                    ->first();

            if($track){
                $track->genres = $track->allGenres()->lists('name');
                $track->moods = $track->moods()->lists('name');
                $meta['image'] = $track->cover_image;
                $meta['title'] = $track->track_name;
                $meta['description'] = $track->genres[0] . '-' . $track->moods[0];
                return view('layouts.default', ['meta' => $meta]);
            }
        }

    }

    public function showTrackWithMeta($slug) {

            $track = \App\TrackDemo::select('cover_image','type','track_name','id')
                                    ->where('slug', $slug)
                                    ->first();

        if($track){
            $track->genres = $track->allGenres()->lists('name');
            $track->moods = $track->moods()->lists('name');
            $meta['image'] = $track->cover_image;
            $meta['title'] = $track->track_name;

            $arrDescrption = [];
            if (count($track->genres)) {
                $arrDescrption[] = $track->genres[0];
            }

            if (count($track->moods)) {
                $arrDescrption[] = $track->moods[0];
            }

            $meta['description'] = implode(' - ', $arrDescrption);
            return view('layouts.default', ['meta' => $meta]);
        }
    }

    public function faq()
    {
        $allFaqs = \App\Faq::get();
        return response()->json(['data' => $allFaqs]);
    }

    public function downloadtrack($type, $slug) {
        $agent = new Agent();

        $downloadFile = null;
        $user = $this->user;
        if($type == 'track') {
            $track = \App\TrackDemo::where('slug', $slug)->first();

            /*$downloadFile = public_path( $track->mp3_file);*/
            if ($track->external_download_link == null || $track->external_download_link == '') {
                $downloadFile = public_path($track->mp3_file);
            } else {
                $downloadFile = public_path($track->external_download_link);
            }

            $redirect = '/recommended-tracks/track/'.$slug;

        } else if( $type == 'campaign') {
            $track = \App\Campaign::where('slug', $slug)->first();

            /*$downloadFile = public_path( $track->mp3_file);*/
            if ($track->external_download_link == null || $track->external_download_link == '') {
                $downloadFile = public_path($track->mp3_file);
            } else {
                $downloadFile = public_path($track->external_download_link);
            }

            $redirect = '/recommended-campaigns/campaign/'.$slug;
            // $downloadFile = public_path( $track->mp3_file);
        }

        if($track->type == 'remix'){
             $downloadFile = $track->external_download_link;
        }

        if($downloadFile){

            /*if($track->type == 'remix'){
                try {
                    // 'https://api.soundcloud.com/tracks/280560739/download?client_id=4feece2ec5272350900d792dcbf71f1e'
                    $ch = curl_init();
                    $timeout = 5;
                    curl_setopt($ch, CURLOPT_URL, $downloadFile);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $data = curl_exec($ch);
                    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    // $new_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                    curl_close($ch);

                    if($status =='200' || $status =='302'){
                        $downloadTrack = new \App\DownloadHistory;

                        if($type == 'campaign'){
                            $downloadTrack->campaign_id = $track->id;
                            $downloadTrack->type = 'campaign';
                        }
                        else if($type == 'track'){
                            $downloadTrack->track_id = $track->id;
                            $downloadTrack->campaign_id = $track->campaign_id;
                            $downloadTrack->type = 'track';
                        }

                        $downloadTrack->user_id = $user ? $user->id : null;
                        $downloadTrack->artist_id = $track->user_id;
                        $downloadTrack->user_type = $user ? $user->user_type : null;
                        $downloadTrack->save();

                        header('Set-Cookie: fileDownload=true; path=/');
                        header("Cache-Control: no-cache, no-store, must-revalidate;");
                        if($agent->isMobile()){
                            if($user){
                                $data = array(
                                    'user'              =>  $user->name ? $user->name : $user->first_name,
                                    'email'             =>  $user->email,
                                    'url'               =>  $downloadFile,
                                    'subject'           =>  'Download link',
                                    'track_name'        =>  $track->track_name
                                );
                                $response = \App\libraries\MailHelper::sendEmail("emails.templates.send-download-link", $data);
                                return;
                            }
                        }

                        return redirect($downloadFile);
                    } else {
                        return response()->json(['error' => 'file not found']);
                    }
                } catch (\Exception $e) {
                    // echo $e;
                    return response()->json(['error' => 'file not found']);
                }
            }*/
            //else{
                if(file_exists($downloadFile)){

                    $downloadTrack = new \App\DownloadHistory;

                    if($type == 'campaign'){
                        $downloadTrack->campaign_id = $track->id;
                        $downloadTrack->type = 'campaign';
                    }
                    else if($type == 'track'){
                        $downloadTrack->track_id = $track->id;
                        $downloadTrack->campaign_id = $track->campaign_id;
                        $downloadTrack->type = 'track';
                    }

                    $downloadTrack->user_id = $user ? $user->id : null;
                    $downloadTrack->artist_id = $track->user_id;
                    $downloadTrack->user_type = $user ? $user->user_type : null;
                    $downloadTrack->save();

                    header('Set-Cookie: fileDownload=true; path=/');
                    header("Cache-Control: no-cache, no-store, must-revalidate;");
                    $fileInfo = pathinfo($downloadFile);
                    if($fileInfo) {
                        if($agent->isMobile()){
                            if($user){
                                $data = array(
                                    'user'              =>  $user->name ? $user->name : $user->first_name,
                                    'email'             =>  $user->email,
                                    'url'               =>  url($type.'/download-track/'.$slug),
                                    'subject'           =>  'Download link',
                                    'track_name'        =>  $track->track_name
                                );
                                $response = \App\libraries\MailHelper::sendEmail("emails.templates.send-download-link", $data);
                                return;
                            }
                        }

                        $fileName = $track->track_name.'.'.$fileInfo['extension'];
                        return response()->download($downloadFile, $fileName);
                    } else {

                        return response()->download($downloadFile);
                    }


                }
                else{
                    return response()->json(['error' => 'file not found']);
                }
            //}

            // return response()->download($downloadFile);
        }
        else{
            return response()->json(['error' => 'file not found']);
        }

        // return response()->json(['error' => 'file not found']);
    }

    public function downloadtrackEmail($type, $slug) {
        $downloadFile = null;

        if($type == 'track') {
            $track = \App\TrackDemo::where('slug', $slug)->first();
            $downloadFile = public_path( $track->mp3_file);

        } else if( $type == 'campaign') {
            $track = \App\Campaign::where('slug', $slug)->first();
            $downloadFile = public_path( $track->mp3_file);
        }

        if($downloadFile){
            if(file_exists($downloadFile)){
                header('Set-Cookie: fileDownload=true; path=/');
                header("Cache-Control: no-cache, no-store, must-revalidate;");
                $fileInfo = pathinfo($downloadFile);
                if($fileInfo) {
                    $fileName = $track->track_name.'.'.$fileInfo['extension'];
                    return response()->download($downloadFile, $fileName);
                } else {
                    return response()->download($downloadFile);
                }
            }
            else{
                return response()->json(['error' => 'file not found']);
            }
        }
        else{
            return response()->json(['error' => 'file not found']);
        }
    }

    public function senddownloadtrackEmail(Request $request){

        $type = $request->get('type');
        $slug = $request->get('slug');
        $email = $request->get('email') ? $request->get('email') : '';
        $name = $request->get('name') ? $request->get('name') : '';
        $user = $this->user;
        if($user){
            $name = $user->name ? $user->name : $user->first_name;
            $email = $user->email;
        }

        $downloadFile = null;
        if($type == 'track') {
            $track = \App\TrackDemo::where('slug', $slug)->first();
            $downloadFile = public_path( $track->mp3_file);
        } else if( $type == 'campaign') {
            $track = \App\Campaign::where('slug', $slug)->first();
            $downloadFile = public_path( $track->mp3_file);
        }
        $artist = \App\User::select('name')->where('id',$track->user_id)->first();
        if($track->type == 'remix'){
             $downloadFile = $track->external_download_link;
        }

        if($downloadFile){

            if($track->type == 'remix'){
                try {
                    // 'https://api.soundcloud.com/tracks/280560739/download?client_id=4feece2ec5272350900d792dcbf71f1e'
                    $ch = curl_init();
                    $timeout = 5;
                    curl_setopt($ch, CURLOPT_URL, $downloadFile);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $data = curl_exec($ch);
                    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    // $new_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                    curl_close($ch);

                    if($status =='200' || $status =='302'){
                        $downloadTrack = new \App\DownloadHistory;

                        if($type == 'campaign'){
                            $downloadTrack->campaign_id = $track->id;
                            $downloadTrack->type = 'campaign';
                        }
                        else if($type == 'track'){
                            $downloadTrack->track_id = $track->id;
                            $downloadTrack->campaign_id = $track->campaign_id;
                            $downloadTrack->type = 'track';
                        }

                        $downloadTrack->user_id = $user ? $user->id : null;
                        $downloadTrack->artist_id = $track->user_id;
                        $downloadTrack->user_type = $user ? $user->user_type : null;
                        $downloadTrack->save();

                        if($email){
                            $data = array(
                                'user'              =>  $name,
                                'email'             =>  $email,
                                'url'               =>  $downloadFile,
                                'subject'           =>  'Download link',
                                'track_name'        =>  $track->track_name,
                                'artist_name'       =>  $artist->name
                             );
                            //  if($user){
                            //     $data['artist_name'] =    $track->artist_name;
                            // }
                            $response = \App\libraries\MailHelper::sendEmail("emails.templates.send-download-link", $data);
                            return response()->json(['success' => 'Email sent']);
                        }
                        else{
                            return response()->json(['data' => 'UserNotFound'],200);
                        }

                    } else {
                        return response()->json(['error' => 'file not found']);
                    }
                } catch (\Exception $e) {
                    return response()->json(['error' => 'file not found']);
                }
            }
            else{
                if(file_exists($downloadFile)){

                    $downloadTrack = new \App\DownloadHistory;

                    if($type == 'campaign'){
                        $downloadTrack->campaign_id = $track->id;
                        $downloadTrack->type = 'campaign';
                    }
                    else if($type == 'track'){
                        $downloadTrack->track_id = $track->id;
                        $downloadTrack->campaign_id = $track->campaign_id;
                        $downloadTrack->type = 'track';
                    }

                    $downloadTrack->user_id = $user ? $user->id : null;
                    $downloadTrack->artist_id = $track->user_id;
                    $downloadTrack->user_type = $user ? $user->user_type : null;
                    $downloadTrack->save();

                    $fileInfo = pathinfo($downloadFile);
                    if($fileInfo) {
                        if($email){
                            $data = array(
                                'user'              =>  $name,
                                'email'             =>  $email,
                                'url'               =>  url($type.'/download-track/'.$slug),
                                'subject'           =>  'Download link',
                                'track_name'        =>  $track->track_name,
                                'artist_name'       =>  $artist->name

                            );
                            // if($user){
                            //     $data['artist_name'] =    $track->artist_name;
                            // }
                            $response = \App\libraries\MailHelper::sendEmail("emails.templates.send-download-link", $data);
                            return response()->json(['success' => 'Email sent']);
                        }
                        else{
                            return response()->json(['data' => 'UserNotFound'],200);
                        }
                    } else {

                    }
                }
                else{
                    return response()->json(['error' => 'file not found']);
                }
            }

            // return response()->download($downloadFile);
        }
        else{
            return response()->json(['error' => 'file not found']);
        }

    }
}
