<?php
    session_start();
    require_once("./inc/top_layout.php");
?>

    <div id="blackouts-per-rotation">
        <h4 id="blackouts-entered-title">Blackouts Entered Per Congregation</h4>
        <div class="table-responsive">
        </div>
    </div>
    <div class="modal fade" id="conf-sch-submit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="conf-sch-cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" id="conf-sch-yes" class="btn btn-primary">Yes schedule</button>
                </div>
            </div>
        </div>
    </div>
    <div class="loader"></div>
<?php
    require_once("./inc/bottom_layout.php");
?>