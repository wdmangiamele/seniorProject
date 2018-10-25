<?php
    //Fetches the distinct rotatations from congregation
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $rotationNums = $CongregationSchedule->getDistinctRotationNums();

    echo json_encode($rotationNums);