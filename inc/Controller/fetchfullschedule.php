<?php
    require_once(__DIR__."/Congregation.class.php");
    require_once(__DIR__."/CongregationSchedule.class.php");
    require_once(__DIR__."/Functions.class.php");
    $Congregation = new Congregation();
    $CongregationSchedule = new CongregationSchedule();
    $Functions = new Functions();

    $rotationNum = $_POST['rotation_number'];

    $fullSchedule = $CongregationSchedule->getSchedulePerRotation($rotationNum, "startDate");

    $fullSchedulePerRotation = array();
    for($i = 0; $i < sizeof($fullSchedule); $i++) {
        $tempArr = array(
                "congName" => $Congregation->getCongregationName($fullSchedule[$i]["congID"]),
                "startDate" => $fullSchedule[$i]["startDate"],
                "weekNumber" => $fullSchedule[$i]["weekNumber"],
                "rotationNumber" => $fullSchedule[$i]["rotationNumber"],
                "holiday" => $fullSchedule[$i]["holiday"],
                "isFlagged" => $fullSchedule[$i]["IsFlagged"],
                "reasonForFlag" => $fullSchedule[$i]["ReasonForFlag"]
        );
        array_push($fullSchedulePerRotation, $tempArr);
    }

    //Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($fullSchedulePerRotation);