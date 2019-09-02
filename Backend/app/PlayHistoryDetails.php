<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayHistoryDetails extends Model
{
    public $timestamps = false;
    protected $table = 'play_history_details';
    protected $fillable = ['track_id'];
}
