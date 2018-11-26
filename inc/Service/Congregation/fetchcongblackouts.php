<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationBlackout.class.php");
    require_once(__DIR__."/../../Business/Congregation/Congregation.class.php");
    require_once(__DIR__."/../../Business/Congregation/DateRange.class.php");
    require_once(__DIR__."/../../Business/Functions.class.php");
    $CongregationBlackout = new CongregationBlackout();
    $Congregation = new Congregation();
    $DateRange = new DateRange();
    $Functions = new Functions();

    //Get all the rotation numbers that have been inserted to congregation blackouts and convert them into a non associative array (regular indexed array)
    $rotsInCongBlackouts = $CongregationBlackout->getDistinctRotationNums();
    $rotsInCongBlackoutsNormalArr = $Functions->turnIntoNormalArray($rotsInCongBlackouts,"rotation_number");

    //Get all congregation IDs
    $allCongIDs = $Congregation->getCongregationID();
    $allCongIDsNormalArr = $Functions->turnIntoNormalArray($allCongIDs,"congID");

    //Get all rotations numbers that are in the date range table
    $rotationsInDateRange = $DateRange->getDistinctRotationNums();
    $rotationsInDateRangeNormalArr = $Functions->turnIntoNormalArray($rotationsInDateRange,"rotation_number");

    $congsEnteredBlackouts = array();
    for($j = 0; $j < sizeof($rotsInCongBlackouts); $j++) {
        //Start creating an associative array with the rotation number as the key
        $congsEnteredBlackouts[$rotsInCongBlackouts[$j]["rotation_number"]] = array();

        //Get all distinct congregation IDs that have inserted blackouts
        $congBlackouts = $CongregationBlackout->getCongBlackoutsDistinctCongIDByRotation($rotsInCongBlackouts[$j]["rotation_number"],"congID");
        $blackoutCongIDArr = $Functions->turnIntoNormalArray($congBlackouts, "congID");

        for($h = 0; $h < sizeof($allCongIDsNormalArr); $h++) {
            //See if congregation has inserted blackout for a rotation
            if(in_array($allCongIDsNormalArr[$h], $blackoutCongIDArr)) {
                $tempArr = array(
                    "congName" => $Congregation->getCongregationName($allCongIDsNormalArr[$h]),
                    "enteredBlackouts" => "Yes"
                );
                array_push($congsEnteredBlackouts[$rotsInCongBlackouts[$j]["rotation_number"]], $tempArr);
            }else {
                $tempArr = array(
                    "congName" => $Congregation->getCongregationName($allCongIDsNormalArr[$h]),
                    "enteredBlackouts" => "No"
                );
                array_push($congsEnteredBlackouts[$rotsInCongBlackouts[$j]["rotation_number"]], $tempArr);
            }
        }
    }

    for($j = 0; $j < sizeof($rotationsInDateRangeNormalArr); $j++) {
        //If a rotation doesn't have any congregation blackouts, mark all congregations for that rotation as not having entered blackouts
        if(!in_array($rotationsInDateRangeNormalArr[$j],$rotsInCongBlackoutsNormalArr)) {
            $congsEnteredBlackouts[$rotationsInDateRangeNormalArr[$j]] = array();
            for($h = 0; $h < sizeof($allCongIDsNormalArr); $h++) {
                $tempArr = array(
                    "congName" => $Congregation->getCongregationName($allCongIDsNormalArr[$h]),
                    "enteredBlackouts" => "No"
                );
                array_push($congsEnteredBlackouts[$rotationsInDateRangeNormalArr[$j]], $tempArr);
            }
        }
    }

    echo json_encode($congsEnteredBlackouts);