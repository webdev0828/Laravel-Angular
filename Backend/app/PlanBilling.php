<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanBilling extends Model
{
    protected $guarded = [];
    protected $table = 'plans_billing';
    protected $fillable = ['plan_name', 'price','start_date','expire_date'];

    public function artists(){
    	$this->belongsToMany('\App\ArtistsProfile','user_id', 'user_id');
    }

    public function plans(){
    	$this->belongsToMany('\App\Plans','id', 'plan_id');
    }
}
