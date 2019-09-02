<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignGenres extends Model
{
    protected $table = 'campaign_genres';

    protected $fillable = ['campaign_id', 'genre_id'];
}