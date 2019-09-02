<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plans;
use App\Http\Requests;

class DemoLimitController extends Controller
{
   	public function index()
    {
        $title = 'Demo Limit';
        $plans = Plans::select('id', 'name', 'discover_demo_limit', 'remix_demo_limit', 'video_demo_limit')->get();
        return view('admin.demo_limit.index',compact(['title','plans']));
    }

    public function store(Request $request){
    	$plans = Plans::select('id', 'name', 'discover_demo_limit')->get();
    	if($plans){
    		foreach ($plans as  $plan) {
    			$demoLabel = "demo_limit_".$plan->id;
                $remixLabel = "remix_limit_".$plan->id;
                $videoLabel = "video_limit_".$plan->id;
    			$demoLimitValue = $request->input($demoLabel);
                $remixLimitValue = $request->input($remixLabel);
                $videoLimitValue = $request->input($videoLabel);
				$input = [
							'discover_demo_limit' => $demoLimitValue,
                            'remix_demo_limit' => $remixLimitValue,
                            'video_demo_limit' => $videoLimitValue
						];
				\DB::table('plans')->where('id',$plan->id)->update($input);
    		}
    	}
    	\Session::flash('message','Demo limit has been updated successfully');
    	return redirect('admin/demo-limit');
    }    
}