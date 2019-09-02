<?php 
namespace App\Listeners;

use Carbon\Carbon;
use Auth;

class UpdateLastLoginOnLogin
{
    public function handle($event)
    {
        $user = Auth::user();
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}