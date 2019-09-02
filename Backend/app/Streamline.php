<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Streamline extends Model {
    use Sluggable;

    protected $table = 'streamlines';
    protected $guarded = [];

    protected $fillable = ['track_link', 'artwork_link', 'artist_name', 'track_id', 'track_name', 'slug', 'social_terms', 'yt_link', 'sc_link', 'itune_link', 'apple_link', 'sp_link', 'gp_link', 'tidal_link', 'deezer_link', 'beatport_link', 'amazon_link', 'amazon_mp3_link', 'bandcamp_link', 'juno_link', 'trackitdown_link', 'stm_track_id', 'custom_art_file', 'custom_title', 'custom_link', 'bg_file', 'status', 'blur'];
    protected $appends = ['visit_count'];

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'track_name'
            ]
        ];
    }

    public function streamlineVisit(){
        return $this->hasMany('App\StreamlineVisit', 'streamline_id', 'id');
    }

    public function getVisitCountAttribute(){
        $visitCount = $this->streamlineVisit()->count();
        return $visitCount ? $visitCount : 0 ;
    }

    public static function boot() {
        parent::boot();

        Streamline::deleting(function($streamline) {
            $streamlineVisits = StreamlineVisit::where('streamline_id', $streamline['id'])->get();
            foreach ($streamlineVisits as $streamlineVisit) {
                $streamlineVisit->delete();
            }
        });
    }
}