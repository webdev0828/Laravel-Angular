<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Donation extends Model
{
    protected $table = 'donations';

    protected $fillable = ['user_id', 'artist_id', 'track_id', 'donation_amount'];

    public function user()
	{
		return $this->belongsTo('\App\User');
	}
	public function artist(){

		return $this->belongsTo('\App\User');
	}
	public function track(){

		return $this->belongsTo('\App\Track');
	}
}
