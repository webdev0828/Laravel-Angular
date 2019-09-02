<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image as Image;
use App\News;
use App\Comment; 
use Datatable;
use Validator;
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(News $news)
    {
        $title = "News";
        return view('admin.news.index',compact('title'));
    }

    function getNews()
    {
        $query = \DB::table('news')->join('users','users.id','=','news.user_id')->whereIn('users.user_type',['admin_user','admin'])->select('news.id','news.title', 'news.description', 'news.tags', 'news.image_name', 'news.created_at','users.name');
        return Datatable::query($query)
            ->addColumn('title', function($model) {return ucfirst($model->title);})
             ->addColumn('name', function($model) {return ucfirst($model->name);})
            ->addColumn('created_at', function($model){
                return '<span class="sort-date">'.strtotime($model->created_at).'</span>'.date("d/m/Y", strtotime($model->created_at));
            })
            ->addColumn('actions', function($model) { return 
            '<a href="'.\URL::to('admin/news/'.$model->id.'/edit') . '"><i class="fa fa-edit"></i></a> &nbsp;
             <a href="' . \URL::to('admin/news/'.$model->id) . '/delete" onclick="return confirmDelete();"><i class="fa fa-trash"></i></a>'; })
            ->searchColumns('title')
            ->make();
    }

    public function getComment($id)
    {
        $comments = Comment::getCommentByNews($id);
        $title = "Comments";
        return view('admin.news.comments',compact(['title','comments']));
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        $comment->destroy($id);

        \Session::flash('message','Comment has been deleted successfully');
        // return response()->json(['success'=>'true']);
        return \Redirect::back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \DB::table('news_categories')->lists('name', 'id');
        $adminUsers = \DB::table('users')->whereIn('users.user_type',['admin_user','admin'])->lists('name', 'id');
        $taglist = [];
        $title = 'Add';
        return view('admin.news._form',compact('title', 'categories','taglist','adminUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, News $news)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'image_name'=> 'required',
            'user_id'=>'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect('admin/news/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $input = \Input::all();
        $input['tags'] = implode(',', $input['tags']);

        if($request->hasFile('image_name')){
            $file = $request->file('image_name');
            $destinationPath = 'uploads/news'; //public_path('uploads/news/');
            $fileName = \App\libraries\GlobalHelper::uploadImage($file, $destinationPath);
            $input['image_name'] = $destinationPath.'/'.$fileName;
        }

        $news->create($input);

        $news = \App\News::select('slug','title')->orderBy('created_at','DESC')->first();
        
        $notificationData = [
                                'user_id' => null,
                                'comments'=> 'Sore Thumb Media published a new story <a href="/news/'.$news->slug.'">'.$news->title.'</a>',
                                'type' => 'news',
                                'isGlobal' => 1,
                            ];
        $response = \App\libraries\GlobalHelper::addNotification($notificationData);

        $user = \DB::table('users')->update(['notifications' => \DB::raw(" CASE WHEN notifications IS NULL THEN ".$response->id." ELSE   CONCAT(COALESCE(notifications),',',$response->id) END")]);

        \Session::flash('message','News has been created successfully');
        return \Redirect::route('admin.news.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $adminUsers = \DB::table('users')->whereIn('users.user_type',['admin_user','admin'])->lists('name', 'id');
        // print_r($adminUsers);
        $title = "Edit";
        $categories = \DB::table('news_categories')->lists('name', 'id');
        // $taglist = \DB::table('users')->lists('name', 'name');

        $news = News::find($id);
        $tags = explode(',',$news->tags);
        $news->tags = @array_combine($tags, $tags);
        $taglist = $news->tags;
            // dd($news->tags);
        $image = $news->image_name;
        return view('admin.news._form', compact(['news','title','image','categories','taglist','adminUsers']));
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
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
            'user_id'=>'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            return redirect('admin/news/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $news = News::findOrFail($id);
        $inputs = $request->all();
       $inputs['tags'] = implode(',', $inputs['tags']);


        if($request->hasFile('image_name')){

            

            $filePath = $news->image_name;
            if($filePath){
              \App\libraries\GlobalHelper::deleteFile($filePath);  
            }

            $file = $request->file('image_name');
            $destinationPath = 'uploads/news'; //public_path('uploads/news/');
            $fileName = \App\libraries\GlobalHelper::uploadImage($file, $destinationPath);
            $inputs['image_name'] = $destinationPath.'/'.$fileName;

            
             // uploads/news/5779fe9dcfb50.jpg
            // $file = $request->file('image_name');
            // $data = $this->uploadFileThumbnail($file, 1140, 600);
            // $inputs['image_name'] = $data;
        }
        // $selectedUser =  $inputs['admin_user_type'];
        // if(!empty($selectedUser)){
        //   $inputs['user_id'] = $selectedUser;
        // }
        //$news->user_id = $inputs['admin_user_type'];
        $news->update($inputs);
        
        \Session::flash('message','News has been updated successfully');
        return \Redirect::route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $news = News::find($id);
    //     $news->destroy($id);
    //     \Session::flash('message','News has been successfully deleted');
    //     return response()->json(['success'=>'true']);
    //     // return \Redirect::route('admin.news.index');
    // }

    public function deleteNews($id)
    { 
        $news = News::find($id);
        if($news->image_name){
          \App\libraries\GlobalHelper::deleteFile($news->image_name);  
        }
        $news->destroy($id);
        \Session::flash('message','News has been deleted successfully');
        // return response()->json(['success'=>'true']);
        return redirect('admin/news');
    }


    function uploadFileThumbnail($file, $width, $height)
    {
        if(!empty($file)) {
          $pathData = $file->getRealPath();
          $ext = $file->getClientOriginalExtension();
          $image_name = @uniqid();
          $filename = $image_name.'.'.$ext;
          $destinationPath = public_path('uploads/news/');

          $file = $destinationPath . $filename;
         

          // THEN RESIZE IT
          $returnData = 'uploads/news/' . $filename;
          $image = Image::make($pathData)->resize($width, $height)->save($destinationPath.$filename);                
            if($image){
                return $returnData;
            }else{
                return $returnData = [];
            }
        }     
    } 
}
