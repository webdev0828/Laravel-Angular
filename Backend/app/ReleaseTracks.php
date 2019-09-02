<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReleaseTracks extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'label_release';
	protected $fillable = ['user_id'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	public function getAlbumImageAttribute()
    {
        $path = $this->attributes['album_image'] ? $this->attributes['album_image'] : '';
        return $path;
    }
	
}