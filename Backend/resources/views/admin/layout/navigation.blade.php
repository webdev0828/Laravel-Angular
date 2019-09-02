<div class="overlay"></div>
<form class="search-form" action="#" method="GET">
    <div class="input-group">
        <input type="text" name="search" class="form-control search-input" placeholder="Search...">
        <span class="input-group-btn">
                    <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button"><i class="fa fa-times"></i></button>
                </span>
    </div>
    <!-- Input Group -->
</form>
<!-- Search Form -->
<main class="page-content content-wrap">
    <div class="navbar">
        <div class="navbar-inner container">
            <div class="sidebar-pusher">
                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="logo-box">
                <a href="{{ url('/admin/dashboard') }}" class="logo-text"><span>Sore Thumb Media</span></a>
            </div>
            <!-- Logo Box -->
           {{--  <div class="search-button">
                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>
            </div> --}}
            <div class="topmenu-outer">
                <div class="top-menu">
                    <ul class="nav navbar-nav navbar-left">
                       <!--  <li>
                            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                        </li> -->
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                                <span class="user-name">Welcome {{ Auth::user()->name }}<i class="fa fa-angle-down"></i></span> @if(Auth::user()->image != '')
                                <img width="40" height="40" alt="" src="{{ asset('uploads/admin/'.Auth::user()->image)}}" class="img-circle avatar"> @endif
                            </a>
                            <ul class="dropdown-menu dropdown-list" role="menu">
                                <li role="presentation"><a href="javascript:void(0);" class="profileEdit" id="{{ url('admin/profile/edit') }}"><i class="menu-icon icon-user"></i>Profile</a></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a href="{{URL::to('admin/soundcloud-gating')}}" class=""><i class="fa fa-soundcloud"></i>Soundcloud Setting</a></li>

                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a href="javascript:void(0);" id="drip-feed-setting"><i class="fa fa-hourglass" aria-hidden="true"></i>Drip Feed Setting</a></li>

                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a href="{{ url('/admin/logout') }}"><i class="menu-icon icon-logout"></i>Log out</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic hidden" id="showRight">
                                <i class="fa fa-comments"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- Nav -->
                </div>
                <!-- Top Menu -->
            </div>
        </div>
    </div>
    <!-- Navbar -->
    @include('admin.layout.dripfeed-modal');
    <div class="page-sidebar sidebar horizontal-bar">
        <div class="page-sidebar-inner">
            <ul class="menu accordion-menu">
                <li class="nav-heading"><span>Navigation</span></li>
                <li class="{!! Request::segment(2) == 'dashboard' ? 'active' : '' !!}"><a href="{{ url('admin/dashboard') }}"><span class="menu-icon icon-speedometer"></span><p>Dashboard</p></a></li>

                <?php $users = ['adminusers','users','artists'];?>

                <li class="droplink {!! in_array(Request::segment(2),$users) ? 'active' : '' !!}"><a href="javascript:void(0)"><span class="menu-icon icon-user"></span><p>Users</p></a>
                    <ul class="sub-menu">
                        <li class="{!! Request::segment(2) == 'artists' ? 'active' : '' !!}"><a href="{{URL::to('admin/artists')}}">Artists</a></li>
                        <li class="{!! Request::segment(2) == 'users' ? 'active' : '' !!}"><a href="{{URL::to('admin/users')}}">Users</a></li>
                        <li class="{!! Request::segment(2) == 'adminusers' ? 'active' : '' !!}"><a href="{{URL::to('admin/adminusers')}}">Admin Users</a></li>
                    </ul>
                </li>
               {{--  <li><a href="{{URL::to('admin/queue')}}"><span class="menu-icon icon-speedometer"></span><p></p></a>
                </li> --}}

                <?php $campaigns = ['discover-tracks','music_video_release','remix-tracks'];?>

                <li class="droplink {!! in_array(Request::segment(2),$campaigns) ? 'active' : '' !!}"><a href="javascript:void(0);"><span class="menu-icon icon-music-tone-alt"></span><p>Campaigns</p><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="{!! Request::segment(2) == 'discover-tracks' ? 'active' : '' !!}"><a href="{{URL::to('admin/discover-tracks')}}">Original</a></li>
                        <li class="{!! Request::segment(2) == 'remix-tracks' ? 'active' : '' !!}"><a href="{{URL::to('admin/remix-tracks')}}">Remix</a></li>
                        <li class="{!! Request::segment(2) == 'music_video_release' ? 'active' : '' !!}"><a href="{{URL::to('admin/music_video_release')}}">Music Videos</a></li>

                        <!-- <li><a href="{{URL::to('admin/music-video')}}">Repost</a></li> -->
                    </ul>
                </li>

                <?php $listeningQueue = ['discover','remix','music-video','quality-control'];?>
              
                <!-- <li><a href="{{URL::to('admin/tracks')}}"><span class="menu-icon fa fa-music"></span><p>Tracks</p></a></li> -->
                <li class="check-status droplink {!! in_array(Request::segment(2),$listeningQueue) ? 'active' : '' !!}">
                    <a href="javascript:void(0);"><span class="menu-icon icon-music-tone"></span><p>Listening queue</p><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="quality-control {!! Request::segment(2) == 'quality-control' ? 'active' : '' !!}"><span></span><a href="{{URL::to('admin/quality-control')}}">Quality control</a></li>
                        <li class="discover {!! Request::segment(2) == 'discover' ? 'active' : '' !!}"><span></span><a href="{{URL::to('admin/discover')}}">Discover</a></li>
                        <li class="remix {!! Request::segment(2) == 'remix' ? 'active' : '' !!}"><span></span><a href="{{URL::to('admin/remix')}}">Repost</a></li>
                        <li class="music-video {!! Request::segment(2) == 'music-video' ? 'active' : '' !!}"><span></span><a href="{{URL::to('admin/music-video')}}">STM release</a></li>
                        <li class="remix-competition {!! Request::segment(2) == 'remix-competition' ? 'active' : '' !!}"><span></span><a href="{{URL::to('admin/remix-competition')}}">Remix competition</a></li>
                        <!-- <li class="{!! Request::segment(2) == 'repost' ? 'active' : '' !!}"><a href="{{URL::to('admin/repost')}}">Repost</a></li> -->
                    </ul>
                </li>
                <li class="{!! Request::segment(2) == 'competition' ? 'active' : '' !!}"><a href="{{URL::to('admin/competition')}}"><span class="menu-icon icon-trophy"></span><p>Competitions</p></a></li>
                <li class="{!! Request::segment(2) == 'payment' ? 'active' : '' !!}"><a href="{{URL::to('admin/payment')}}"><span class="menu-icon icon-credit-card"></span><p>Payments</p></a></li>
               
                <?php $settings = ['genres', 'vibes', 'newscategories', 'news', 'faqs', 'demo-limit', 'donation','video_template'] ?>

                <li class="droplink {!! in_array(Request::segment(2),$settings) ? 'active' : '' !!}"><a href="javascript:void(0)"><span class="menu-icon icon-settings"></span><p>Settings</p><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <?php $manage = ['genres', 'vibes', 'newscategories', 'news', 'faqs']; ?>
                        <li class="droplink {!! in_array(Request::segment(2), $manage) ? 'active' : '' !!}"><a href="#"><p>Manage</p><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                {{-- <li><a href="{{URL::to('admin/categories')}}">Manage Categories</a></li> --}}
                                <li class="{!! Request::segment(2) == 'genres' ? 'active' : '' !!}"><a href="{{URL::to('admin/genres')}}">Manage Genres</a></li>
                                <li class="{!! Request::segment(2) == 'vibes' ? 'active' : '' !!}"><a href="{{URL::to('admin/vibes')}}">Manage Vibes</a></li>
                                <li class="{!! Request::segment(2) == 'newscategories' ? 'active' : '' !!}"><a href="{{URL::to('admin/newscategories')}}">News Categories</a></li>
                                <li class="{!! Request::segment(2) == 'news' ? 'active' : '' !!}"><a href="{{URL::to('admin/news')}}">News</a></li>
                                <li class="{!! Request::segment(2) == 'faqs' ? 'active' : '' !!}"><a href="{{URL::to('admin/faqs')}}">FAQ's</a></li>
                            </ul>
                        </li>
                        <li class="{!! Request::segment(2) == 'demo-limit' ? 'active' : '' !!}"><a href="{{URL::to('admin/demo-limit')}}">Demo Limit</a></li>
                        <li class="{!! Request::segment(2) == 'email-notification' ? 'active' : '' !!}"><a href="{{URL::to('admin/email-notification')}}">Email Notification</a></li>

                        <!-- <li class="{!! Request::segment(2) == 'video_template' ? 'active' : '' !!}"><a href="{{URL::to('admin/video_template')}}">Video Template</a></li> -->
                        <!-- <li  class="{!! Request::segment(2) == 'donation' ? 'active' : '' !!}"><a href="{{URL::to('admin/donation')}}"><span class="menu-icon fa fa-money"></span><p>Donations</p></a></li> -->
                    </ul>
                </li>
                @if(!$dripfeed_status)
                    <li><a href="{{URL::to('admin/dripfeed-start')}}" title="Start drip feed"><span class="menu-icon glyphicon glyphicon-play red"></span><p>Start DRIP FEED</p></a></li>
                @else
                    <li><a href="{{URL::to('admin/dripfeed-stop')}}" title="Stop drip feed"><span class="menu-icon glyphicon glyphicon-stop red"></span><p>Stop DRIP FEED</p></a></li>
                @endif
                
            </ul>
        </div>
        <!-- Page Sidebar Inner -->
    </div>
    <!-- Page Sidebar -->
    <div class="page-inner">