<?php
    session_start();
    require_once("./inc/top_layout.php");
?>
    <div id="admin-schedule">

    </div>
    <div class="modal fade" id="conf-data-submit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="conf-data-cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" id="conf-data-save" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="conf-data-finalize" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="finalized-title" id="finalizeLabel"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="conf-data-cancel-finalize" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" id="conf-finalize" class="btn btn-primary">Finalize</button>
                </div>
            </div>
        </div>
    </div>
    <div class="loader"></div>
<?php require_once("./inc/bottom_layout.php"); ?>