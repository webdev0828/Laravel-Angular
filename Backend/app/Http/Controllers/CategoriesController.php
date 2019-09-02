<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatable;
use App\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Categories';
        return view('admin.categories.index',compact('title'));
    }

    function getCategories()
    {
        $query = \DB::table('categories')->select('id','name', 'description');
        return Datatable::query($query)
        ->showColumns('name','description')
        ->addColumn('actions', function($model) { return 
        '<a href="javascript:void(0);" id="' . \URL::to('admin/categories/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#categoryEditModal" title="Edit" class="categoryEdit"><i class="fa fa-edit"></i></a> &nbsp;
         <a href="javascript:void(0);" id="' . \URL::to('admin/categories/'.$model->id) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a>'; })
        ->searchColumns('name')
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
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = \Input::all();
        Category::create($input);

        \Session::flash('message','Category has been successfully saved');
        return \Redirect::route('admin.categories.index');
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
        $category = Category::find($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $category = Category::findOrFail($id);
        $input = \Input::all();
        $category->update($input);
        
        \Session::flash('message','Category has been successfully edited');
        return \Redirect::route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $categories = Category::find($id);
        $categories->destroy($id);
        \Session::flash('message','Category has been successfully deleted');
    }
}
