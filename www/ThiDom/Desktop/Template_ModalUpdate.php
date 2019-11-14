<?php include_once dirname(__FILE__) .'/../Core/Security.php';  ?>


<div id="modal-update-thidom" class="modal fade" role="dialog">
    
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Update</h4>
			</div>

            <div id="result-update">
                <iframe id="iframe-update">
                </iframe>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="user-close">
					<i class="fas fa-times"></i> Close
				</button>
			</div>
		</div>
	</div>
</div>