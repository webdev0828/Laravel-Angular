{!! Form::model($user, array('id' => 'profileForm', 'class'=>'form-horizontal', 'method' => 'POST','url' => 'admin/profile/update', 'files' => true)) !!}
<input type="hidden" id="user_id" name="user_id"  value="@if(!empty($user['id']) ){{ $user['id'] }}@endif">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Profile</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="lable-control col-md-3">Name</label>
                    <div class="col-md-9">
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Email</label>
                    <div class="col-md-9">
                        {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Email']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Password</label>
                    <div class="col-md-9">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Confirm password</label>
                    <div class="col-md-9">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Biodata</label>
                    <div class="col-md-9">
                        {!! Form::textarea('bio', $user->adminUser->bio, array('id'=>'bioText','class' =>'form-control','placeholder' =>'Bio data','rows'=>'3')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Soundcloud</label>
                    <div class="col-md-9">
                        {!! Form::text('soundcloud_url',$user->adminUser->soundcloud_url,['class'=>'form-control','placeholder'=>'Soundcloud URL']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Facebook</label>
                    <div class="col-md-9">
                        {!! Form::text('facebook_url',$user->adminUser->facebook_url,['class'=>'form-control','placeholder'=>'Facebook URL']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Twitter</label>
                    <div class="col-md-9">
                        {!! Form::text('twitter_url',$user->adminUser->twitter_url,['class'=>'form-control','placeholder'=>'Twitter URL']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Youtube</label>
                    <div class="col-md-9">
                        {!! Form::text('youtube_url',$user->adminUser->youtube_url,['class'=>'form-control','placeholder'=>'Youtube URL']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="lable-control col-md-3">Instagram</label>
                    <div class="col-md-9">
                        {!! Form::text('instagram_url',$user->adminUser->instagram_url,['class'=>'form-control','placeholder'=>'Instagram URL']) !!}
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label class="lable-control col-md-3">&nbsp;</label>
                    <div class="col-md-9">
                        <img id="adminProfilePreview" name="image" id="profile_photo_preview" src="{!! asset(!empty($user->adminUser->image) ? asset('/timthumb.php?src='.$user->adminUser->image.'&w=200&h=200&q=100') : Config::get('constants.default_profile_image')) !!}" class="img-thumbnail default-img-sm" alt="Admin User Image">
                    </div>
                </div>
                
                
                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        <div>
                            {!! Form::label('image', 'Upload Image',['class'=>'control-label']) !!}
                        </div>
                        <div class="fileUpload btn btn-primary" id="profile-upload-btn">
                            <span>Browse</span>
                            {!! Form::file('image',['class'=>'upload form-control', 'id'=>'adminProfilePhoto', 'accept'=>'image/*']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}