<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'news_comment';

    protected $fillable = ['news_id', 'user_id','comment'];

    public static function getCommentByNews($id){
    	return Comment::where('news_id', '=', $id)
        		->join('users','users.id', '=', 'news_comment.user_id')
                ->select('news_comment.id','user_id','comment_text','news_id','first_name','last_name','news_comment.created_at', 'name')
                ->get();
    }
}
