<?php
    require_once(__DIR__."/../../Business/Congregation/Congregation.class.php");
    require_once(__DIR__."/../../Business/Congregation/LegacyHostBlackout.class.php");
    $LegacyHostBlackout  = new LegacyHostBlackout();
    $Congregation = new Congregation();

    $rotNum = $_GET['rotation_number'];

    //Based on the rotation number, get the schedule
    $legacyData = $LegacyHostBlackout->getLegacyDataForOneRotation($rotNum);

    //Use returned data to create JSON data
    $fullSchedulePerRotation = array();
    for($i = 0; $i < sizeof($legacyData); $i++) {
        $tempArr = array(
            "congName" => $Congregation->getCongregationName($legacyData[$i]["congID"]),
            "startDate" => $legacyData[$i]["startDate"],
            "rotationNumber" => $legacyData[$i]["rotation_number"],
            "holiday" => $legacyData[$i]["holiday"],
        );
        array_push($fullSchedulePerRotation, $tempArr);
    }

    //Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($fullSchedulePerRotation);