<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'settings';
	protected $fillable = ['dripfeed_timer', 'current_status', 'last_checked_date', 'name'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

		
}