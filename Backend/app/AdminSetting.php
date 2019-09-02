<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $table = "admin_settings";

    protected $fillable = ['email_send'];
}