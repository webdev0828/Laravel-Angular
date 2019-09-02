

@extends('app')

@section('content')
<main class="page-content">
    <div class="page-inner">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-4 center">
                    <div class="login-box">
                        <a href="{!! url('/') !!}" class="logo-name text-lg text-center"><img width="400" height="54" border="0" alt="Sore Thumb Media" src="{!!asset('images/logo.png')!!}"></a>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <p class="text-center m-t-md">Enter your new password</p>
                        <form class="form-horizontal" role="form" method="POST" action="{!! url('/admin/reset-password') !!}">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="token" value="{!! $token !!}">

                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" >Reset Password</button>
                                <a class="display-block text-center m-t-md text-sm" href="{!! url('admin/login') !!}">Back to Login</a>
                            </div>
                        </form>
                        <p class="text-center m-t-xs text-sm">{!! date('Y') !!} &copy; Sore Thumb Media.</p>
                    </div>
                </div>
            </div><!-- Row -->
        </div><!-- Main Wrapper -->
    </div><!-- Page Inner -->
</main><!-- Page Content -->
@endsection

