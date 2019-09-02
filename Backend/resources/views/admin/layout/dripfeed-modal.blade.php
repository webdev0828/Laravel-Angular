<div class="modal fade" id="DripfeedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header solid-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Drip Feed</h4>
            </div>
           {!! Form::open(array('id' => 'DripfeedForm', 'name'=>'DripfeedForm', 'class'=>'form-horizontal DripfeedForm', 'url' => 'admin/save-dripfeed') )!!}
            <input type="hidden" name="name" id="dripfeed-name">
            <div class="modal-body">
            Note : Please add Drip Feed time between 10 to 60 minutes.
            <div class="clearfix">&nbsp;</div>
               <div class="form-group">
                    {!! Form::label('dripfeed_timer','Drip Feed Time :',['class'=>'control-label col-sm-3']) !!}
                    <div class="col-sm-9">
                        {{Form::select('dripfeed_timer',['10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','60'=>'60',],null,['id'=>'dripfeed_timer','class'=>'form-control'])}}
                       
                       {{--  <input type="number" name="dripfeed_timer" min="10" max="60" id="dripfeed_timer" class="form-control" placeholder="Drip Feed Timer" required> --}}
                        <div class="name-error text-danger"></div>
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
