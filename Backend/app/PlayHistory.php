<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayHistory extends Model
{
    public $timestamps = false;
    protected $table = 'play_history';
    protected $fillable = ['track_id'];
}