<div class="panel panel-white">
    <div class="panel-body">
        <div class="row form-group">
            <div class="col-md-6">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" ng-minlength="3" ng-maxlength="12"  required value="<% first_name %>" ng-model="profile.first_name">
                <div ng-messages="userForm.first_name.$error" ng-if="userForm.first_name.$touched || userForm.$submitted">
                   @include('artists.message')
                </div>
            </div>
            <div class="col-md-6">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" ng-minlength="3" ng-maxlength="12"  required value="<% last_name %>" ng-model="profile.last_name">
                <div ng-messages="userForm.last_name.$error" ng-if="userForm.last_name.$touched || userForm.$submitted">
                   @include('artists.message')
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="text" class="form-control" name="email" ng-pattern="/^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/" value="<% email %>" ng-model="profile.email" required>
            <div ng-messages="userForm.email.$error" ng-if="userForm.email.$touched || userForm.$submitted">
               @include('artists.message')
               <p class="help-block text-danger" ng-message="pattern">Emial is not valid</p>
            </div>
        </div>
        <div class="form-group">
            <label>Artist Name</label>
            <input type="text" class="form-control" name="name" ng-minlength="3" ng-maxlength="12"  required value="<% name %>" ng-model="profile.name">
            <div ng-messages="userForm.name.$error" ng-if="userForm.name.$touched || userForm.$submitted">
                   @include('artists.message')
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label>Country</label>
                <select type="text" name="country" required value="<% country %>" class="form-control" ng-model="profile.country">
                    <option value="">Please select</option>
                    <option value="India">India</option>
                    <option value="USA">USA</option>
                </select>
                <div ng-messages="userForm.country.$error" ng-if="userForm.country.$touched || userForm.$submitted">
                   @include('artists.message')
                </div>
            </div>
            <div class="col-md-6">
                <label>Town / City</label>
                 <select name="city" class="form-control" required value="<% city %>" ng-model="profile.city">
                    <option value="">Please select</option>
                    <option value="Pune">Pune</option>
                    <option value="Mumbai">Mumbai</option>
                </select>
                <div ng-messages="userForm.city.$error" ng-if="userForm.city.$touched || userForm.$submitted">
                   @include('artists.message')
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Website</label>
            <input type="text" class="form-control" ng-pattern="/^(((?:http|ftp)s?:\/\/)|(?:www).?)(?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)|localhost|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::\d+)?(?:\/?|[\/?]\S+)$/i" value="<% website %>" name="website" required ng-model="profile.website">
            <div ng-messages="userForm.website.$error" ng-if="userForm.website.$touched || userForm.$submitted">
               @include('artists.message')
               <p class="help-block text-danger" ng-message="pattern">Website Url is invalid (it should be like : http://, https:// or www.xyz.com)</p>
            </div>
        </div>
        <div class="form-group">
            <label>Mobile No</label>
            <input type="num" class="form-control" ng-pattern="/^[1-9]{1}[0-9]{9}$/" required value="<% phone %>" name="phone" ng-model="profile.phone">
            <div ng-messages="userForm.phone.$error" ng-if="userForm.phone.$touched || userForm.$submitted">
               @include('artists.message')
               <p class="help-block text-danger" ng-message="pattern">Please Enter 10 Digit</p>
            </div>
        </div>
        <div class="form-group">
            <label>Genre (You can pick up 3)</label>
            <div class="row">
                <label class="form-group col-md-3" ng-repeat="role in roles">
                    <input type="checkbox" checklist-model="user.roles" ng-change="check(role)" checklist-value="role"> <% role %>
                </label>
            </div>
            <div class="help-block text-danger" ng-show="genre_error"> 
                You can pick only 3 genre
            </div>
            <div class="help-block text-danger" ng-show="genre_req">
                <div ng-if="userForm.$submitted"> This field is required </div>
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" name="password" ng-model="profile.password" class="form-control">
           {{--  <span class="error" ng-show="messages['password'][0]"><% messages['password'][0] %></span> --}}
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" class="form-control" ng-model="profile.password_confirmation" name="password_confirmation">
          <span class="error" ng-show="messages['password'][0]"><% messages['password'][0] %></span>
        </div>    
    </div>
</div>