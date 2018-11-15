<?php
    require_once(__DIR__."/Congregation.class.php");
    require_once(__DIR__."/LegacyHostBlackout.class.php");
    $LegacyHostBlackout  = new LegacyHostBlackout();
    $Congregation = new Congregation();

    $rotNum = $_GET['rotation_number'];

    $legacyData = $LegacyHostBlackout->getLegacyDataForOneRotation($rotNum);

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