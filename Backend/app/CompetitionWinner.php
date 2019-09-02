<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionWinner extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'competition_winners';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['competition_id', 'winner_id', 'position','campaign_id','video_id'];


}
