<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $updatedData = $_POST['updatedData'];

    //Update changes made to the congregation schedule
    //Changes come from adminCongSchedule.php page
    $status = array();
    for($i = 0; $i < sizeof($updatedData); $i++) {
        $updateResult = $CongregationSchedule->updateSchedule($updatedData[$i]["startDate"],$updatedData[$i]["congName"],$updatedData[$i]["rotation"]);
        array_push($status,$updateResult);
    }

    echo json_encode($status);