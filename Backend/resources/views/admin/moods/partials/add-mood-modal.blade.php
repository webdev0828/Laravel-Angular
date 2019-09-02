<div class="modal fade" id="moodModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header solid-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Vibe</h4>
            </div>
           {!! Form::open(array('id' => 'moodForm', 'class'=>'form-horizontal', 'route' => array('admin.vibes.store')) )!!}
           
            <div class="modal-body">

                <input type="hidden" name="moodId" id="moodId">
                <div class="form-group">
                    {!! Form::label('moodName','Name',['class'=>'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        <input type="text" name="name" id="name-input" class="form-control" placeholder="Vibe" required>
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
                {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'save-mood']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
