<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mood;
use Datatable;

class MoodsController extends Controller
{

    public function index(){
        $title = 'Vibes';
        return view('admin.moods.index',compact('title'));
    }

    function getMoods(){
        $users = \DB::table('moods')->select('id', 'name', 'description','created_at');
        return Datatable::query($users)
        ->showColumns('name','description')
        ->addColumn('created_at', function($model) { return $model->created_at;})
        ->addColumn('actions', function($model) { 
            return '<a href="javascript:void(0);" id="' . \URL::to('admin/vibes/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#moodModal" title="Edit" class="moodsEdit"><i class="fa fa-edit"></i></a> &nbsp;
                    <a href="javascript:void(0);" id="' . route('admin.vibes.destroy',[$model->id]) . '" title="Delete" data-id="'.$model->id.'" class="vibe-delete"><i class="fa fa-trash"></i></a>'; 
        })
        ->searchColumns('name','description')
        ->make();
    }

   
    public function store(){
        $input = \Input::all(); 
        
        $subGenres = Mood::findOrNew($input['moodId']);
        $subGenres->name = $input['name'];
        $subGenres->description = $input['description'];
        $subGenres->save();

        \Session::flash('message','Vibe has been saved successfully');
        return \Redirect::back();
    }

   
    public function edit($id){
        $moods = Mood::find($id);
        return response()->json(['moods' => $moods]);
        // return view('admin.moods.edit', compact('genres'));
    }

 
    public function destroy($id){
        
        $moods = Mood::find($id)->delete();
        \Session::flash('message','Vibe has been deleted successfully');
    }

    public function checkMoodExist(Request $request)
    {
        $mood_id= $request->get('id');
        $campaignMoods = \App\CampaignMoods::where('mood_id',$mood_id)->first();
        
        if($campaignMoods){
            \Session::flash('error','Can not delete this vibe because it is in use');
            $title = 'Vibes';
            return view('admin.moods.index',compact('title'));
        }
        else{
            return response('notExist');
        }

    }
}
