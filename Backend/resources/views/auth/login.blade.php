@extends('app')
@section('content')
<main class="page-content">
    <div class="page-inner">
        <div id="main-wrapper">
            <div class="row">
                <div class="col-md-4 center">
                    <div class="login-box">
                        <a href="{{ url('/') }}" class="logo-name text-lg text-center"><img width="400" height="54" border="0" alt="Sore Thumb Media" src="{!!asset('images/logo.png')!!}"></a>
                        <div class="m-t-md">
                            @if(Session::get('success'))
                                <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    {!! Session::get('success'); !!}
                                </div>
                            @endif
                            
                            @if(count($errors) > 0)
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
                        <p class="text-center m-t-md">Please login into your account.</p>
                        <form class="m-t-md" role="form" method="POST" action="{{ url('/admin/login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Login</button>
                            <a class="display-block text-center m-t-md text-sm" href="{{ url('admin/forgot-password') }}">Forgot Your Password?</a>
                        </form>
                        <p class="text-center m-t-xs text-sm">{{ date('Y') }} &copy; Sore Thumb Media.</p>
                    </div>
                </div>
            </div>
            <!-- Row -->
        </div>
        <!-- Main Wrapper -->
    </div>
    <!-- Page Inner -->
</main>
<!-- Page Content -->
@endsection
