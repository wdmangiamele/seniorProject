<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    //Convert all of the rotation schedules into an associative array
    $result = $CongregationSchedule->getFullScheduleInArrayForm();

    //Return full schedule as JSON encoded data to the app.js file
    echo json_encode($result);