<div class="modal fade" id="subGenresModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header solid-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Sub Genres</h4>
            </div>
           {!! Form::open(array('id' => 'subGenresForm', 'class'=>'form-horizontal', 'url'=>'admin/genres/'.$id.'/sub-genres') )!!}
           
            <div class="modal-body">
                <input type="hidden" name="subGenreId" id="subGenreId">
               <div class="form-group">
                    {!! Form::label('generName','Name',['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        <input type="text" name="name" id="name-input" class="form-control" placeholder="Name" required>
                        <div class="name-error text-danger"></div>
                    </div>

                </div>
                <div class="form-group">
                    {!! Form::label('desc','Description',['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        <textarea id="desc-input" name="description" class="form-control" rows="4" placeholder="Description"></textarea>
                        <div class="description-error text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer footer-solid-border">
                {!! Form::button('Cancel', ['class' => 'btn btn-default','data-dismiss'=>'modal']) !!}
                {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'save-sub-genre']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
