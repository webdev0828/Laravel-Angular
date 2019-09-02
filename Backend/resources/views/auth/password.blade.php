@extends('app')

@section('content')
<main class="page-content">
    <div class="page-inner">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-4 center">
                    <div class="login-box">
                        <a href="{{ url('/') }}" class="logo-name text-lg text-center"><img width="400" height="54" border="0" alt="Sore Thumb Media" src="{{asset('images/logo.png')}}"></a>
                        <div class="m-t-md">
                        @if (session('status'))
                            <div class="alert alert-success" style="mar">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                {{ session('status') }}
                            </div>
                        @elseif (count($errors) > 0)
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        </div>
                        <p class="text-center">Enter your e-mail address to reset your password</p>
                        <form class="form-horizontal m-t-md" role="form" method="POST" action="{{ url('/admin/password/email') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter your email id" name="email" value="{{ old('email') }}">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" >Send Password Reset Link</button>
                                <a class="display-block text-center m-t-md text-sm" href="{{ url('admin/login') }}">Back to Login</a>
                            </div>
                        </form>
                        <p class="text-center m-t-xs text-sm">{{ date('Y') }} &copy; Sore Thumb Media.</p>
                    </div>
                </div>
            </div><!-- Row -->
        </div><!-- Main Wrapper -->
    </div><!-- Page Inner -->
</main><!-- Page Content -->
@endsection
