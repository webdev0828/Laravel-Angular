{!! Form::open(array('id' => 'adminUserForm', 'route' => array('admin.adminusers.store'), 'files'=>true)) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Admin User</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="name" id="name-input" class="form-control" placeholder="Name" value="" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email-input" class="form-control" placeholder="Email" value="" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                </div>
                <div class="form-group">
                    <textarea name="bio" rows="5" class="form-control" placeholder="Bio data"></textarea>
                </div>
                <div class="form-group">
                    <input type="text" name="soundcloud_url" id="soundcloud_url_input" class="form-control" placeholder="Soundcloud URL" value="">
                </div>
                <div class="form-group">
                    <input type="text" name="facebook_url" id="facebook_url_input" class="form-control" placeholder="Facebook URL" value="">
                </div>
                <div class="form-group">
                    <input type="text" name="twitter_url" id="twitter_url_input" class="form-control" placeholder="Twitter URL" value="">
                </div>
                <div class="form-group">
                    <input type="text" name="youtube_url" id="youtube_url_input" class="form-control" placeholder="Youtube URL" value="">
                </div>
                <div class="form-group">
                    <input type="text" name="instagram_url" id="instagram_url_input" class="form-control" placeholder="Instagram URL" value="">
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        <input class="upload form-control" id="image" accept="image/*" name="image" type="file">
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