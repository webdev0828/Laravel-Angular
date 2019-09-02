<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatable;
use Validator;
use App\User;
use Mail;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Admin Users';
        return view('admin.admin_users.index',compact('title'));
    }

    function getUsers()
    {
        $users = \DB::table('users')
                    ->where('user_type', '=', 'admin_user')
                    ->select('id','name', 'status','email','created_at');
        return Datatable::query($users)
        ->addColumn('name', function($model) { return ucfirst($model->name); })
        ->addColumn('email', function($model) { return $model->email; })
        ->addColumn('status', function($model) { return $model->status == 0 ? 'Inactive' : 'Active'; })
        ->addColumn('created_at', function($model) { return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at)); })
        ->addColumn('actions', function($model) { return 
        '<a href="javascript:void(0);" id="' . \URL::to('admin/adminusers/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#adminUserEditModal" title="Edit" class="AdminUserEdit"><i class="fa fa-edit"></i></a> &nbsp;
         <a href="javascript:void(0);" id="' . \URL::to('admin/adminusers/'.$model->id) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a>'; })
        ->searchColumns('name','email')
        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.admin_users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User;
        $user->user_type = 'admin_user';
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = \Hash::make(\Input::get('password'));
        $user->status = 1;
        $user->save();

        $adminUser = new \App\AdminUser;
        $adminUser->user_id = $user->id;
        $adminUser->name = $request->input('name');
        $adminUser->bio = $request->input('bio');
        $adminUser->soundcloud_url = $request->input('soundcloud_url');
        $adminUser->facebook_url = $request->input('facebook_url');
        $adminUser->twitter_url = $request->input('twitter_url');
        $adminUser->youtube_url = $request->input('youtube_url');
        $adminUser->instagram_url = $request->input('instagram_url');
        if($request->hasFile('image')){
            $originalFile = $request->file('image');
            $destinationPath = 'uploads/admin_users/image';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $adminUser->image = $destinationPath.'/'.$fileName;
        }
        $adminUser->save();

        \Session::flash('message','Admin User has been created successfully');
        return \Redirect::route('admin.adminusers.index');
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
        $user = User::with('adminUser')->find($id)->toArray();
        if (is_null($user))
        {
            return \Redirect::route('admin.adminusers.index');
        }

        return view('admin.admin_users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find($id);
        $user->user_type = 'admin_user';
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        if($request->has('password')){
            $user->password = \Hash::make($request->input('password'));
        }
        $user->status = $request->input('status');
        $user->save();

        $adminUser = \App\AdminUser::where('user_id', $user->id)->first();
        $adminUser->user_id = $user->id;
        $adminUser->name = $request->input('name');
        $adminUser->bio = $request->input('bio');
        $adminUser->soundcloud_url = $request->input('soundcloud_url');
        $adminUser->facebook_url = $request->input('facebook_url');
        $adminUser->twitter_url = $request->input('twitter_url');
        $adminUser->youtube_url = $request->input('youtube_url');
        $adminUser->instagram_url = $request->input('instagram_url');
        if($request->hasFile('image')){
            $originalFile = $request->file('image');
            $destinationPath = 'uploads/admin_users/image';
            if($adminUser->image){
              \App\libraries\GlobalHelper::deleteFile($adminUser->image);
            }
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $adminUser->image = $destinationPath.'/'.$fileName;
        }
        $adminUser->save();
        
        \Session::flash('message','User has been updated successfully');
        return \Redirect::route('admin.adminusers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $adminUser = \App\AdminUser::where('user_id', $id)->first();
        if($adminUser->image){
          \App\libraries\GlobalHelper::deleteFile($adminUser->image);
        }
        $adminUser->delete();
        $users = User::find($id);
        $users->delete();

        \Session::flash('message','Admin User has been deleted successfully');
    }

    function passwordChangeMail($user, $password)
    {
        $data = array(
                    'username'  =>  $user['name'],
                    'pass'      =>  $password,
                    'email'     =>  $user['email'],
                    'subject'   =>  'Password changed'
                );
        
        $response = \App\libraries\MailHelper::sendEmail('emails.changed_pass', $data);
        // $subject = "Password changed";
        // Mail::send('emails.changed_pass', ['username' => $user['name'], 'pass'=> $password], function ($m) use ($user, $subject) {
        //   $m->to($user['email'], $user['name'])->subject($subject);
        // });
    }
}
