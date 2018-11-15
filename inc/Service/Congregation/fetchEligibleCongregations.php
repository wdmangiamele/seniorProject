<?php
    require_once(__DIR__."/CongregationBlackout.class.php");
    require_once(__DIR__."/CongregationSchedule.class.php");
    require_once(__DIR__."/Congregation.class.php");
    $CongregationBlackout = new CongregationBlackout();
    $CongregationSchedule = new CongregationSchedule();
    $Congregation = new Congregation();

    $rotationNum = $_POST['rotation_number'];

    $rotations = $CongregationSchedule->getDistinctRotationNums();
    $congIDs = $Congregation->getCongregationID();

    $congEligibleList = array();
//    for($i = 0; $i < sizeof($rotations); $i++) {
    $startDates = $CongregationSchedule->getStartDatesByRotation($rotationNum);
    for($h = 0; $h < sizeof($startDates); $h++) {
        $blackouts = $CongregationBlackout->getCongBlackoutsByStartDate($startDates[$h]["startDate"]);
        $congEligibleList[$startDates[$h]["startDate"]] = array();

        //If there are no blackouts for a specific date, add all the congregations as eligible for that date
        if(is_null($blackouts)) {
            for($u = 0; $u < sizeof($congIDs); $u++) {
                $tempCong = array(
                    "title" => $Congregation->getCongregationName($congIDs[$u]["congID"]),
                    "eligible" => "Yes",
                    "start" => $startDates[$h]["startDate"]
                );
                array_push($congEligibleList[$startDates[$h]["startDate"]], $tempCong);
            }
        }else {
            //Create array out of the ineligible congregations
            $ineligibleCongs = array();
            for($j = 0; $j < sizeof($blackouts); $j++) {
                array_push($ineligibleCongs, $blackouts[$j]['congID']);
            }

            //Add eligible congregations to eligibleCongs list
            for ($j = 0; $j < sizeof($congIDs); $j++) {
                if (!in_array($congIDs[$j]['congID'], $ineligibleCongs)) {
                    $tempCong = array(
                        "title" => $Congregation->getCongregationName($congIDs[$j]['congID']),
                        "eligible" => "Yes",
                        "start" => $startDates[$h]["startDate"]
                    );
                    array_push($congEligibleList[$startDates[$h]["startDate"]], $tempCong);
                }
            }

            //Add ineligible congregations to eligibleCongs list
            for($j = 0; $j < sizeof($ineligibleCongs); $j++) {
                $tempCong = array(
                    "title" => $Congregation->getCongregationName($ineligibleCongs[$j]),
                    "eligible" => "No",
                    "start" => $startDates[$h]["startDate"]
                );
                array_push($congEligibleList[$startDates[$h]["startDate"]], $tempCong);
            }
        }
    }
//    }
    echo json_encode($congEligibleList);
