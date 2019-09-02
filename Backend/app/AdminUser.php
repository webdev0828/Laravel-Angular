<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $table = 'admin_users';

    protected $fillable = ['user_id', 'fname', 'lname', 'name', 'souncloud_url', 'facebook_url', 'twitter_url', 'youtube_url', 'instagram_url', 'bio', 'image'];

    public static function boot(){
        parent::boot();

        AdminUser::deleting(function($adminUser){

            $adminFaqs = Faq::where('user_id', $adminUser['user_id'])->get();
            foreach ($adminFaqs as $adminFaq) {
                $adminFaq->delete();
            }

            $adminNews = News::where('user_id', $adminUser['user_id'])->get();
            foreach ($adminNews as $adminNew) {
                if($adminNew->image_name){
                  \App\libraries\GlobalHelper::deleteFile($adminNew->image_name);
                }
                $adminNew->delete();
            }

        });
    }

}
