<?php
    //session_start();
    require_once("./inc/top_layout.php");
    require_once("Schedule.class.php");

?>


    <ul class="legend">
        <li><h3>Legend</h3></li>
        <li><span class="primary"></span>Primary Driver</li>
        <li><span class="backup"></span>Backup Driver</li>
        <li><span class="nodriver"></span>No Driver Available</li>
    </ul>


    <div id='busCalendar'>

    </div>


<?php require_once("./inc/bottom_layout.php"); ?>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
