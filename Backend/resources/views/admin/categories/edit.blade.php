{!! Form::model($category, array('id' => 'categoryForm', 'method' => 'PATCH','route' => array('admin.categories.update', $category['id']))) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="categoryModalLabel">Edit Category</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name-input" class="form-control" placeholder="Category Name" value="@if(!empty($category['name']) ){{ $category['name'] }}@endif" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Description">@if(!empty($category['description']) ){{ $category['description'] }}@endif</textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}