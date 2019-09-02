<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CampaignVisit extends Model
{
    
    protected $table = 'campaign_visits';

    protected $fillable = ['ip_address', 'campaign_id', 'track_id'];

    // public $timestamps = false;

}
