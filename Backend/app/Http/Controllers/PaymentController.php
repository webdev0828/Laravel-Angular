<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatable;
use DB;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Payments';
        $order = \App\PlanBilling::get();
        $subscriptions = \Config::get('app.subscriptions');
        return view('admin.payments.index',compact('title','subscriptions','order'));
    }

    function getTransactions(Request $request)
    {
        $query = \App\PlanBilling::join('artists_profile', 'plans_billing.user_id', '=', 'artists_profile.user_id')->select('plans_billing.*','artists_profile.name as artist_name');;

        if($request->get('start_date') && !empty($request->get('start_date'))) {
            $query = $query->whereRaw('DATE_FORMAT(plans_billing.start_date, "%Y-%m-%d") >= \''. date('Y-m-d', strtotime($request->get('start_date'))) . '\'');
        }

        if($request->get('end_date') && !empty($request->get('end_date'))) {
            $query = $query->whereRaw('DATE_FORMAT(plans_billing.start_date, "%Y-%m-%d") <= \''. date('Y-m-d', strtotime($request->get('end_date'))) . '\'');
        }

        return Datatable::query($query)
        ->showColumns('artist_name','plan_name','price')
        ->addColumn('start_date', function($model){
            return '<span class="sort-date">'.strtotime($model->start_date).'</span>'.date("d/m/Y", strtotime($model->start_date));
        })
        ->addColumn('expire_date', function($model){
            return date("d/m/Y", strtotime($model->expire_date));
        })
        ->setSearchWithAlias()
        ->searchColumns('artists_profile.name','plan_name','price')
        ->make();
    }

    function export()
    {
        $exportData = \App\PlanBilling::join('artists_profile', 'plans_billing.user_id', '=', 'artists_profile.user_id')->select('artists_profile.name', 'plans_billing.plan_name', 'plans_billing.price', 'plans_billing.start_date', 'plans_billing.expire_date')->get();
        // dd($exportData);
        $fileName = date("Y-m-d");
        return \Excel::create($fileName, function($excel) use($exportData, $fileName) {
            $excel->sheet($fileName, function($sheet)  use($exportData) {
                $sheet->loadView('admin.payments.export', compact('exportData'));
            });
        })->export('xls');
        // return view('admin.payments.export',compact('exportData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
