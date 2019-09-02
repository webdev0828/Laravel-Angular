<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatable;
use App\User;
use Mail;
use File;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Users';
        return view('admin.users.index',compact('title'));
    }

    function getUsers()
    {
        // $user_type = \Auth::user()->user_type;
        $users = \DB::table('users')
                    ->where('users.user_type', '=', 'stm_user')
                    ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                    ->select('users.id','users.name', 'users.email', 'user_profiles.country', 'user_profiles.city','users.created_at','users.status');

        return Datatable::query($users)
        ->addColumn('name', function($model) { return ucfirst($model->name); })
        ->addColumn('email', function($model) { return $model->email; })
        ->addColumn('location', function($model){
          return $model->city && $model->country ? $model->city.', '.$model->country : '-';
        })
        ->addColumn('created_at', function($model){
          return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date('d/m/Y', strtotime($model->created_at));
        })
        ->addColumn('status', function($model) { 
          $status = 'Active';
          if($model->status == 0){
            $status = 'Inactive';
          }
          return $status;
        })
        ->addColumn('actions', function($model) { return 
        '<a href="javascript:void(0);" id="' . \URL::to('admin/users/'.$model->id.'/edit') . '" data-toggle="modal" data-target="#UserEditModal" title="Edit" class="StmUserEdit"><i class="fa fa-edit"></i></a> &nbsp;
         <a href="javascript:void(0);" id="' . \URL::to('admin/users/'.$model->id) . '" title="Delete" data-method="DELETE"><i class="fa fa-trash"></i></a> &nbsp;
         <a href="javascript:void(0);" class="sendMailToUser" id="' . \URL::to('admin/users/sendmail/'.$model->id) . '"><i title="Send email" class="fa fa-envelope"></i></a> &nbsp;
         <a href="' . \URL::to('admin/users/'.$model->id) . '"><i title="Show info" class="fa fa-info-circle"></i></a>'; })
        ->searchColumns('users.name','users.email')
        ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        
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
        $user = User::with(['userprofile','genres'=>function($q){
                                            $q->select('name');
                              }])->find($id);
        $title = ucfirst($user->userprofile->name);
        if (is_null($user))
        {
            return \Redirect::route('admin.users.index');
            \Session::flash('error','User not found');
        }

        return view('admin.users.detail', compact('user','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        
        if (is_null($user))
        {
            return \Redirect::route('admin.users.index');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function uniqueEmail()
    {
        $email = \Input::get('email');
        $user_id = \Input::get('user_id');
        
        $return = false;
        
        $query = \DB::table('users')
                ->where('users.email', '=', $email);
        
        if(!empty($user_id))
        {
            $query->where('users.id', '<>', $user_id);
        }
        
        $user = $query->get();
                
        if(!empty($user))
        {
            $return = false;
        }
        else
        {
            $return = true; 
        }
        
        echo json_encode($return);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();

        $user = User::findOrFail($id);
        
        $user->user_type = 'stm_user';
        $user->name = $request->name;
        $user->email = $request->email;
        $status = 0;
        if($request->input('status') == 1){
            $status = 1;
        }
        $user->status = $status;
        if ($request->has('password'))
        {
            $user->password = \Hash::make($request->input('password'));
            $this->passwordChangeMail($inputs, $request->input('password'));
        }        
        $user->save();

        $validator = User::validate($id, $inputs);

        if ($validator->fails())
        {
            return \Redirect::back()->withErrors($validator)->withInput();
            // $messages = $validator->errors();
            // $messages->all();
        }
        
        \Session::flash('message','User has been updated successfully');
        return \Redirect::route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $stm_user = \App\UserProfile::where('user_id',$id)->first()->delete();
        $users = User::find($id);
        $users->destroy($id);
        \Session::flash('message','User has been deleted successfully');
    }

    public function editProfile()
    {
        $user = User::with('adminUser')->find(\Auth::id());
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $id = $request->get('user_id');
        $user = User::findOrFail($id);

        // $user->user_type = 'admin';
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        
        if ($request->has('password'))
        {
            $user->password = \Hash::make($request->input('password'));
        }

        $user->save();

        $admin_user = \App\AdminUser::where('user_id', $id)->first();
        
        $admin_user->name = $request->input('name');
        $admin_user->bio = $request->input('bio');
        $admin_user->soundcloud_url = $request->input('soundcloud_url');
        $admin_user->facebook_url = $request->input('facebook_url');
        $admin_user->twitter_url = $request->input('twitter_url');
        $admin_user->youtube_url = $request->input('youtube_url');
        $admin_user->instagram_url = $request->input('instagram_url');

        if($request->hasFile('image')){
            $originalFile = $request->file('image');
            $destinationPath = 'uploads/admin';
            $fileName = \App\libraries\GlobalHelper::uploadImage($originalFile, $destinationPath);
            $admin_user->image = $destinationPath.'/'.$fileName;
        }

        $admin_user->save();

        \Session::flash('message','Profile has been updated successfully');
        return \Redirect::back();
    }

    function passwordChangeMail($user, $password)
    {
        $data = array(
                    'username'  =>  $user['name'],
                    'pass'      =>  $password,
                    'email'     =>  $user['email'],
                    'subject'   =>  "Password changed"
                );
        
        $response = \App\libraries\MailHelper::sendEmail('emails.changed_pass', $data);


        // $subject = "Password changed";
        // Mail::send('emails.changed_pass', ['username' => $user['name'], 'pass'=> $password], function ($m) use ($user, $subject) {
        //   $m->to($user['email'], $user['name'])->subject($subject);
        // });
    }

    function sendMail($id)
    {
        $user = \App\UserProfile::where('user_id',$id)->first();
        return view('admin.users.mail', compact('user'));
    }

    function sendmailtoUser()
    {
        $id = \Input::get('user_id');
        $user = User::findOrFail($id);
        // $subject = \Input::get('subject');

        $data = array(
                    'username'      =>  $user->name,
                    'mail_message'  =>  \Input::get('message'),
                    'email'         =>  $user->email,
                    'subject'       =>  \Input::get('subject')
                );
        
        $response = \App\libraries\MailHelper::sendEmail('emails.user_email', $data);
        
        // Mail::send('emails.user_email', ['username' => $user->name, 'mail_message' => \Input::get('message')], function ($m) use ($user, $subject) {
        //   $m->to($user->email, $user->name)->subject($subject);
        // });

        \Session::flash('message','Mail has been sent successfully');
        return \Redirect::route('admin.users.index');
    }

}
