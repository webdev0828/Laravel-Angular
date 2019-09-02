<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'activities';
	protected $guarded = [];
	public $timestamps = false;


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id','sender_id', 'object', 'object_type', 'object_id', 'message'];

	############################################################
	### Owner Relation
	public function profile()
	{
		return $this->belongsTo('\App\ArtistsProfile', 'sender_id', 'user_id');
		//->with(['thumbnail']);
	}

	public function userProfile()
	{
		return $this->belongsTo('\App\UserProfile', 'sender_id', 'user_id');
		//->with(['thumbnail']);
	}
	public function owner()
	{
		return $this->belongsTo('\App\User', 'user_id')->select('first_name','last_name','avatar','id','name','slug','user_type');
		//->with(['thumbnail']);
	}

	public function track() {
		return $this->belongsTo('\App\TrackDemo', 'object_id');
	}

	public function favouriteCampaign() {
		return $this->belongsTo('\App\Campaign', 'object_id');
	}

	public function favouriteRemix() {
		return $this->belongsTo('\App\CompetitionArtist', 'object_id');
	}

	public function favouriteVideo() {
		return $this->belongsTo('\App\StmVideoRelease', 'object_id');
	}

	public function downloadTrack(){
		return $this->belongsTo('\App\TrackDemo', 'object_id');
	}

	public function downloadCampaign() {
		return $this->belongsTo('\App\Campaign', 'object_id');
	}

	public function downloadRemix() {
		return $this->belongsTo('\App\CompetitionArtist', 'object_id');
	}

	public function downloadVideo() {
		return $this->belongsTo('\App\StmVideoRelease', 'object_id');
	}

	public function follower() {
		return $this->belongsTo('\App\User', 'object_id')->select('first_name','last_name','avatar','id','name','slug','user_type');
	}

	public function following() {
		return $this->belongsTo('\App\User', 'object_id')->select('first_name','last_name','avatar','id','name','slug','user_type');
	}





}
