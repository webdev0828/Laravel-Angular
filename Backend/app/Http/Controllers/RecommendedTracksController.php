<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Datatable;
use App\RecommendedTrack;

class RecommendedTracksController extends Controller
{
    public function index()
    {
        $title = 'Recommended Tracks';
        return view('admin.recommended_tracks.index',compact('title'));
    }

    function getRecommendedTracks()
    {
        $query = RecommendedTrack::select('id','name','file','artwork_file','background_image');

        return Datatable::query($query)
        ->showColumns('name')
        ->addColumn('actions', function($model) { return
        '<a href="javascript:void(0);" id="' . \URL::to('admin/recommended_tracks/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#recommendedtrackEditModal" title="Edit" class="recommendedtrackEdit"><i class="fa fa-edit"></i></a> &nbsp;
         <a href="javascript:void(0);" id="' . \URL::to('admin/recommended_tracks/'.$model->id) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a>'; })
        ->searchColumns('name')
        ->orderColumns('id','name')
        ->make();
    }

    public function create()
    {
        return view('admin.recommended_tracks.create');
    }

    public function edit($id)
    {
        $track = RecommendedTrack::find($id);
        return view('admin.recommended_tracks.edit', compact('track'));
    }

    public function store(Request $request, RecommendedTrack $recommendedTrack)
    {
        $input = $request->all();
        if($request->hasFile('file')){
            $originalFile = $request->file('file');
            $filename = pathinfo($originalFile->getClientOriginalName(), PATHINFO_FILENAME);
            $filetype = 'file';
            $destinationPath = 'uploads/recommended_tracks/file';
            $fileName = \App\libraries\GlobalHelper::uploadFile($originalFile, $destinationPath, $filename, $filetype);
            $input['file'] = $destinationPath.'/'.$fileName;
        }

        if($request->hasFile('artwork_file')){
            $originalFile = $request->file('artwork_file');
            $destinationPath = 'uploads/recommended_tracks/artwork_file';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $input['artwork_file'] = $destinationPath.'/'.$fileName;
        }

        if($request->hasFile('background_image')){
            $originalFile = $request->file('background_image');
            $destinationPath = 'uploads/recommended_tracks/background_image';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $input['background_image'] = $destinationPath.'/'.$fileName;
        }

        $recommendedTrack->create($input);

        \Session::flash('message','Recommended Track has been successfully saved');
        return \Redirect::route('admin.recommended_tracks.index');
    }

    public function update(Request $request, RecommendedTrack $recommendedTrack, $id)
    {
        $input = $request->all();
        $recommendedTrack = $recommendedTrack->findOrFail($id);

        if($request->hasFile('file')){
            $originalFile = $request->file('file');
            $filename = pathinfo($originalFile->getClientOriginalName(), PATHINFO_FILENAME);
            $filetype = 'file';
            $destinationPath = 'uploads/recommended_tracks/file';
            $fileName = \App\libraries\GlobalHelper::uploadFile($originalFile, $destinationPath, $filename, $filetype);
            $input['file'] = $destinationPath.'/'.$fileName;
        }

        if($request->hasFile('artwork_file')){
            $originalFile = $request->file('artwork_file');
            $destinationPath = 'uploads/recommended_tracks/artwork_file';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $input['artwork_file'] = $destinationPath.'/'.$fileName;
        }

        if($request->hasFile('background_image')){
            $originalFile = $request->file('background_image');
            $destinationPath = 'uploads/recommended_tracks/background_image';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $input['background_image'] = $destinationPath.'/'.$fileName;
        }
        $recommendedTrack->update($input);

        \Session::flash('message','Recommended Track has been successfully edited');
        return \Redirect::route('admin.recommended_tracks.index');
    }

    public function destroy($id)
    {
        $categories = RecommendedTrack::find($id);
        $categories->destroy($id);
        \Session::flash('message','Recommended Track has been successfully deleted');
    }
}
