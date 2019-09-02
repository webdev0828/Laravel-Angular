<?php namespace App\Http\Controllers\FrontWeb;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

class BaseController extends Controller {

	protected $user;

	public function __construct()
    {
        if(Auth::check()) {
            
        	$this->user = Auth::user();
        	if(!in_array($this->user->user_type, ['stm_user','artist'])) {
        		$this->user = null;
        	}
        }
    }
}