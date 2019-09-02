<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ContactRequest;

class HomeController extends BaseController {

	public function __construct()
    {
        // construct
    }

    public function index(){
        $news = \App\News::join('news_categories', 'news.category_id', '=', 'news_categories.id')
        			//	 ->where('news_categories.name', 'Blog')
                         ->select('news.*')
                         ->orderBy('news.created_at', 'desc')
                         ->take(3)
                         ->get();

        $spotlightVideo = \App\StmVideoRelease::
                                            join('top_items', 'stm_video_releases.id', '=', 'top_items.object_id')
                                            ->where('top_items.object_type', 'spotlight_video')
                                            ->with(array('campaign'=>function($q){
                                                    $q->select('id', 'track_name', 'slug');
                                                }
                                            ))
                                            ->select('stm_video_releases.*')
                                            ->orderBy('stm_video_releases.created_at', 'desc')
                                            ->take(2)
                                            ->get();

        $spotlighTracks = \App\TrackDemo::join('top_items', 'track_demos.id', '=', 'top_items.object_id')
                                        ->where('top_items.object_type','spotlight_discover')
                                        ->select('track_demos.*')
                                        ->where('track_demos.status', 'approved')
                                        ->orderBy('top_items.created_at', 'DESC')
                                        ->take(4)
                                        ->get();

        foreach($spotlighTracks as $item) {
            if ($item->type == 'discover' && $item->external_download_link != null && $item->external_download_link != '') {
                $item->mp3_file = $item->external_download_link;
            }
        }

        $userCount = \App\User::where('user_type', 'stm_user')->count();

        $globalPlays = \App\PlayHistory::sum('count');

        $tracksDownloaded = \App\DownloadHistory::count();

        $artistCount = \App\User::where('user_type', 'artist')->count();

        $tweets = \App\libraries\GlobalHelper::getTweets();
        // $tweets = [];

        $faqs =  \App\Faq::all();

        return response()->json(['news' => $news, 'spotlightVideo' => $spotlightVideo, 'spotlighTracks' => $spotlighTracks, 'userCount' => $userCount, 'artistCount' => $artistCount,'globalPlays'=>$globalPlays,'tracksDownloaded'=>$tracksDownloaded, 'tweets'=>$tweets,'faqs' => $faqs]);
    }

    public function contactUs(ContactRequest $request){

            $data = array(
                    'messageText'       => $request->get('message'),
                    'name'              => $request->get('name'),
                    'email'             => env('SUPPORT_MAIL'),
                    'sender'            => $request->get('email'),
                    'subject'           => $request->get('subject')
                );
        try{

            $response = \App\libraries\MailHelper::sendEmail('emails.contact-us', $data);

            // \Mail::send('emails.contact-us', $data, function($m) use ($request)
            // {
            //     $m->to(env('SUPPORT_MAIL'));
            //     $m->subject($request->get('subject'));
            // });
            return response()->json(['status'=>'success'],200);
        }catch (\Exception $e) {
                return response()->json(['status'=>'error','message'=>'Something went wrong.Please try again','code'=>'500'],422);
            }
    }


}