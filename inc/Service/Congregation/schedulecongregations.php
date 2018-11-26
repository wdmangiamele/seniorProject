<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
    $CongregationSchedule = new CongregationSchedule();
    $RotationScheduleStatus = new RotationScheduleStatus();

    $rotNum = $_POST['rotation_number'];

    //Using the rotation number, schedule the rotation
    $scheduledResult = $CongregationSchedule->scheduleCongregations($rotNum);

    //Update the rotation schedule's status to "Scheduled"
    if($scheduledResult) {
        $scheduledResult = $RotationScheduleStatus->updateRotationStatus($rotNum, "Scheduled");
    }

    $scheduledResultArr = array(
        "result" => $scheduledResult
    );

    echo json_encode($scheduledResultArr);