<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Donation;
use App\User;
use App\Track;
use Auth;
use Datatable;
class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Donations";
        $artistList = \App\User::where('user_type', 'artist')->lists('name','id')->toArray();
        return view('admin.donation.index', compact('title','artistList'));
    }
    function getDonation(Request $request)
    {
        // $query = \DB::table('donations')->select('id','user_id', 'artist_id', 'user_id');
        $query = Donation::join('users as artist', 'artist.id', '=', 'donations.artist_id')
                    ->join('users as u', 'u.id', '=', 'donations.user_id')
                    ->select('u.name as userName', 'artist.name as artistName', 'donation_amount','donations.created_at');
        // dd($request->all());
        if($request->get('artist') && !empty($request->get('artist'))) {
            $query = $query->where('donations.artist_id', '=', $request->get('artist'));
        }
        
        if($request->get('start_date') && !empty($request->get('start_date'))) {
            $query = $query->whereRaw('DATE_FORMAT(donations.created_at, "%Y-%m-%d") >= \''. date('Y-m-d', strtotime($request->get('start_date'))) . '\'');
        }

        if($request->get('end_date') && !empty($request->get('end_date'))) {
            $query = $query->whereRaw('DATE_FORMAT(donations.created_at, "%Y-%m-%d") <= \''. date('Y-m-d', strtotime($request->get('end_date'))) . '\'');
        }

        return Datatable::query($query)
        ->addColumn('userName', function($model) {return ucfirst($model->userName);})
        ->addColumn('artistName', function($model) {return ucfirst($model->artistName);})
        ->addColumn('donation_amount', function($model) {return $model->donation_amount;})
        ->addColumn('created_at', function($model){
            return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date("d/m/Y", strtotime($model->created_at));
        })
        ->searchColumns('u.name', 'artist.name')
        ->make();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Donation $donation)
    {
        $inputs = $request->all();
        $donation->create($inputs);

        \Session::flash('message','Successfully donated');
        return \Redirect::route('admin.donation.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Donation Table";
        $data = Track::where('campaigns.id', $id)
                ->join('users', 'users.id', '=', 'campaigns.user_id')
                ->select('campaigns.user_id as artistId', 'campaigns.id as trackId', 'users.name')
                ->first();        
        return view('admin.donation.show', compact('title','data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dataList()
    {
        $title = "Donation List";
        $listData = Donation::join('campaigns', 'campaigns.id', '=', 'donations.track_id')
                    ->join('users as artist', 'artist.id', '=', 'donations.artist_id')
                    ->join('users as u', 'u.id', '=', 'donations.user_id')
                    ->select('u.name as userName', 'artist.name as artistName', 'title', 'donation_amount','donations.created_at')
                    ->get();
        return view('admin.donation.list', compact(['listData', 'title']));
    }

}
