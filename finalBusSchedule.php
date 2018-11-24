<?php
    session_start();
    if(!isset($_SESSION['userID']) || !isset($_SESSION['role'])) {
        $_SESSION['appErrMsg'] = 'Login Error';
        header('Location: error.php');
    }

    require_once("./inc/top_layout.php");
?>


    <ul class="legend">
        <li><h3>Legend</h3></li>
        <li><span class="primary"></span>Primary Driver</li>
        <li><span class="backup"></span>Backup Driver</li>
        <li><span class="nodriver"></span>No Driver Available</li>
    </ul>

<?php

if ($_SESSION['role'] == 'Bus Driver Admin'){

?>

    <img src='img/email-icon.svg' id='email-bus-icon' data-toggle='modal' data-target='#send-final-bus-sch-modal'/>

    <div id='busCalendarAdmin'>

    </div>


    <div class="modal fade" id="send-final-bus-sch-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalBusLabel">

                    </h4>
                </div>
                <div class="modal-body">
                    <h4 id="monthLabel"> </h4>


                    <p>The schedule will be sent to all drivers</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="send-bus-final-sch-cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" id="send-bus-final-sch-save" class="btn btn-primary">Send Schedule</button>
                </div>
            </div>
        </div>
    </div>
    <div class="loader"></div>

<?php

}
else if ($_SESSION['role'] == 'Bus Driver'){

?>

<div id='busCalendarUser'>

</div>

<?php
}
 ?>





<?php require_once("./inc/bottom_layout.php"); ?>


<!-- <script>
var view = $('#busCalendarAdmin').fullCalendar('getView');
alert("The view's title is " + view.title);
</script> -->
