<?php 
    include_once dirname(__FILE__) .'/../Core/Security.php';
?>

<div id="modal-confirmation" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="modal-confirmation-cancel" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="modal-confirmation-save"  data-dismiss="modal" >Yes</button>
            </div>
        </div>
    </div>
</div>
