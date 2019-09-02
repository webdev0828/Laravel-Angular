<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeActions extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'stripe_actions';
	// protected $guarded = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id','actionable_date','stripe_customer_id','action','change_plan_to','stripe_id'];

}
