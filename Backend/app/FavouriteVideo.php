<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavouriteVideo extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'favourite_videos';
	protected $guarded = [];


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['video_id', 'user_id'];
}
