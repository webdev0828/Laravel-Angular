<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionGenre extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'competition_genres';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['competition_id', 'genre_id', 'type'];

}