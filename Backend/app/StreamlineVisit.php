<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StreamlineVisit extends Model {
    protected $table = 'streamline_visits';
    protected $fillable = ['ip_address', 'streamline_id'];
}
