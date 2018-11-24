<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
    $CongregationSchedule = new CongregationSchedule();
    $RotationScheduleStatus = new RotationScheduleStatus();

    $rotNum = $_POST['rotation_number'];

    $finalizedResult = $CongregationSchedule->finalizeSchedule($rotNum);

    if($finalizedResult) {
        $finalizedResult = $RotationScheduleStatus->updateRotationStatus($rotNum, "Finalized");
    }

    $finalizedResultArr = array(
        "FinalizeResult" => $finalizedResult
    );

    echo json_encode($finalizedResultArr);