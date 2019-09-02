<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Admin\NewsCategoriesRequest;
use App\Http\Controllers\Controller;
use Datatable;
use App\NewsCategory;

class NewsCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'News Categories';
        return view('admin.newscategories.index',compact('title'));
    }

    function getNewsCategories()
    {
        $query = \DB::table('news_categories')->select('id','name', 'description');
        return Datatable::query($query)
        ->showColumns('name','description')
        ->addColumn('actions', function($model) { 
            return $model->name != "Blog" ? '<a href="javascript:void(0);" id="' . \URL::to('admin/newscategories/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#newscategoryEditModal" title="Edit" class="newscategoryEdit"><i class="fa fa-edit"></i></a> &nbsp;<a href="javascript:void(0);" id="' . \URL::to('admin/newscategories/'.$model->id) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a>' : ""; 
        })
        ->searchColumns('name','description')
        ->orderColumns('id','name')
        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.newscategories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(NewsCategoriesRequest $request)
    {
        // echo '<pre>'; print_r($request->all()); die; 
        $input = \Input::all();
        NewsCategory::create($input);

        \Session::flash('message','News Category has been created successfully');
        return \Redirect::route('admin.newscategories.index');
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
        $category = NewsCategory::find($id);
        return view('admin.newscategories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(NewsCategoriesRequest $request, $id)
    {
        $category = NewsCategory::findOrFail($id);
        $input = \Input::all();
        $category->update($input);
        
        \Session::flash('message','News Category has been updated successfully');
        return \Redirect::route('admin.newscategories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $categories = NewsCategory::find($id);
        $categories->destroy($id);
        \Session::flash('message','News Category has been deleted successfully');
    }
}
