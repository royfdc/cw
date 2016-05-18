
<!-- script for delete item -->
<script>
    function delete_item(id) {
        $('#delete_modal').modal('show');
        $('#ids').val(id);
    }
</script>
<!-- script for delete item ends here -->

<!-- modal for delete confirmation -->
<div class="modal fade" id="delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <?php echo form_open(base_url().'admin/'.$action_delete_link);?>
            <div class="modal-body">
                Are you sure want to delete this <?php echo $item_name;?> ?
                <input type="hidden" id="ids" name="id"/>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- modal for delete confirmation ends here -->