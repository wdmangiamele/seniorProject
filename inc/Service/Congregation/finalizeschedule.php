<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $rotNum = 56;

    $finalizedResult = $CongregationSchedule->finalizeSchedule($rotNum);

    $finalizedResultArr = array(
        "FinalizeResult" => $finalizedResult
    );

    echo json_encode($finalizedResultArr);