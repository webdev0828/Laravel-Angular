{!! Form::model('', array('id' => 'sendMailForm', 'method' => 'POST','url' => 'admin/sendmailto/artist')) !!}
<input type="hidden" id="user_id" name="user_id" value="@if(!empty($user->user_id) ){{ $user->user_id }}@endif">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <span>To : </span><span><b>{!! $user->name !!}</b></span>
                </div>
                <div class="form-group">
                    <input type="text" name="subject"  class="form-control" placeholder="Subject">
                </div>
                <div class="form-group">
                    <textarea name="message" class="form-control" placeholder="Write Message here..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Send</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}