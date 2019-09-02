@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Campaigns</li>
        <li><a href="{!! url('/admin/'.$mainTitle.'-tracks') !!}">{!! @ucfirst($mainTitle) !!}</a></li>
        <li class="active">{!! $title !!}</li>
    </ol>
</div>
<div class="page-title">
    <div class="container">
        <h3>{!! $title !!}</h3>
    </div>
</div>
@stop
@section('content')
<div class="panel panel-white">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center"> 
                    <label> Cover Image</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! !empty($trackData->cover_image) ? asset('/timthumb.php?src='.$trackData->cover_image.'&w=500&h=500&q=100') : Config::get('constants.default_track_image') !!}" class="img-thumbnail img-responsive" id="admin_photoPreview" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="Cover Image">
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center"> 
                    <label>Background Image</label>
                    <div>
                        <img id="previewPhoto" name="image" src="{!! !empty($trackData->background_file) ? asset('/timthumb.php?src='.$trackData->background_file.'&w=500&h=500&q=100') : Config::get('constants.default_track_image') !!}" class="img-thumbnail img-responsive" id="admin_photoPreview" onError="this.onerror=null; this.src='/{!! Config::get('constants.default_track_image') !!}'" alt="Background Image">
                    </div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="col-sm-6 col-md-12 col-lg-12 info-page" align="center"> 
                    <a href="{!! URL::to($trackData->mp3_file) !!}" download="{!! $trackData->track_name !!}" class="btn btn-success btn-block">Download Track</a>
                </div>
            </div>
            <div class=" col-md-9 col-lg-9 col-sm-12">
                <table class="table table-bottom-bordered">
                    <tbody>
                        <tr>
                            <td class="col-sm-3"><b>Artist :</b></td>
                            <td class="col-sm-9">{!! $trackData->artist->name ? $trackData->artist->name : 'No Artist' !!}</td>
                        </tr>
                        <tr>
                            <td class="col-sm-3"><b>Artist Links :</b></td>
                            <td class="col-sm-9">
                            @if($artistUrls)
                                @foreach($artistUrls as $key => $artistUrl) 
                                    {!! $artistUrl->username !!}
                                    {!! $key+1 != $artistUrls ? ' ' :''  !!}
                                @endforeach
                            @else
                                {!! '-' !!}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="col-sm-3"><b>Genres :</b></td>
                            <td class="col-sm-9">
                            @if($trackData->track_genres)
                                @foreach($trackData->track_genres as $key => $genre)
                                    {!! $genre->name !!}
                                    {!! $key+1 != $trackData->track_genres ? ' ' :''  !!}
                                @endforeach
                            @else
                                {!! '-' !!}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="col-sm-3"><b>Sub Genres :</b></td>
                            <td class="col-sm-9">
                            @if($trackData->track_sub_genres)
                                @foreach($trackData->track_sub_genres as $key => $sub_genre)
                                    {!! $sub_genre->name !!}
                                    {!! $key+1 != $trackData->track_sub_genres ? ' ' :''  !!}
                                @endforeach
                            @else
                                {!! '-' !!}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="col-sm-3"><b>Vibes :</b></td>
                            <td class="col-sm-9">
                            @if($trackData->campaign_moods || $trackData->track_moods)
                                @if($trackData->campaign_moods)
                                    @foreach($trackData->campaign_moods as $key => $campaign_mood)
                                        {!! $campaign_mood->name !!}
                                        {!! $key+1 != $trackData->campaign_moods ? ' ' :''  !!}
                                    @endforeach
                                @else
                                    @foreach($trackData->track_moods as $key => $track_mood)
                                        {!! $track_mood->name !!}
                                        {!! $key+1 != $trackData->track_moods ? ' ' :''  !!}
                                    @endforeach
                                @endif
                            @else
                                {!! '-' !!}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="col-sm-3"><b>Uploaded At :</b></td>
                            <td class="col-sm-9">{!! $trackData->created_at->diffForHumans(); !!}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-responsive">
                    <style type="text/css">
                    .social-table td{text-align: center}
                    </style>
                    <table class="table table-bordered social-table"> 
                        <thead>
                            <th>Social Network</th>
                            <th>Follow</th>
                            <th>Like</th>
                            <th>Tweet</th>
                             <th>Share</th>
                            <th>Subscribe</th>
                            <th>Repost</th>
                        </thead>
                        <tbody>
                            @foreach($socialData as $key => $value)
                                <tr>
                                    <td style="text-align:left"><b>{!! $key !!}</b></td>
                                    <td>{!! isset($value->follow) ? $value->follow == 'true' ? '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                    @if(($key=="soundcloud" || $key=="facebook") && $trackData->type=='remix')
                                       <td> {!! isset($value->like)? $value->like == 'true' ? '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                    @elseif($trackData->type=='original' && $key=="facebook")
                                     <td>{!! isset($value->like)? $value->like == 'true' ? '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                     @else
                                        <td>&nbsp;</td>
                                    @endif

                                    <td>{!! isset($value->tweet) ? $value->tweet == 'true' ?  '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                     
                                    <td>{!! isset($value->share) ? $value->share == 'true' ? '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                    <td>{!! isset($value->subscribe) ? $value->subscribe == 'true' ? '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                    <td>{!! isset($value->repost)  && $trackData->type=='remix' ? $value->repost == 'true' ? '<i class="fa fa-check text-success" aria-hidden="true"></i>': '<i class="fa fa-times text-danger" aria-hidden="true"></i>' : ' ' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop