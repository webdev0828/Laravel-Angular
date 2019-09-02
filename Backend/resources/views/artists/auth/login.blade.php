@extends('artists.auth.app')
@section('content')
    <main class="page-content">
        <div class="page-inner">
            <div id="main-wrapper">
                <div class="row">
                    <div class="col-md-4 center">
                        <div class="login-box">
                            <a href="{{ url('/') }}" class="logo-name text-lg text-center"><img width="400" height="54" border="0" alt="Sore Thumb Media" src="{{asset('images/logo.png')}}"></a>
                            <p class="text-center m-t-md">Please login into your account.</p>
                            <form class="m-t-md" name="loginForm" method="post" ng-submit="loginForm.$valid && submitLogin()" novalidate>
                                <div class="form-group" ng-class="{ 'has-error': loginForm.email.$touched && loginForm.email.$invalid }">
                                    <input type="email" class="form-control" name="email" placeholder="Email" ng-model="email" required>
                                    <div class="help-block" ng-messages="loginForm.email.$error" ng-if="loginForm.email.$touched">
                                        <p ng-message="required">Please enter email address.</p>
                                        <p ng-message="email">Please enter valid email address.</p>
                                    </div>
                                    <span class="error" ng-show="messages['email'][0]"><% messages['email'][0] %></span>
                                </div>

                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password" ng-model="password">
                                </div>
                                <span class="error" ng-show="messages['password'][0]"><% messages['password'][0] %></span>
                                <button type="submit" class="btn btn-success btn-block" >Login</button>
                                <a class="display-block text-center m-t-md text-sm" href="{{ url('artist/forgot-pass') }}">Forgot Your Password?</a>
                            </form>
                            <p class="text-center m-t-xs text-sm">{{ date('Y') }} &copy; Sore Thumb Media.</p>
                        </div>
                    </div>
                </div><!-- Row -->
            </div><!-- Main Wrapper -->
        </div><!-- Page Inner -->
    </main><!-- Page Content -->
@endsection
