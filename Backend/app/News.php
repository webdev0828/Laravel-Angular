<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use Cviebrock\EloquentSluggable\Sluggable;

class News extends Model
{
    use Sluggable;
    protected $table = 'news';
    protected $fillable = ['title', 'description','tags','image_name', 'user_id','category_id'];
    protected $appends = ['comment_count', 'category_name'];


        public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    
    public function getCommentCountAttribute(){
        $commentCount = $this->comments()->count();
        return $commentCount;
    }

    public function getCategoryNameAttribute(){
        $categoryName = $this->categories()->first();
        return $categoryName->name;
    }

    public function comments(){
        return $this->hasMany('App\Comment', 'news_id', 'id');
    }

    public function categories(){
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function boot(){
        parent::boot();

        News::deleting(function($news){

            $comments = Comment::where('news_id', $news['id'])->get();
            foreach ($comments as $comment) {
                $comment->delete();
            }

        });
    }
}
