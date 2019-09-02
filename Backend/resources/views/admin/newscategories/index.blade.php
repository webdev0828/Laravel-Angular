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
<div class="panel panel-white">
    <div class="panel-body">
        <button type="button" class="btn btn-success m-b-sm" id="addnewscategory">Add News Category</button>
        <!-- Modal -->
        <div class="modal fade" id="newscategoryModal" tabindex="-1" role="dialog" aria-labelledby="newscategoryModalLabel" aria-hidden="true">
            
        </div>
        <div class="table-responsive">
            <table id="newscategory_table" class="display table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="no-sort">Description</th>
                        <th class="no-sort">Action(s)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop