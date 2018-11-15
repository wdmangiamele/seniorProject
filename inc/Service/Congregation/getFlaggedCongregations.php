<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $result = $CongregationSchedule->getFlaggedCongregations();

    //Return flagged congregations as JSON encoded data to the app.js file
    echo json_encode($result);