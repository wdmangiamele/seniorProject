<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    //Get any flagged congregations after the algorithm was run
    $result = $CongregationSchedule->getFlaggedCongregations();

    //Return flagged congregations as JSON encoded data to the app.js file
    echo json_encode($result);