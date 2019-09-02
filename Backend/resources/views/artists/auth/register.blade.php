@extends('artists.auth.app')

@section('content')
<div class="container-fluid" ng-init="getValue()">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div style="text-align: center;">
                <a href="{{ url('/') }}" class="logo-name text-lg text-center"><img width="400" height="54" border="0" alt="Sore Thumb Media" src="{{asset('images/logo.png')}}"></a>
                </div>
                <div class="panel-heading"> 
                    <h4>Register</h4>
                </div>
                <div class="panel-body">
                    <form method="post" name="userForm" ng-submit="submitProfile()"  novalidate>
                        <div class="col-md-12 m-t-lg">
                            @include('artists.auth._form')
                            <div class="form-group pull-right">
                                <button type="submit" ng-click="save()" class="btn btn-danger">SAVE PROFILE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
