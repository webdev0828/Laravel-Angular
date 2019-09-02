<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StmReleasesMood extends Model
{
    protected $table = 'stm_releases_moods';

    protected $fillable = ['stm_releases_id','moods_id'];
}
