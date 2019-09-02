{!! Form::open(array('method'=>'post','class'=>'form-horizontal','id'=>'chooseWinnerForm','name'=>'chooseWinnerForm')) !!}
    <div class="modal fade" id="topListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header solid-border">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Spotlist</h4>
                </div>
                <div class="modal-body top-padding">
                   <div class="table-responsive">
                        <table id="spotlight-table" class="display table">
                            <thead>
                                <tr>
                                    <th>Track</th>
                                    <th>Artist</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}

