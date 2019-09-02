<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthIdentity extends Model
{
     /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'oauth_identities';
	protected $guarded = [];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'provider_user_id','provider','access_token'];
}
