<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
    $CongregationSchedule = new CongregationSchedule();
    $RotationScheduleStatus = new RotationScheduleStatus();

    $rotNum = $_POST['rotation_number'];

    $scheduledResult = $CongregationSchedule->scheduleCongregations($rotNum);

    if($scheduledResult) {
        $scheduledResult = $RotationScheduleStatus->updateRotationStatus($rotNum, "Scheduled");
    }

    $scheduledResultArr = array(
        "result" => $scheduledResult
    );

    echo json_encode($scheduledResultArr);