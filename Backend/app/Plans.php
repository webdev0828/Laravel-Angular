<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'plans';
	// protected $guarded = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name','stripe_plan_key','amount','discover_demo_limit','remix_demo_limit'];
}
