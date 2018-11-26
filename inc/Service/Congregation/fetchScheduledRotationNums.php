<?php
    //Fetches the distinct rotatations from congregation
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    //Get all rotation numbers in the congregation_schedule table
    $rotationNums = $CongregationSchedule->getDistinctRotationNums();

    echo json_encode($rotationNums);