<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $result = $CongregationSchedule->getFullScheduleInArrayForm();

    //Return full schedule as JSON encoded data to the app.js file
    echo json_encode($result);