<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Genres;
use Datatable;

class SubGenresController extends Controller
{
    
    public function index($id){
        $title = 'Sub-Genres';
        return view('admin.genres.sub-genres',compact(['title', 'id']));
    }

    function getSubGenres($id){  
        
        $users = \DB::table('genres')->select('id','name','description','parent_id','created_at')->where('parent_id', $id);
        return Datatable::query($users)
        ->showColumns('name','description')
        ->addColumn('created_at', function($model) { return $model->created_at;})
        ->addColumn('actions', function($model) {
            return '<a href="javascript:void(0);" id="' . \URL::to('admin/genres/'.$model->parent_id.'/sub-genres/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#subGenresModal" title="Edit" class="subGenresEdit"><i class="fa fa-edit"></i></a> &nbsp;
                    <a href="javascript:void(0);" id="' . \URL::to('admin/genres/'.$model->parent_id.'/sub-genres/'.$model->id).'" data-parent-id="'.$model->parent_id.'" data-id="'.$model->id.'" title="Delete" class="sub-genre-delete""><i class="fa fa-trash"></i></a>'; 
        })
        ->searchColumns('name','description')
        ->make();
    }
 
    public function store($parentId){
        $input = \Input::all();
        
        $subGenres = Genres::findOrNew($input['subGenreId']);
        $subGenres->name = $input['name'];
        $subGenres->description = $input['description'];
        $subGenres->parent_id = $parentId;
        $subGenres->save();

        \Session::flash('message','Sub-Genre has been saved successfully');
        return \Redirect::back();
        // return \redirect()->action('SubGenresController@index');
    }


    public function edit($genreId, $subGenreId){
        $genres = Genres::find($subGenreId);
        return response()->json(['genres' => $genres]);
    }

    
    public function destroy($genreId, $subGenreId){

        $genres = Genres::find($subGenreId);
        $genres->destroy($subGenreId);
        \Session::flash('message','Sub-Genre has been deleted successfully');
    }

    public function checkSubGenreExist(Request $request)
    {
        $genre_id= $request->get('id');
        $id = $request->get('parent_id');
        $campaignGenres = \App\CampaignGenres::where('genre_id',$genre_id)->first();
        $artistGenres = \App\ArtistGenres::where('genre_id',$genre_id)->first();

        if($campaignGenres || $artistGenres){
            \Session::flash('error','Can not delete this sub-genre because it is in use');
            $title = 'Sub-Genres';
            return view('admin.genres.sub-genres',compact('title','id'));
        }

        else{
            return response('notExist');
        }

    }
}
