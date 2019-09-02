<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Mood extends Model
{
    use Sluggable;
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'moods';
	protected $guarded = [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'description','slug'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

	public static function boot(){
        parent::boot();

        Mood::deleting(function($mood){
            $campaignMoods = CampaignMoods::where('mood_id', $mood['id'])->get();
            foreach ($campaignMoods as $campaignMood) {
                $campaignMood->delete();
            }

            $stmReleasesMoods = StmReleasesMood::where('moods_id', $mood['id'])->get();
            foreach ($stmReleasesMoods as $stmReleasesMood) {
                $stmReleasesMood->delete();
            }

            $trackDemoMoods = TrackDemoMoods::where('mood_id', $mood['id'])->get();
            foreach ($trackDemoMoods as $trackDemoMood) {
                $trackDemoMood->delete();
            }
                
        });
    }


}
