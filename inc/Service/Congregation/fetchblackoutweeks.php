<?php
	require_once(__DIR__."/DateRange.class.php");
	$DateRange = new DateRange();

	$result = $DateRange->showBlackoutWeeks();

	//Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($result);

