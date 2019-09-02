<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subscriptions';
	// protected $guarded = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id','name','stripe_id','stripe_plan','quantity','trial_ends_at','ends_at'];

	 public function user()
     {
         $model = getenv('STRIPE_MODEL') ?: config('services.stripe.model');
 
        return $this->belongsTo($model, 'user_id');
        $model       = getenv('STRIPE_MODEL') ?: config('services.stripe.model');
        $modelObject = explode('\\', $model);
        $columnName  = strtolower(array_pop($modelObject));
         
        return $this->belongsTo($model, $columnName . '_id');
     }

}
