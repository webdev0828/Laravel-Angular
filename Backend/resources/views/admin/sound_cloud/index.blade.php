@extends('admin.layout.admin')
@section('breadcrumb')
<div class="page-breadcrumb">
    <ol class="breadcrumb container">
        <li><a href="{!! url('admin/dashboard') !!}">Home</a></li>
        <li class="active">Settings</li>
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
    <div class="admin">
        <section id="socials">
            <div class="row">
              <div class="col-xs-12">
                <ul>
                  <li>
                    <div class="vertical-center">
                      <i class="fa fa-soundcloud"></i>
                      <input type="checkbox"  @if($isGating) checked="checked" @endif name="soundcloud-gating">
                    </div><!-- /.vertical-center -->
                  </li>
                </ul>
              </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
        </section><!-- /#socials -->
    </div>
@stop

