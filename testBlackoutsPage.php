<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Controller/CongregationBlackout.class.php");
    require_once(__DIR__."/inc/Controller/CongregationSchedule.class.php");
    $CongregationBlackout = new CongregationBlackout();
    $CongregationSchedule = new CongregationSchedule();

	if(isset($_SESSION['eventMsg'])) {
		$eventMsg = $_SESSION['eventMsg'];
		unset($_SESSION['eventMsg']);
	}
?>

<?php

	// Refer to the PHP quickstart on how to setup the environment:
	// https://developers.google.com/calendar/quickstart/php
	// Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
	// credentials.
	if(isset($_POST['blackout-submit'])) {
        /*$insertResult = $CongregationBlackout->insertBlackout($_POST['blackoutWeek']);*/
        /*if($insertResult) {*/
            $scheduleResult = $CongregationSchedule->scheduleCongregations();
//            var_dump($scheduleResult);
            if($scheduleResult) {
                $_SESSION['eventMsg'] = "<h3>Schedule Created</h3>";
                header("Location: testBlackoutsPage.php");
            }else {
                $_SESSION['eventMsg'] = "<h3>Error!</h3>";
                header("Location: testBlackoutsPage.php");
            }
        /*}*/
    }
    if(isset($eventMsg)) {
        echo $eventMsg;
	}

?>
	<!--<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=raihncongregation%40gmail.com&amp;color=%231B887A&amp;ctz=America%2FNew_York" style="border-width:0" width="1000" height="600" frameborder="0" scrolling="no"></iframe>
	<br>-->
	<form action="testBlackoutsPage.php" method="post">
		<div class="form-check">
		</div>
		<button id="blackout-submit" name="blackout-submit"  type="submit" class="btn btn-primary">Run Algorithm</button>
	</form>

<?php require_once("./inc/bottom_layout.php"); ?>
