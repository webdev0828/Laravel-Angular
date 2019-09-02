<div class="panel panel-default st-panel">
     <div class="panel-heading">
       <h1 class="panel-title">Profile Images</h1>
     </div>
    <div class="panel-body">
       <div class="row">
          <div class="col-md-4">
            <!-- Current avatar -->
            <div class="avatar-view" title="Change the avatar">
              <img src="http://localhost/sorethumbmedia/public/assets/images/profile-picture.png" class="img-circle img-responsive" id="photoAvatar" alt="Avatar">
            </div>
          </div>
          <div class="col-md-8">
              <img src="http://localhost/sorethumbmedia/public/assets/images/profile-cover.png" class="img-responsive banner photoCover" id="photoCover" alt="Image">
          </div>
       </div>
       <div class="row">
          <div class="col-md-4">
            <span class="btn btn-danger btn-file">
             EDIT IMAGE <input type="file" accept="images/*" value="avatar" >
            </span>
          </div>
          <div class="col-md-4">
             <span class="btn btn-danger btn-file">
                           EDIT BANNER <input type="file" id="inputCover" value="cover" >
                   </span>
          </div>
          <div class="col-md-4">
             <span class="btn btn-default btn-file st-btn">
                           TEMPLATES <input type="file">
                   </span>
          </div>
       </div>
       <div class="row">
          <div class="form-title">Social Links</div>
       </div>
       <div class="form-group">
             <label>SoundCloud</label>
             {!! Form::text('soundcloud_url', null, ['id'=>'soundcloud_url', 'class'=>'form-control','placeholder'=>'SoundCloud']) !!}
          </div>
          <div class="form-group">
             <label>Facebook</label>
             {!! Form::text('facebook_url', null, ['id'=>'facebook_url', 'class'=>'form-control','placeholder'=>'Facebook']) !!}
          </div>
          <div class="form-group">
             <label>Twitter</label>
             {!! Form::text('twitter_url', null, ['id'=>'twitter_url', 'class'=>'form-control','placeholder'=>'Twitter']) !!}
          </div>
          <div class="form-group">
             <label>YouTube</label>
             {!! Form::text('youtube_url', null, ['id'=>'youtube_url', 'class'=>'form-control','placeholder'=>'YouTube']) !!}
          </div>
          <div class="form-group">
             <label>Instagram</label>
             {!! Form::text('instagram_url', null, ['id'=>'instagram', 'class'=>'form-control','placeholder'=>'instagram']) !!}
          </div>
          <div class="form-group">
                <button type="submit" class="btn btn-danger">SAVE PROFILE</button>
                <button class="btn btn-default st-btn">DELETE ACCOUNT</button>
            </div>
    </div>
 </div>

 