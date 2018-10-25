<?php
    require_once(__DIR__."/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $rotNum = $_POST['rotation_number'];

    $scheduledResult = $CongregationSchedule->scheduleCongregations($rotNum);

    $scheduledResultArr = array(
        "result" => $scheduledResult
    );

    echo json_encode($scheduledResultArr);