<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Datatable;

class VideoTemplateController extends Controller
{
    public function index()
    {
        $title = 'STM Video Templates';
        return view('admin.video_templates.index',compact('title'));
    }

    function getVideoTemplates()
    {
        $users = \DB::table('video_templates')->select('id','template_name', 'template_url');
        return Datatable::query($users)
        ->addColumn('template_name', function($model) {return ucfirst($model->template_name);})
        ->addColumn('template_url', function($model) {return '<a href='.$model->template_url.' target="_blank">Play Video</a>';})
        ->addColumn('actions', function($model) { 
            return '<a href="javascript:void(0);" id="' . \URL::to('admin/video_template/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#templatesModal" title="Edit" class="videoTemplateEdit"><i class="fa fa-edit"></i></a> &nbsp;
                    <a href="javascript:void(0);" id="' . route('admin.video_template.destroy',[$model->id]) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a>'; 
        })
        ->searchColumns('template_name')
        ->make();
    }

    public function create()
    {
        return view('admin.video_templates.create');
    }

    public function store()
    {
        $input = \Input::all();
        \App\VideoTemplate::create($input);

        \Session::flash('message','Video Template has been created successfully');
        return \Redirect::route('admin.video_template.index');
    }

    public function edit($id)
    {
        $video = \App\VideoTemplate::find($id);
        return view('admin.video_templates.edit', compact('video'));
    }

    public function update($id)
    {
        $video = \App\VideoTemplate::findOrFail($id);
        $input = \Input::all();
        $video->update($input);
        
        \Session::flash('message','Video Template has been updated successfully');
        return \Redirect::route('admin.video_template.index');
    }

    public function destroy($id)
    {
        $video = \App\VideoTemplate::find($id)->delete();
        
        \Session::flash('message','Video Template has been deleted successfully');
    }
      

}
