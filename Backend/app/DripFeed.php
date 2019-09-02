<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DripFeed extends Model
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'drip_feeds';
	protected $guarded = [];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['track_id', 'track_type', 'user_id'];
    
}
