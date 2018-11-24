<?php
    session_start();
    if(!isset($_SESSION['userID']) || !isset($_SESSION['role'])) {
        $_SESSION['appErrMsg'] = 'Login Error';
        header('Location: error.php');
    }

    require_once("./inc/top_layout.php");
?>
    <div id="finalized-schedule">
        <div id="final-sch-tools">
            <span id="dummy-span"></span>
            <?php if($_SESSION['role'] == "Congregation Admin"): ?>
            <img src='img/email-icon.svg' id='email-icon' data-toggle='modal' data-target='#send-final-sch-modal'/>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal fade" id="send-final-sch-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="send-final-sch-cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" id="send-final-sch-save" class="btn btn-primary">Send Schedule</button>
                </div>
            </div>
        </div>
    </div>
    <div class="loader"></div>
<?php
    require_once("./inc/bottom_layout.php");
?>
