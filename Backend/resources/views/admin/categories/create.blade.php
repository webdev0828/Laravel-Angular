{!! Form::open(array('id' => 'categoryForm', 'route' => array('admin.categories.store'))) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="categoryModalLabel">Add Category</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="name" id="name-input" class="form-control" placeholder="Category Name" value="" required>
                </div>
                <div class="form-group">
                    <textarea name="description" class="form-control" placeholder="Description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}