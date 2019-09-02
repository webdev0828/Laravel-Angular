<div class="col-md-10" ng-init="getProfile()">
    <section id="profile">
       <form method="post" name="userForm" id="form" ng-submit="updateProfile()"  novalidate>
        <div class="col-md-7 m-t-lg">
            <input type="hidden" name="id" ng-model="profile.id" value="<% id %>">
            @include('artists.auth._form')
        </div>
        <div class="col-md-5 m-t-lg">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <div class="panel-title">Profile Images</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Current avatar -->
                            <div class="avatar-view" title="Change the avatar">
                                <img ng-src="<% profile.avatar ? profile.avatar : 'http://localhost/sorethumbmedia/public/assets/images/profile-picture.png' %>" class="img-circle img-responsive" style="height: 100px;" id="photoAvatar" alt="Avatar">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <img ng-src="<% profile.cover ? profile.cover : 'http://localhost/sorethumbmedia/public/assets/images/profile-cover.png' %>" class="img-responsive banner photoCover" id="photoCover" alt="Image">
                        </div>
                    </div>
                      <br>
                    <div class="row">
                        <div class="col-md-4">
                            {{-- <span class="btn btn-danger btn-file">
                                EDIT IMAGE <input type="file" id="inputAvatar" accept="images/*" file-model="profile.avatar" file-model="profile.avatar" value="<% avatar %>" required>
                            </span> --}}
                        </div>
                        <div class="col-md-4">
                            <span class="btn btn-danger btn-file">
                                EDIT BANNER <input type="file" id="inputCover" file-model="profile.cover" file-model="profile.cover" value="<% cover %>" required>
                            </span>
                        </div>
                        <div class="col-md-3">
                        <button class="btn btn-danger">TEMPLATES</button>
                        </div>
                    </div>
                    <h3>Social Links</h3>
                    <div class="form-group">
                        <label>Soundcloud</label>
                        <input type="text" class="form-control" ng-model="profile.souncloud_url">
                    </div>
                    <div class="form-group">
                        <label>Facebook</label>
                        <input type="text" class="form-control" ng-model="profile.facebook_url">
                    </div>
                    <div class="form-group">
                        <label>Twitter</label>
                        <input type="text" class="form-control" ng-model="profile.twitter_url">
                    </div>
                    <div class="form-group">
                        <label>YouTube</label>
                        <input type="text" class="form-control" ng-model="profile.youtube_url">
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <input type="text" class="form-control" ng-model="profile.instagram_url">
                    </div>
                    <div class="form-group">
                        <button type="submit" ng-click="updateProfile()" class="btn btn-danger">SAVE PROFILE</button>
                        <button class="btn btn-default">DELETE ACCOUNT</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
       
    </section>
    <!-- /#profile --> 
 </div>
 <!-- /.col-xs-12 --> 
      

@include('artists.image-crop') 



