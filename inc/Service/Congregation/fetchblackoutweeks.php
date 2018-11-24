<?php
	require_once(__DIR__."/../../Business/Congregation/DateRange.class.php");
	require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
	$DateRange = new DateRange();
	$RotationScheduleStatus = new RotationScheduleStatus();

	$rotationsFinalOrSch = $RotationScheduleStatus->getScheduledFinalizedRotations();
	if(is_null($rotationsFinalOrSch)) {
	    $rotsArr = array();

        //Fetch all rotations where rotation is not already scheduled or finalized
        $result = $DateRange->showBlackoutWeeks($rotsArr);

        //Return MySQL data as JSON encoded data to the app.js file
        echo json_encode($result);
    }else {
        $rotsArr = array();
	    for($i = 0; $i < sizeof($rotationsFinalOrSch); $i++) {
	        array_push($rotsArr, $rotationsFinalOrSch[$i]['rotation_number']);
        }

        //Fetch all rotations where rotation is not already scheduled or finalized
        $result = $DateRange->showBlackoutWeeks($rotsArr);

        //Return MySQL data as JSON encoded data to the app.js file
        echo json_encode($result);
    }

