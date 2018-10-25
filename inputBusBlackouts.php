<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once("Schedule.class.php");
?>

<div id='inputBusCalendar'>

</div>

<button onclick="getBlackouts()">Save Blackouts</button>





<?php
require_once("./inc/bottom_layout.php");
?>
