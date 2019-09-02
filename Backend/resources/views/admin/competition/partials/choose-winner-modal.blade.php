{!! Form::open(array('method'=>'post','class'=>'form-horizontal', 'id'=>'chooseWinnerForm', 'name'=> 'chooseWinnerForm')) !!}
    <div class="modal fade" id="chooseWinnerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header solid-border">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Choose Winner</h4>
                </div>
                <div class="modal-body top-padding">
                    <div class="table-responsive">
                        <table id="view-participates" class="display table">
                            <thead>
                                <tr>
                                    <th>Artist name</th>
                                    <th>Track name</th>
                                    <th>Uploaded date</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer footer-solid-border">
                    {!! Form::button('Cancel', ['class' => 'btn btn-default','data-dismiss'=>'modal']) !!}
                    {!! Form::button('Save', ['class' => 'btn btn-info save-winner','id'=>'save-winner']) !!}
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}

