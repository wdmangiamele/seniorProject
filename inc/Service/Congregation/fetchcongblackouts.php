<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationBlackout.class.php");
    require_once(__DIR__."/../../Business/Congregation/Congregation.class.php");
    require_once(__DIR__."/../../Business/Congregation/DateRange.class.php");
    require_once(__DIR__."/../../Business/Functions.class.php");
    $CongregationBlackout = new CongregationBlackout();
    $Congregation = new Congregation();
    $DateRange = new DateRange();
    $Functions = new Functions();

    $rotsInCongBlackouts = $CongregationBlackout->getDistinctRotationNums();
    $rotsInCongBlackoutsNormalArr = $Functions->turnIntoNormalArray($rotsInCongBlackouts,"rotation_number");

    $allCongIDs = $Congregation->getCongregationID();
    $allCongIDsNormalArr = $Functions->turnIntoNormalArray($allCongIDs,"congID");

    $rotationsInDateRange = $DateRange->getDistinctRotationNums();
    $rotationsInDateRangeNormalArr = $Functions->turnIntoNormalArray($rotationsInDateRange,"rotation_number");

    $congsEnteredBlackouts = array();
    for($j = 0; $j < sizeof($rotsInCongBlackouts); $j++) {
        $congsEnteredBlackouts[$rotsInCongBlackouts[$j]["rotation_number"]] = array();

        $congBlackouts = $CongregationBlackout->getCongBlackoutsDistinctCongIDByRotation($rotsInCongBlackouts[$j]["rotation_number"],"congID");
        $blackoutCongIDArr = $Functions->turnIntoNormalArray($congBlackouts, "congID");

        for($h = 0; $h < sizeof($allCongIDsNormalArr); $h++) {
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