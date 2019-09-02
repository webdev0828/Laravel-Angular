@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li><a href="{!! url('admin/competition') !!}">Competition</a></li>
        <li class="active">Winner-selection</li>
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
    {!! Form::open(array('id' => 'winnerSelectionForm', 'class'=>'winnerSelectionForm', 'name'=>'winnerSelectionForm', 'url' => 'admin/competition-finalist/store')) !!}
    @if(count($competitionParticipates))
    <input type="hidden" name="_id" value="{!! $id !!}">
    <input type="hidden" value="{!! implode(',', $winnerCampaignIds) !!}" id="winnerCampaignIds" name="">
    <input type="hidden" value="{!! implode(',', $winnerPositionsInfo) !!}" id="winnerPositionsInfo" name="">
    
    <table class="table">
        <thead>
          <tr>
            <th>Artist Name</th>
            <th>Track</th>
            <th>Position</th>
          </tr>
        </thead>
        <tbody>
            @foreach($competitionParticipates as $data)
                <tr>

                    <td>
                        <input type="hidden" name="artistinfo[]" value="{!! $data->artist_id !!}"> 
                        {!! ucfirst($data->artist_name) !!}
                    </td>

                    <td class="div-content-width">
                        <div>
                            {!! Form::select('remix_tracks[]', $remixCampaigns, $data->campaign_id ? $data->campaign_id : '', ['class' => 'js-example-responsive form-control stm-artist-track select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'stm-artist-track'.$data->artist_id]) !!}
                        </div>
                    </td>

                    <td>
                        <select class="form-control positionSelector" id="position{!!$data->artist_id!!}" name="positions[]">
                            <option value="">Select position </option>
                            @for($i = 1; $i<= 10; $i++)
                                @if($data->position && $data->position == $i )
                                    <option value="{!! $i !!}" selected="selected"> {!! $i !!} </option>
                                @else
                                    <option value="{!! $i !!}"> {!! $i !!} </option>
                                @endif
                            @endfor
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <hr>

        <div class="form-group">
            <label class="control-label col-sm-2 label-width">Music Video : </label>
            <div class="col-sm-10 div-content-width">
                <div>
                    {!! Form::select('music_video', $allMusicVideos, $selectedMusicVideo ? $selectedMusicVideo->video_id : '', ['class' => 'js-example-responsive form-control music-videos select2-size', 'tabindex'=>'0', 'size'=>'1', 'multiple'=>'multiple', 'id'=>'music-videos']) !!}
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2 label-width">Publish Result : </label>
            <div class="col-sm-10 div-content-width">
                <div> 
                    @if($isCompetitionPublished) 
                        {!! Form::checkbox('published_date', 1, $isCompetitionPublished, ['class' => 'published_date', 'disabled']) !!}
                    @else
                        {!! Form::checkbox('published_date', 1, null, ['class' => 'published_date']) !!}
                    @endif
                </div>
                <span class="error_message">Winner need to be selected, please select first position</span>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>



    <!-- <div class="col-sm-12 error_message_div"> 
        <div class="alert alert-danger" style="background:#fff; font-size: 16px;">
            <a href="javascript:;" class="close" onClick="$('.error_message_div').hide();" aria-label="close">&times;</a>
            <strong><i class="fa fa-times"> </i></strong> Winner need to be selected, please select first position
        </div>
    </div> -->



    <div class="pull-right">
        <a href="{{URL::to('admin/competition')}}" class="btn btn-default">Cancel</a>
        <button type="submit" id="add-row" class="btn btn-success">Save</button>
    </div>
    @else
    <div class="msg-div">
        <h4 class="">No records found</h4>
    </div>
    @endif
  {!! Form::close() !!} 
    </div>
</div>
@stop

