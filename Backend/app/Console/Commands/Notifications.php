<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Notifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change notification comments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $notificationsUsers = \App\Notification::where('notifications.type','followed')
                                                ->select('notifications.comments','notifications.user_id','notifications.id')->get();
        
        foreach ($notificationsUsers as $value) {
           

            $pos = strpos($value->comments, '/user/');
            if($pos){
                $array = explode('/',$value->comments);
                $slug = $array[4];
              
                if(!empty($slug)){
                    $userData = \App\User::where('slug',$slug)->select('user_type')->first();
                    if($userData){
                       if($userData->user_type == 'artist'){
                          echo $value->id."===".$slug."\n";
                           echo  $newComment  = str_replace('/user/', '/', $value->comments);
                           echo "\n";
                        $notification = \App\Notification::find($value->id);
                        $newComment  = str_replace('/user/', '/', $value->comments);
                        $newComment  = str_replace('favourites', 'tracks', $newComment);
                        $notification->comments = $newComment;
                        $notification->save();

                       }
                    }
                }
            }
        }

         $notificationsUsers = \App\Notification::where('notifications.type','favorited')
                                                ->select('notifications.comments','notifications.user_id','notifications.id')->get();
        
        foreach ($notificationsUsers as $value) {
            
            // echo $value->comments."\n";

            $pos = strpos($value->comments, '/user/');
            if($pos){
                $array = explode('/',$value->comments);
                $slug = $array[4];
              
                if(!empty($slug)){
                    $userData = \App\User::where('slug',$slug)->select('user_type')->first();
                    if($userData){
                       if($userData->user_type == 'artist'){
                          echo $value->id."===".$slug."\n";
                           echo  $newComment  = str_replace('/user/', '/', $value->comments);
                           echo "\n";
                        $notification = \App\Notification::find($value->id);
                        $newComment  = str_replace('/user/', '/', $value->comments);
                        $newComment  = str_replace('favourites', 'tracks', $newComment);
                        $notification->comments = $newComment;
                        $notification->save();

                       }
                    }
                }
            }
        }

    }
 

}
