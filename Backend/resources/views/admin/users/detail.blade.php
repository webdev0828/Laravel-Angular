@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li><a href="{!! url('/admin/users') !!}">Users</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
</div>
<div class="page-title">
    <div class="container">
        <h3>{{ $title }}</h3>
    </div>
</div>
@stop
@section('content')
<div class="panel panel-white">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center"> 
                    <label>Profile Images</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! asset(!empty($user->userprofile->avatar) ? asset('/timthumb.php?src='.$user->userprofile->avatar.'&w=500&h=500&q=100') : Config::get('constants.default_track_image')) !!}" class="img-thumbnail img-responsive" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="User Pic">
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center">
                    <label> Banner Image</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! asset(!empty($user->userprofile->cover) ? asset('/timthumb.php?src='.$user->userprofile->cover.'&w=500&h=500&q=100') : Config::get('constants.default_track_image')) !!}"  class="img-thumbnail img-responsive" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="User Pic">
                    </div>
                    <div class="clearfix">&nbsp;</div>                
                </div>
            </div>
            <div class=" col-md-9 col-lg-9 ">
                <table class="table table-user-information detail-table table-bottom-bordered">
                    <tbody>
                        <tr>
                            <td class="col-md-3"><b>Profile Name :</b></td>
                            <td class="col-md-9">{!! $user->userprofile->name ? $user->userprofile->name : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>First Name :</b></td>
                            <td class="col-md-9">{!! $user->userprofile->first_name ? $user->userprofile->first_name : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Surname :</b></td>
                            <td class="col-md-9">{!! $user->userprofile->last_name ? $user->userprofile->last_name : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Email :</b></td>
                            <td class="col-md-9">{!! $user->email ? HTML::mailto($user->email) : '-' !!}</td>
                        </tr>
                        <!-- 
                        <tr>
                            <td class="col-md-3"><b>Website :</b></td>
                            <td class="col-md-9">{!! $user->userprofile->website ? $user->userprofile->website : '-' !!}</td>
                        </tr> -->
                        <tr>
                            <td class="col-md-3"><b>Location :</b></td>
                            <td class="col-md-9">{!! $user->userprofile->country && $user->userprofile->city ? $user->userprofile->city.', '.$user->userprofile->country : '-' !!}</td>
                        </tr>
                        <!-- <tr>
                            <td class="col-md-3"><b>Genres :</b></td>
                            <td class="col-md-9">
                                @if(@count($user->genres) > 0)
                                    @foreach($user->genres as $key => $genre) 
                                        {!! $genre->name !!}
                                        {!! $key+1 != count($user->genres) ? ',' :''  !!}
                                    @endforeach
                                @else
                                    {!! '-' !!}
                                @endif
                            </td>
                        </tr> -->
                        <tr>
                            <td class="col-md-3"><b>Bio Data :</b></td>
                            <td class="col-md-9">{!! $user->userprofile->bio ? $user->userprofile->bio : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Souncloud URL :</b></td>
                            <td class="col-md-9">
                                <a target="_blank" href="{!! $user->userprofile->souncloud_url ? $user->userprofile->souncloud_url : 'javascript:;' !!}">
                                    {!! $user->userprofile->souncloud_url ? $user->userprofile->souncloud_url : '-' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Facebook URL :</b></td>
                            <td class="col-md-9">
                                <a target="_blank" href="{!! $user->userprofile->facebook_url ? $user->userprofile->facebook_url : 'javascript:;' !!}">
                                    {!! $user->userprofile->facebook_url ? $user->userprofile->facebook_url : '-' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Twitter URL :</b></td>
                            <td class="col-md-9">
                                <a target="_blank" href="{!! $user->userprofile->twitter_url ? $user->userprofile->twitter_url : 'javascript:;' !!}">
                                    {!! $user->userprofile->twitter_url ? $user->userprofile->twitter_url : '-' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Youtube URL :</b></td>
                            <td class="col-md-9">
                                <a target="_blank" href="{!! $user->userprofile->youtube_url ? $user->userprofile->youtube_url : 'javascript:;' !!}">
                                    {!! $user->userprofile->youtube_url ? $user->userprofile->youtube_url : '-' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Instagram URL :</b></td>
                            <td class="col-md-9">
                                <a target="_blank" href="{!! $user->userprofile->instagram_url ? $user->userprofile->instagram_url : 'javascript:;' !!}">
                                    {!! $user->userprofile->instagram_url ? $user->userprofile->instagram_url : '-' !!}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop