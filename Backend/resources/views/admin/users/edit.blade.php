{!! Form::model($user, array('id' => 'stmUserForm', 'method' => 'PATCH','route' => array('admin.users.update', $user['id']))) !!}
<input type="hidden" id="user_id"  value="@if(!empty($user['id']) ){{ $user['id'] }}@endif">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit User</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name-input" class="form-control" placeholder="Name" value="@if(!empty($user['name']) ){{ $user['name'] }}@endif">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email-input" class="form-control" placeholder="Email" value="@if(!empty($user['email']) ){{ $user['email'] }}@endif" readonly>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <?php   $active = '';
                                    $inactive = '';
                                ?>
                            @if(!empty($user['status']) && $user['status'] == '1')
                                <?php $active = 'selected="selected"';?>
                            @endif

                            @if($user['status'] == '0')
                                <?php $inactive = 'selected="selected"';?>
                            @endif
                            <option value="1" {{$active}}>Active</option>
                            <option value="0" {{$inactive}}>Inactive</option>
                        </select>
                    </div>
                     @if($user['provider']!='')
                         <div class="form-group">
                            <label>Provider:</label>
                            <?php echo $user['provider'];?>
                        </div>
                    @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}