<div class="modal fade" id="addartist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Artist</h4>
            </div>
            {!! Form::open(array('id' => 'addArtistForm', 'route' => array('admin.artists.store')) )!!}
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="name" id="name-input" class="form-control" placeholder="Name" value="" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email-input" class="form-control" placeholder="Email" value="" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
