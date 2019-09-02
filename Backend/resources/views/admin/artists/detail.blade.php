@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li><a href="{!! url('/admin/artists') !!}">Artists</a></li>
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
            <div class="col-md-12">
                <h2 class="page-header m-t-10">General Information</h2>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center"> 
                    <label>Profile Image</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! asset(!empty($user->artistprofile->avatar) ? asset('/timthumb.php?src='.$user->artistprofile->avatar.'&w=500&h=500&q=100') : Config::get('constants.default_track_image')) !!}"  class="img-thumbnail img-responsive" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="User Pic">
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center">
                    <label> Banner Image</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! asset(!empty($user->artistprofile->cover) ? asset('/timthumb.php?src='.$user->artistprofile->cover.'&w=500&h=500&q=100') : Config::get('constants.default_track_image')) !!}" class="img-thumbnail img-responsive" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="Cover Pic">
                    </div>
                    <div class="clearfix">&nbsp;</div>                
                </div>
            </div>
            <div class=" col-md-9 col-lg-9 ">
                <!-- <div class="table-responsive"> -->
                    <table class="table table-bottom-bordered">
                        <tbody>
                            <tr>
                                <td class="col-md-3"><b>Profile Name :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->name ? $user->artistprofile->name : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>First Name :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->first_name ? $user->artistprofile->first_name : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Surname :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->last_name ? $user->artistprofile->last_name : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Email :</b></td>
                                <td class="col-md-9">{!! $user->email ? HTML::mailto($user->email) : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Paypal :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->paypal_email ? $user->artistprofile->paypal_email : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Website :</b></td>
                                <td class="col-md-9">
                                    <a target="_blank" href="{!! $user->artistprofile->website !!}">
                                        {!! $user->artistprofile->website ? $user->artistprofile->website : '-' !!}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Genres :</b></td>
                                <td class="col-md-9">
                                    @if(@count($user->artistGenres) > 0)
                                        @foreach($user->artistGenres as $key => $genre) 
                                            {!! $genre->name !!}
                                            {!! $key+1 != count($user->artistGenres) ? ',' :''  !!}
                                        @endforeach
                                    @else
                                        {!! '-' !!}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Location :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->country && $user->artistprofile->city ? $user->artistprofile->country.', '.$user->artistprofile->city : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Bio Data :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->bio ? $user->artistprofile->bio : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Youtube Channel :</b></td>
                                <td class="col-md-9">{!! $user->artistprofile->youtube_channel ? $user->artistprofile->youtube_channel : '-' !!}</td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Souncloud URL :</b></td>
                                <td class="col-md-9">
                                    <a target="_blank" href="{!! $user->artistprofile->souncloud_url ? $user->artistprofile->souncloud_url : 'javascript:;' !!}">
                                        {!! $user->artistprofile->souncloud_url ? $user->artistprofile->souncloud_url : '-' !!}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Facebook URL :</b></td>
                                <td class="col-md-9">
                                    <a target="_blank" href="{!! $user->artistprofile->facebook_url ? $user->artistprofile->facebook_url : 'javascript:;' !!}">
                                    {!! $user->artistprofile->facebook_url ? $user->artistprofile->facebook_url : '-' !!}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Twitter URL :</b></td>
                                <td class="col-md-9">
                                    <a target="_blank" href="{!! $user->artistprofile->twitter_url ? $user->artistprofile->twitter_url : 'javascript:;' !!}">
                                        {!! $user->artistprofile->twitter_url ? $user->artistprofile->twitter_url : '-' !!}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Youtube URL :</b></td>
                                <td class="col-md-9">
                                    <a target="_blank" href="{!! $user->artistprofile->youtube_url ? $user->artistprofile->youtube_url : 'javascript:;' !!}">
                                        {!! $user->artistprofile->youtube_url ? $user->artistprofile->youtube_url : '-' !!}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3"><b>Instagram URL :</b></td>
                                <td class="col-md-9">
                                    <a target="_blank" href="{!! $user->artistprofile->instagram_url ? $user->artistprofile->instagram_url : 'javascript:;' !!}">
                                        {!! $user->artistprofile->instagram_url ? $user->artistprofile->instagram_url : '-' !!}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <!-- </div> -->
                <div class="table-responsive">
                    <table class="table table-bordered social-table"> 
                        <thead>
                            <th>Social Network</th>
                            <th>Follow</th>
                            <th>Like</th>
                            <th>Tweet</th>
                            <th>Comment</th>
                            <th>Share</th>
                            <th>Subscribe</th>
                            <th>Repost</th>
                        </thead>
                        <tbody>
                            @foreach($social as $key => $value)
                                <tr>
                                    <td><b>{!! $key !!}</b></td>
                                    <td>{!! $value['follow_count'] != 0 ? $value['follow_count'] :'-' !!}</td>
                                    <td>{!! $value['like_count'] != 0 ? $value['like_count'] :'-' !!}</td>
                                    <td>{!! $value['tweet_count'] != 0 ? $value['tweet_count'] :'-' !!}</td>
                                    <td>{!! $value['comment_count'] != 0 ? $value['comment_count'] :'-' !!}</td>
                                    <td>{!! $value['share_count'] != 0 ? $value['share_count'] :'-' !!}</td>
                                    <td>{!! $value['subscribe_count'] != 0 ? $value['subscribe_count'] :'-' !!}</td>
                                    <td>{!! $value['repost_count'] != 0 ? $value['repost_count'] :'-' !!}</td>
                                </tr>
                            @endforeach
                            @if(isset($diff))
                                @foreach($diff as $d)
                                    <tr><td><b>{!! $d !!}</b></td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if(isset($user->lableRelease->track_name) && !empty($user->lableRelease->track_name))
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-header m-t-10">Label Release</h2>
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center"> 
                    <label>Track image</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! asset(!empty($user->lableRelease->album_image) ? asset('/timthumb.php?src='.$user->lableRelease->album_image.'&w=150&h=150&q=100') : Config::get('constants.default_track_image')) !!}" width="150" height="150" class="img-thumbnail img-responsive" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="Cover Pic">
                    </div>
                    <div class="clearfix">&nbsp;</div>                
                </div>
            </div>
            <div class=" col-md-9 col-lg-9 ">
                <table class="table table-bottom-bordered">
                    <tbody>
                        <tr>
                            <td class="col-md-3"><b>Track Name :</b></td>
                            <td class="col-md-9">{!! $user->lableRelease->track_name ? $user->lableRelease->track_name : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Recorded Label :</b></td>
                            <td class="col-md-9">{!! $user->lableRelease->record_label ? $user->lableRelease->record_label : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Link to purchase :</b></td>
                            <td class="col-md-9">{!! $user->lableRelease->purchase_link ? $user->lableRelease->purchase_link : '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-md-3"><b>Genres :</b></td>
                            <td class="col-md-9">
                                @if(@count($user->artistLabelGenres) > 0)
                                    @foreach($user->artistLabelGenres as $key => $genre) 
                                        {!! $genre->name !!}
                                        {!! $key+1 != count($user->artistLabelGenres) ? ',' :''  !!}
                                    @endforeach
                                @else
                                    {!! '-' !!}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@stop