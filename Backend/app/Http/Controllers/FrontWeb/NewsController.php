<?php

namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class NewsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $limit = $request->get('limit',8);
        $tag = $request->get('tag');

        $latestNews = \App\News::join('admin_users', 'news.user_id', '=', 'admin_users.user_id')
                         ->select('news.*', 'admin_users.name')->orderBy('news.created_at','desc')->first();

        $news = \App\News::join('admin_users', 'news.user_id', '=', 'admin_users.user_id')
                         ->select('news.*', 'admin_users.name')
                         ->where('news.id', '!=', $latestNews->id);

        if($tag) {
            $news = $news->whereRaw('find_in_set("'.$tag.'",tags) <> 0');
        }
                         
        $news = $news->orderBy('news.created_at', 'desc')
                    ->paginate($limit);

        $news = array(
            'news' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage()
            );


        return response()->json(['data' => $news, 'latestNews'=> $latestNews]);
    }

    public function getMoreNews(Request $request){
        
        $limit = $request->get('limit',8);
        $tag = $request->get('tag');

        $news = \App\News::join('admin_users', 'news.user_id', '=', 'admin_users.user_id')
                         ->select('news.*', 'admin_users.name');

         if($tag) {
            $news = $news->where('tags', 'LIKE', '%'.$tag.'%');
        }
                         
        $news = $news->orderBy('news.created_at', 'desc')
                     ->paginate($limit);

        $news = array(
            'news' => $news->items(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage()
            );
        return response()->json(['data' => $news]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug){
        $news = \App\News::join('admin_users', 'news.user_id', '=', 'admin_users.user_id')
                         ->select('news.*', 'admin_users.name', 'admin_users.bio', 'admin_users.soundcloud_url', 'admin_users.facebook_url', 'admin_users.twitter_url', 'admin_users.youtube_url', 'admin_users.instagram_url', 'admin_users.bio', 'admin_users.image')
                         ->where('news.slug', $slug)
                         ->with('comments')
                         ->first();

        if($news) {
            $relatedNews = \App\News::join('admin_users', 'news.user_id', '=', 'admin_users.user_id')
                                ->select('news.*', 'admin_users.name', 'admin_users.bio', 'admin_users.soundcloud_url', 'admin_users.facebook_url', 'admin_users.twitter_url', 'admin_users.youtube_url', 'admin_users.instagram_url', 'admin_users.bio', 'admin_users.image')
                                ->orderBy('news.created_at', 'desc')
                                ->where('news.slug','!=', $slug)
                                ->where('news.category_id', $news->category_id)
                                ->get();
            return response()->json(['data' => $news, 'relatedNews' => $relatedNews]);
        } else {
            return response()->json(['error'=>'News not found'], 422);
        }
    }
}
