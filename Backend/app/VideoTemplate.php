<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoTemplate extends Model
{
    protected $table = 'video_templates';

    protected $fillable = ['template_name', 'template_url'];
}
