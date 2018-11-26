<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
    $CongregationSchedule = new CongregationSchedule();
    $RotationScheduleStatus = new RotationScheduleStatus();

    $rotNum = $_POST['rotation_number'];

    //Finalize the rotation schedule
    //Move schedule from congregation_schedule to legacy_host_blackout
    $finalizedResult = $CongregationSchedule->finalizeSchedule($rotNum);

    //Update the rotation schedule status as "Finalized"
    if($finalizedResult) {
        $finalizedResult = $RotationScheduleStatus->updateRotationStatus($rotNum, "Finalized");
    }

    //Create JSON data based on if the schedule was successfully finalized
    $finalizedResultArr = array(
        "FinalizeResult" => $finalizedResult
    );

    echo json_encode($finalizedResultArr);