{!! Form::model($video, array('id' => 'videoTemplatesForm', 'method' => 'PATCH','route' => array('admin.video_template.update', $video['id']))) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="templatesModalLabel">Edit Template</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Template Name</label>
                    <input type="text" name="template_name" id="template-name" class="form-control" placeholder="Template Name" value="@if(!empty($video['template_name']) ){{ $video['template_name'] }}@endif" required>
                </div>
                <div class="form-group">
                    <label>Template URL</label>
                    <input type="text" name="template_url" id="template-url" class="form-control" placeholder="Template URL" value="@if(!empty($video['template_url']) ){{ $video['template_url'] }}@endif" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" id="add-row" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
{!! Form::close() !!}