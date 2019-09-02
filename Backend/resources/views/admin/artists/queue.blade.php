@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{{ url('admin/dashboard') }}">Home</a></li>
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
        <div class="search p bg-light m-b-sm">
            <div class="input-group">
                <input type="text" placeholder="Search..." class="form-control input-search" name="search" id="track_search">
                <span class="input-group-btn">
                        <button type="button" class="btn btn-success" id="track_search_button"><i class="fa fa-search"></i></button>
                    </span>
            </div>
        </div>
        <div id="successMessage"></div>
        <div role="tabpanel">
            <ul role="tablist" class="nav nav-tabs">
                {{-- dropdown tab for video & discover --}}
                <li role="presentation" class="dropdown active"> 
                    <a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown" aria-controls="myTabDrop1-contents" aria-expanded="false">Select <span class="caret"></span></a>
                    <ul class="dropdown-menu" aria-labelledby="myTabDrop1" id="myTabDrop1-contents">
                        <li class="active"><a href="#dropdown1" role="tab" id="dropdown1-tab" data-toggle="tab" aria-controls="dropdown1" aria-expanded="true">Video</a>
                        </li>
                        <li class=""><a href="#dropdown2" role="tab" id="dropdown2-tab" data-toggle="tab" aria-controls="dropdown2" aria-expanded="false">Discover</a>
                        </li>
                    </ul>
                </li>
                {{-- // --}}
                <li role="presentation"><a data-toggle="tab" role="tab" href="#all" id="unregister_tab" class="tab_click">Unregistered<span class="badge badge-default pull-right m-l-xs" id="unregistered_count">{{$unregistered->count()}}</span></a>
                </li>
                <li class="" role="presentation"><a data-toggle="tab" role="tab" href="#members" id="members_tab" class="tab_click">Members<span class="badge badge-success pull-right m-l-xs" id="members_count">{{ $members->count() }}</span></a>
                </li>
                <li class="" role="presentation"><a data-toggle="tab" role="tab" href="#pro_users" id="prousers_tab" class="tab_click">Pro Users<span class="badge badge-primary pull-right m-l-xs" id="prousers_count">{{ $prousers->count() }}</span></a>
                </li>
                <li class="" role="presentation"><a data-toggle="tab" role="tab" href="#fav_users" id="favtracks_tab" class="tab_click">Fav<span class="badge badge-info pull-right m-l-xs" id="favourites_count">{{ $favourites->count() }}</span></a>
                </li>
                <li class="" role="presentation"><a data-toggle="tab" role="tab" href="#bin_users" id="bintracks_tab" class="tab_click">Bin<span class="badge badge-default pull-right m-l-xs" id="deleted_count">{{ $deletedTracks->count() }}</span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="all" class="tab-pane fade in p-v-lg active" role="tabpanel">
                    <table class="" id="unregistered_table">
                        <thead>
                            <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="members" class="tab-pane fade p-v-lg" role="tabpanel">
                    <table class="" id="members_table">
                        <thead>
                            <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="pro_users" class="tab-pane fade p-v-lg" role="tabpanel">

                    <table class="" id="pro_users_table">
                        <thead>
                            <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="fav_users" class="tab-pane fade p-v-lg" role="tabpanel">
                    <table class="" id="fav_tracks_table">
                        <thead>
                            <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="bin_users" class="tab-pane fade p-v-lg" role="tabpanel">
                    <table class="" id="bin_tracks_table">
                        <thead>
                            <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="dropdown1">Video</div>
                <div class="tab-pane" id="dropdown2">Discover</div>
            </div>
        </div>
    </div>
</div>
@stop
