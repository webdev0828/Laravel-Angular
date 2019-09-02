{!! Form::model($user, array('id' => 'adminUserForm', 'method' => 'PATCH','route' => array('admin.adminusers.update', $user['id']), 'files'=>true)) !!}
<input type="hidden" id="user_id"  value="@if(!empty($user['id']) ){{ $user['id'] }}@endif">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Admin User</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="name-input" class="form-control" placeholder="Name" value="@if(!empty($user['name']) ){{ $user['name'] }}@endif" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email-input" class="form-control" placeholder="Email" value="@if(!empty($user['email']) ){{ $user['email'] }}@endif" readonly required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                    <label>Confirm password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <?php   $active = '';
                                $inactive = '';
                            ?>
                        @if(!empty($user['status']) && $user['status'] == '1')
                            <?php $active = 'selected="selected"';?>
                        @endif

                        @if($user['status'] == '0')
                            <?php $inactive = 'selected="selected"';?>
                        @endif
                        <option value="1" {{ $active }}>Active</option>
                        <option value="0" {{ $inactive }}>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Biodata</label>
                    <textarea name="bio" rows="5" class="form-control">@if(!empty($user['admin_user']['bio']) ){!! $user['admin_user']['bio'] !!}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Soundcloud</label>
                    <input type="text" name="soundcloud_url" id="soundcloud_url_input" class="form-control" placeholder="Soundcloud URL" value="@if(!empty($user['admin_user']['soundcloud_url']) ){!! $user['admin_user']['soundcloud_url'] !!}@endif">
                </div>
                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text" name="facebook_url" id="facebook_url_input" class="form-control" placeholder="Facebook URL" value="@if(!empty($user['admin_user']['facebook_url']) ){!! $user['admin_user']['facebook_url'] !!}@endif">
                </div>
                <div class="form-group">
                    <label>Twitter</label>
                    <input type="text" name="twitter_url" id="twitter_url_input" class="form-control" placeholder="Twitter URL" value="@if(!empty($user['admin_user']['twitter_url']) ){!! $user['admin_user']['twitter_url'] !!}@endif">
                </div>
                <div class="form-group">
                    <label>Youtube</label>
                    <input type="text" name="youtube_url" id="youtube_url_input" class="form-control" placeholder="Youtube URL" value="@if(!empty($user['admin_user']['youtube_url']) ){!! $user['admin_user']['youtube_url'] !!}@endif">
                </div>
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="instagram_url" id="instagram_url_input" class="form-control" placeholder="Instagram URL" value="@if(!empty($user['admin_user']['instagram_url']) ){!! $user['admin_user']['instagram_url'] !!}@endif">
                </div>
                <div class="form-group">
                  <img id="adminUserImagePreview" name="image" src="{!! asset(!empty($user['admin_user']['image']) ? asset('/timthumb.php?src='.$user['admin_user']['image'].'&w=500&h=500&q=100') : Config::get('constants.default_profile_image')) !!}" class="img-thumbnail default-img-lg" id="admin_photoPreview" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_profile_image') !!}'" alt="Admin User Image">
                </div>

                <div class="form-group">
                    {!! Form::label('image', 'Upload Image') !!}
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('image',['class'=>'upload form-control', 'id'=>'adminUserImage', 'accept'=>'image/*']) !!}
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