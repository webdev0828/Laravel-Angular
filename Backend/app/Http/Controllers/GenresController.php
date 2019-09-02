<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Genres;
use Datatable;

class GenresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Genres';
        return view('admin.genres.index',compact('title'));
    }

    function getGenres()
    {

        $users = \DB::table('genres')->select('id','name', 'description', 'created_at')->where('parent_id', null);
        return Datatable::query($users)
        ->showColumns('name','description')
        ->addColumn('created_at', function($model) {return $model->created_at;})
        ->addColumn('actions', function($model) { 
            return '<a href="javascript:void(0);" id="' . \URL::to('admin/genres/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#genresModal" title="Edit" class="genresEdit"><i class="fa fa-edit"></i></a> &nbsp;
                    <a class="genre-delete" href="javascript:void(0);" id="' . route('admin.genres.destroy',[$model->id]) . '" title="Delete" data-id="'.$model->id.'"><i class="fa fa-trash"></i></a> &nbsp;
                    <a href="'.\URL::to('admin/genres/'.$model->id.'/sub-genres').'" title="view sub-genres"><i class="fa fa-plus-square-o"></i></a>'; 
        })
        ->searchColumns('name','description')
        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = \Input::all();
        Genres::create($input);

        \Session::flash('message','Genres has been created successfully');
        return \Redirect::route('admin.genres.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {  
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $genres = Genres::find($id);
        return view('admin.genres.edit', compact('genres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $genres = Genres::findOrFail($id);
        $input = \Input::all();
        $genres->update($input);
        
        \Session::flash('message','Genres has been updated successfully');
        return \Redirect::route('admin.genres.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $subGenres = Genres::where('parent_id', $id)->delete();
        $genres = Genres::find($id)->delete();
        
        \Session::flash('message','Genres has been deleted successfully');
    }

    public function checkGenreExist(Request $request)
    {
        $id= $request->get('id');

        $campaignGenres = \App\CampaignGenres::where('genre_id',$id)->first();
        $artistGenres = \App\ArtistGenres::where('genre_id',$id)->first();

        if($campaignGenres || $artistGenres){
            \Session::flash('error','Can not delete this genre because it is in use');
            $title = 'Genres';
            return view('admin.genres.index',compact('title'));
        }

        else{
            return response('notExist');
        }

    }
}
