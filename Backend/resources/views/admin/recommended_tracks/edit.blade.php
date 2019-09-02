{!! Form::model($track, array('id' => 'recommendedtrackForm', 'method' => 'PATCH','route' => array('admin.recommended_tracks.update', $track['id']), 'files'=>true)) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="recommendedtrackModalLabel">Edit Recommended Track</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="name-input" class="form-control" placeholder="Track Name" value="@if(!empty($track['name']) ){{ $track['name'] }}@endif" required>
                </div>
                <div class="form-group">
                    {!! Form::label('file', 'File Upload') !!}
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('file',['class'=>'upload form-control', 'id'=>'file', 'accept'=>'video/*, audio/*']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('artwork_file', 'Artwork File') !!}
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('artwork_file',['class'=>'upload form-control', 'id'=>'artwork_file', 'accept'=>'images/*']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('background_image', 'Background Image') !!}
                    <div class="fileUpload btn btn-primary">
                        <span>Browse</span>
                        {!! Form::file('background_image',['class'=>'upload form-control', 'id'=>'background_image', 'accept'=>'images/*']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}