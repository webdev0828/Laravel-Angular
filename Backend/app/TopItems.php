<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopItems extends Model
{
    protected $table = 'top_items';

    protected $fillable = ['boject_id', 'boject_type', 'sequence'];
}