@extends('artists.auth.app')
@section('content')
<main class="page-content">
    <div class="page-inner">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-4 center">
                    <div class="login-box">
                        <a href="{{ url('/') }}" class="logo-name text-lg text-center"><img width="400" height="54" border="0" alt="Sore Thumb Media" src="{{asset('images/logo.png')}}"></a>
                        <p class="text-center m-t-md">Enter your e-mail address below to reset your password</p>
                        <form class="m-t-md" role="form" method="POST" ng-submit="forgotPassword()">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" ng-model="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            <a href="{{ url('/artist/login') }}" class="btn btn-default btn-block m-t-md">Back</a>
                        </form>
                        <p class="text-center m-t-xs text-sm">{{ date('Y') }} &copy; Sore Thumb Media.</p>
                    </div>
                </div>
            </div><!-- Row -->
        </div><!-- Main Wrapper -->
    </div><!-- Page Inner -->
</main><!-- Page Content -->
@endsection