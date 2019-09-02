<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignMoods extends Model
{
    protected $table = 'campaign_moods';

    protected $fillable = ['campaign_id', 'mood_id'];
}