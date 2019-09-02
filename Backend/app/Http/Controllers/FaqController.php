<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Faq;
use Datatable;
use Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "FAQ's";
        return view('admin.faq.index', compact('title'));
    }

    function getFaq()
    {
        $query = \DB::table('faqs')->select('id','title', 'comment', 'created_at');

        return Datatable::query($query)
        ->addColumn('title', function($model) {return ucfirst($model->title);})
        ->addColumn('comment', function($model) {return $model->comment;})
        ->addColumn('created_at', function($model){
            return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date("d/m/Y", strtotime($model->created_at));
        })
        ->addColumn('actions', function($model) { return 
        '<a href="' . \URL::to('admin/faqs/'.$model->id.'/edit') . '"><i class="fa fa-edit"></i></a> &nbsp;
         <a href="' . \URL::to('admin/faqs/'.$model->id) . '/delete" onclick="return confirmDelete();"><i class="fa fa-trash"></i></a>'; })
        ->searchColumns('title','comment')
        ->make();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Add";
        return view('admin.faq._form', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect('admin/faqs/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $input = \Input::all();
        
        $faq->create($input);

        \Session::flash('message','FAQ has been created successfully');
        return \Redirect::route('admin.faqs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Edit";

        $faq_data = Faq::find($id);
        return view('admin.faq._form', compact(['title', 'faq_data']));
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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect('admin/faqs/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $faq = Faq::findOrFail($id);
        $inputs = $request->all();
        $faq->update($inputs);
        
        \Session::flash('message','FAQ has been updated successfully');
        return \Redirect::route('admin.faqs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFaq($id)
    {
        
        $faq = Faq::find($id);
        $faq->destroy($id);
        \Session::flash('message','FAQ has been deleted successfully');

        return \Redirect::route('admin.faqs.index');
    }
}
