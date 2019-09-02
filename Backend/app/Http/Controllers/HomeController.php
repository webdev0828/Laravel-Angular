<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Datatable;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$title = 'Dashboard';
		$stmusers = User::getCountofUsers('stm_user');
		$artists = User::getCountofUsers('artist');
		// $queue = \App\Campaign::getUnregisteredUsersTracks()->count();
		$queue = \App\TrackDemo::getPenddingTracksCount();
		$pendingCompetitionTracks = \App\CompetitionArtist::getPenddingTracksCount();
		$queue = $queue + $pendingCompetitionTracks;
		// $payments = \App\Order::leftJoin('transactions', 'orders.id', '=', 'transactions.order_id')
		// 			// ->groupBy('transactions.order_id'‌​)
		// 		    ->select(\DB::raw('transactions.id, SUM(transactions.amount) as total'))
		// 		    ->get();
		$payments = \App\PlanBilling::select(\DB::raw('SUM(price) as total'))
				    				  ->get();

		// dd('title:'.($title), 'Artists:'.$artists, 'STM_Users:'.$stmusers, $payments, $queue);
		return view('admin.dashboard.dashboard', compact('title','artists','stmusers','payments','queue'));
	}

	function getTodaysSubscribers()
	{
		// $query = \DB::table('orders')
		// 			->join('users', 'orders.user_id', '=', 'users.id');
		
		// $query->select('orders.id as order_id','orders.payment_status',
		// 				'orders.subscription','users.name','users.email');
		// return Datatable::query($query)
		// ->showColumns('order_id','name','subscription','email','payment_status')
		// ->searchColumns('orders.id','users.name','orders.subscription','orders.payment_status','orders.created_at')
		// ->orderColumns('users.name','users.email')
		// ->make();

		$query = \DB::table('plans_billing')
					->join('users', 'plans_billing.user_id', '=', 'users.id')
					->join('plans', 'plans_billing.plan_id', '=', 'plans.id')
					->whereRaw('Date(users.created_at) = CURDATE()')
					->where('users.user_type','artist')
					->orderBy('users.created_at','DESC');

		$query->select('plans_billing.id as order_id',
						// 'plans_billing.id as payment_status',
						'plans.name as subscription','users.name','users.email','plans_billing.price');
		return Datatable::query($query)
		->showColumns('order_id','name','email','subscription','price')
		->searchColumns('plans_billing.id','users.name','plans_billing.created_at','users.email')
		 ->addColumn('price', function($model){
          return "&pound;".sprintf("%.2f",$model->price);
        })
		->make();
	}


}
