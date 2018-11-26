<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/18/2018
 * Time: 10:29 AM
 */

class CongregationSchedule {
    function __construct() {
        require_once(__DIR__."/Congregation.class.php");
        require_once(__DIR__."/CongregationBlackout.class.php");
        require_once(__DIR__."/CongregationCoordinator.class.php");
        require_once(__DIR__."/DateRange.class.php");
        require_once(__DIR__."/../../Data/db.class.php");
        require_once(__DIR__."/../Functions.class.php");
        require_once(__DIR__."/LegacyHostBlackout.class.php");

        $this->Congregation = new Congregation();
        $this->CongregationBlackout = new CongregationBlackout();
        $this->CongregationCoordinator = new CongregationCoordinator();
        $this->DateRange = new DateRange();
        $this->DB = new Database();
        $this->Functions = new Functions();
        $this->LegacyHost = new LegacyHostBlackout();
    }//end CongregationSchedule constructor

    /* function to turn all of the rotation schedules into an associative array
     * @return $fullCongSch - return all rotation schedules as an associative array if there are no flags
     * @return $dates - return rotation schedules as a new associative array; new array has flagging considered
     * */
    function createCompleteSchArray() {
        $flaggedCongregations = $this->getFlaggedCongregations();
        $fullCongSch = $this->getFullScheduleInArrayForm();

        if(sizeof($flaggedCongregations) == 0) {
            return $fullCongSch;
        }else {
            $dates = array();
            for($i = 0; $i < sizeof($fullCongSch); $i++) {
                $tempArr = array(
                                "title" => $fullCongSch['title'],
                                "start" => $fullCongSch['start'],
                                "end" => $fullCongSch['end'],
                                "holiday" => $fullCongSch['holiday'],
                                "flagged" => "No");
                array_push($dates, $tempArr);
            }

            for($i = 0; $i < sizeof($fullCongSch); $i++) {
                $tempArr = array(
                    "title" => $flaggedCongregations['title'],
                    "start" => $flaggedCongregations['start'],
                    "end" => $flaggedCongregations['end'],
                    "flagged" => "Yes");
                array_push($dates, $tempArr);
            }
            return $dates;
        }
    }//end createCompleteSchArray

    /* function to delete a data from the congregation_schedule in the SQL table
     * @return boolean - return true or false if the data was successfully deleted
     * */
    function deleteCongregationSchedule() {
        $sqlQuery = "DELETE FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "delete");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end deleteCongregationSchedule

    /* function to finalize a rotation's schedule
     * moves the rotations schedule from congregation schedule to legacy host blackout table
     * @param $rotNum - the desired rotation schedule to finalize
     * @return boolean - boolean variable indicating the schedule has been finalized
     * */
    function finalizeSchedule($rotNum) {
        $sqlQuery = "SELECT * FROM congregation_schedule WHERE rotationNumber = :rotNum";
        $params = array(":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            for($i = 0; $i < sizeof($result); $i++) {
                $insertResult = $this->LegacyHost->insertLegacyData($result[$i]["congID"], $result[$i]["startDate"],
                                null, $result[$i]["holiday"], $result[$i]["rotationNumber"]);
                if(!$insertResult) {
                    return false;
                }
            }
            $sqlQuery = "DELETE FROM congregation_schedule WHERE rotationNumber = :rotNum";
            $params = array(":rotNum" => $rotNum);
            $result = $this->DB->executeQuery($sqlQuery, $params, "delete");
            if($result <= 0) {
                return false;
            }
        }else {
            return false;
        }
        return true;
    }//end finalizeSchedule

    /* function to get scheduled congregations IDs of a rotation
     * @return $result - all the scheduled congregation IDs for a rotation
     * @return null - return nothing if no data was returned
     * */
    function getCongIDsByRotation($rotationNum) {
        $sqlQuery = "SELECT congID FROM congregation_schedule WHERE rotationNumber = :rotNum";
        $params = array(":rotNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongIDsByRotation

    /* function to get the distinct rotation numbers from congregation blackouts table
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getDistinctRotationNums() {
        $sqlQuery = "SELECT DISTINCT rotationNumber FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getDistinctRotationNums

    /* function to get the congregations that weren't scheduled
     * @return $flaggedCongregations - all the congregations not scheduled
     * */
    function getFlaggedCongregations() {
        $finalHostCongScheduleArr = $this->getFullScheduleInArrayForm();

        //All the congregations from MySQL
        $allCongList = $this->Congregation->getCongregations();

        //Each rotation number in the final congregation schedule
        $rotationNums = $this->getRotationNumsInFinalSchedule();

        //All the start dates for each rotation number in the final congregation schedule
        $allStartDates = array();
        for($i = 0; $i < sizeof($rotationNums); $i++) {
            $startDates = $this->DateRange->getStartDateBasedRotWithoutZero($rotationNums[$i]['rotationNumber']);
            for($h = 0; $h < sizeof($startDates); $h++) {
                array_push($allStartDates, $startDates[$h]['startDate']);
            }
        }

        $flaggedCongNames = array();
        $flaggedCongDates = array();
        $finalFlaggedCongList = array();

        //Get the names and start dates for each of the scheduled congregations
        $finalHostCongSchNames = array();
        $finalHostCongSchStartDates = array();

        //Add the congregation start dates and name into their own separate arrays
        //The names and start dates come from the $finalHostCongScheduleArr array
        //This is done to help see which start dates and congregation names are missing
        for($e = 0; $e < sizeof($finalHostCongScheduleArr); $e++) {
            array_push($finalHostCongSchNames, $finalHostCongScheduleArr[$e]['title']);
            array_push($finalHostCongSchStartDates, $finalHostCongScheduleArr[$e]['start']);
        }

        //Check to see which congregation names are missing from the $finalHostCongSchNames array
        //Add the missing congregation names to an array
        for($e = 0; $e < sizeof($allCongList); $e++) {
            if(!in_array($allCongList[$e]['congName'], $finalHostCongSchNames)) {
                array_push($flaggedCongNames, $allCongList[$e]['congName']);
            }
        }

        //Check to see which congregation start dates are missing from the $flaggedCongDates array
        //Add the missing congregation start dates to an array
        for($e = 0; $e < sizeof($allStartDates); $e++) {
            if(!in_array($allStartDates[$e], $finalHostCongSchStartDates)) {
                array_push($flaggedCongDates, $allStartDates[$e]);
            }
        }

        $flaggedCongNameCount = 0;
        //Combine the missing start dates and congregation names into one final array
        for($e = 0; $e < sizeof($flaggedCongDates); $e++) {
            $scheduledEndDate = date("Y-m-d", strtotime("+7 days", strtotime($flaggedCongDates[$e])));
            $tempFlagArray = array(
                "title" => $flaggedCongNames[$flaggedCongNameCount],
                "start" => $flaggedCongDates[$e],
                "end" => $scheduledEndDate,
                "color" => "#CC2936"
            );
            array_push($finalFlaggedCongList, $tempFlagArray);
            $flaggedCongNameCount++;
            if($flaggedCongNameCount == 4) {
                $flaggedCongNameCount = 0;
            }
        }

        return $finalFlaggedCongList;
    }//end getFlaggedCongregations

    /* function to get all of the scheduled congregations
     * @return $result - the whole congregation schedule
     * @return null - return nothing if no data was returned
     * */
    function getFullSchedule() {
        $sqlQuery = "SELECT * FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getFullSchedule

    /* function to turn all of the rotation schedules into an associative array
     * @return $finalHostCongScheduleArr - all of the rotation schedules as an associative array
     * */
    function getFullScheduleInArrayForm() {
        $fullSchedule = $this->getFullSchedule();

        $finalHostCongScheduleArr = array();

        for($i = 0; $i < sizeof($fullSchedule); $i++) {
            $scheduledEndDate = date("Y-m-d",strtotime("+7 days",strtotime($fullSchedule[$i]['startDate'])));

            //Create array holding the information of the congregation about to be scheduled
            if($fullSchedule[$i]['holiday'] == 1) {
                $congregationScheduledArr = array(
                    "title" => $this->Congregation->getCongregationName($fullSchedule[$i]['congID']),
                    "start" => $fullSchedule[$i]['startDate'],
                    "end" => $scheduledEndDate,
                    "holiday" => "Yes",
                    "rotationNumber" => $fullSchedule[$i]["rotationNumber"]
                );
                array_push($finalHostCongScheduleArr, $congregationScheduledArr);
            }else {
                $congregationScheduledArr = array(
                    "title" => $this->Congregation->getCongregationName($fullSchedule[$i]['congID']),
                    "start" => $fullSchedule[$i]['startDate'],
                    "end" => $scheduledEndDate,
                    "holiday" => "No",
                    "rotationNumber" => $fullSchedule[$i]["rotationNumber"]
                );
                array_push($finalHostCongScheduleArr, $congregationScheduledArr);
            }
        }
        return $finalHostCongScheduleArr;
    }//end getFullScheduleInArrayForm

    /* function to get each distinct rotation number
     * @return $result - each distinct rotation number
     * @return null - return nothing if no data was returned
     * */
    function getRotationNumsInFinalSchedule() {
        $sqlQuery = "SELECT DISTINCT rotationNumber FROM congregation_schedule";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getRotationNumsInFinalSchedule

    /* function to get the rotation number of a congregation in the congregation schedule
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $startDate - the start date for a scheduled congregation
     * @param $weekNumber - the week number for a scheduled congregation
     * @return $result[0]["rotationNumber"] - the rotation number for a scheduled congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getRotationNumber($congID, $startDate, $weekNumber) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE congID = :congID AND startDate = :startDate AND
                    weekNumber = :weekNumber";
        $params = array(":congID" => $congID, ":startDate" => $startDate, ":weekNumber" => $weekNumber);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["rotationNumber"];
        }else {
            return null;
        }
    }//end getRotationNumber

    /* function to get the scheduled congregation names per rotation
     * @param $rotationNum - the desired rotation number
     * @return $schCongNames - return array of congregation names
     * @return null - return nothing if no data was found
     * */
    function getScheduledCongsPerRotation($rotationNum) {
        $sqlQuery = "SELECT congID FROM congregation_schedule WHERE rotationNumber = :rotNum";
        $params = array(":rotNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            $schCongNames = array();
            for($i = 0; $i < sizeof($result); $i++) {
                array_push($schCongNames, $this->Congregation->getCongregationName($result[$i]['congID']));
            }
            return $schCongNames;
        }else {
            return null;
        }
    }//end getScheduledCongsPerRotation

    /* function to get a schedule for each rotation
     * @param $rotationNum - the desired rotation number of schedule
     * @param $orderByVar - variable to order the returned results by
     * @return $result - the schedule for a rotation number
     * @return null - return no data if no data successfully fetched
     * */
    function getSchedulePerRotation($rotationNum, $orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_schedule WHERE rotationNumber = :rotationNumber ORDER BY ".$orderByVar;
        $params = array(":rotationNumber" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getSchedulePerRotation

    /* function to get the start date of a congregation in the congregation schedule
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $weekNumber - the week number of a rotation for a scheduled congregation
     * @param $rotationNum - the rotation number for a scheduled congregation
     * @return $result[0]["startDate"] - the start date for a scheduled congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getStartDate($congID, $weekNumber, $rotationNum) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE congID = :congID AND weekNumber = :weekNumber AND
                    rotationNumber = :rotationNumber";
        $params = array(":congID" => $congID, ":weekNumber" => $weekNumber, ":rotationNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["startDate"];
        }else {
            return null;
        }
    }//end getStartDate

    /* function to get the start dates by rotation numbers
     * @param $rotationNum - the rotation number
     * @return $result - the start dates for the rotation
     * @return null - return no data if no data successfully fetched
     * */
    function getStartDatesByRotation($rotationNum) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE rotationNumber = :rotNum";
        $params = array(":rotNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getStartDatesByRotation

    /* function to get the start dates in the congregation schedule
     * @return $result - the start dates from congregation_schedule
     * @return null - return no data if no data successfully fetched
     * */
    function getStartDates($orderByVar) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule ORDER BY ".$orderByVar;
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getStartDates

    /* function to get the week number of a congregation in the congregation schedule
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $startDate - the start date for a scheduled congregation
     * @param $rotationNum - the rotation number for a scheduled congregation
     * @return $result[0]["weekNumber"] - the week number for a scheduled congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getWeekNumber($congID, $startDate, $rotationNum) {
        $sqlQuery = "SELECT startDate FROM congregation_schedule WHERE congID = :congID AND startDate = :startDate AND
                    rotationNumber = :rotationNumber";
        $params = array(":congID" => $congID, ":startDate" => $startDate, ":rotationNum" => $rotationNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["weekNumber"];
        }else {
            return null;
        }
    }//end getWeekNumber

    /* function that will insert a newly scheduled congregation to congregation_schedule in MySQL
     * @param $congID - the ID of the congregation to be inserted
     * @param $startDate - the start date of the week the congregation is scheduled for
     * @param $weekNumber - the rotation week number that congregation is scheduled for
     * @param $rotationNumber - the rotation number of the week the congregation is scheduled for
     * @param $holiday - a 1 or 0 telling if week is a holiday week
     * @param $isFlagged - indicating if week should be flagged
     * @param $reasonForFlag - the reasoning behind flagging a week
     * @return boolean - return true or false depending on if the data was inserted
     * */
    function insertNewScheduledCong($congID, $startDate, $weekNumber, $rotationNumber, $holiday, $isFlagged, $reasonForFlag) {
        if(is_null($isFlagged)) {
            $sqlQuery = "INSERT INTO congregation_schedule VALUES (:congID, :startDate, :weekNumber, :rotNum, :holiday, :isFlag, :reasonForFlag)";
            $params = array(":congID" => $congID, ":startDate" => $startDate,
                ":weekNumber" => $weekNumber, ":rotNum" => $rotationNumber, ":holiday" => $holiday, ":isFlag" => null, "reasonForFlag" => null);
            $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }else {
            $sqlQuery = "INSERT INTO congregation_schedule VALUES (:congID, :startDate, :weekNumber, :rotNum, :holiday, :isFlag, :reasonForFlag)";
            $params = array(":congID" => $congID, ":startDate" => $startDate,
                ":weekNumber" => $weekNumber, ":rotNum" => $rotationNumber, ":holiday" => $holiday, ":isFlag" => $isFlagged, "reasonForFlag" => $reasonForFlag);
            $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        }
    }//end insertNewScheduledCong

    /* function to actually schedule congregations to the database
     * @param $rotNum - the rotation to be scheduled
     * @return bool - return true or false if the congregations were successfully inserted
     * */
    function scheduleCongregations($rotNum){
        /*$deleteQuery = $this->deleteCongregationSchedule();*/
        //$numOfRotations = $this->CongregationBlackout->getDistinctRotationNums();

        //Boolean variable used to check if the schedule was created
        $scheduleCreated = true;
//        for ($u = 0; $u < sizeof($numOfRotations); $u++) {
            //Congregation blackout count data sorted
            //$congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCountWithBlackouts();
            $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCountWithBlackouts($rotNum);

//            $startDateList = $this->DateRange->getStartDateBasedRotWithoutZero($numOfRotations[$u]["rotation_number"]);
            $startDateList = $this->DateRange->getStartDateBasedRotWithoutZero($rotNum);

            //Date blackout count data sorted
            $dateBlackoutCount = $this->CongregationBlackout->dateBlackoutCountForOneRotation($rotNum);

            //Grab start dates from dateBlackoutCount and store in separate array
            //Used for figuring if start dates weren't blacked out
            $justBlackoutStartDates = array();
            for ($i = 0; $i < sizeof($dateBlackoutCount); $i++) {
                array_push($justBlackoutStartDates, $dateBlackoutCount[$i]['startDate']);
            }

            //Figure out which start dates weren't blacked out
            //If a start date wasn't blacked out, add it to the dateBlackoutCount array with a count of 0
            for ($e = 0; $e < sizeof($startDateList); $e++) {
                if (!in_array($startDateList[$e]['startDate'], $justBlackoutStartDates)) {
                    $tempArray = array(
                        'startDate' => $startDateList[$e]['startDate'],
                        'count' => 0
                    );
                    array_push($dateBlackoutCount, $tempArray);
                }
            }

            for ($i = 0; $i < sizeof($dateBlackoutCount); $i++) {
                for ($h = 0; $h < sizeof($congregationBlackoutCount); $h++) {
                    //Get an array of a single congregation's list of blackout weeks
                    //The array is used to help figure out if the date we're trying to schedule for is already blacked
                    // out by the congregation
                    $singleCongBlackouts = $this->CongregationBlackout->getBlackoutsForOneCongregation($congregationBlackoutCount[$h]["congID"]);
                    $noConflictingDate = true;
                    for ($j = 0; $j < sizeof($singleCongBlackouts); $j++) {
                        //If the congregation we're looking at doesn't have a
                        // blackout date that is the date we're trying to schedule for
                        if ($dateBlackoutCount[$i]["startDate"] == $singleCongBlackouts[$j]["startDate"]) {
                            //If the date we're trying to schedule for IS one of the blackout dates for the
                            // congregation we're trying to schedule, break the loop and move onto the next congregation
                            $noConflictingDate = false;
                            break;
                        }
                    }

                    //If the congregation we're trying to schedule has no matching blackout date with the date we're
                    // looking at, move onto the next step
                    if ($noConflictingDate == true) {
                        //Test to see if the congregation was scheduled within a 10 week span of the date we're trying to schedule
                        $scheduledAtLeast10Apart = $this->scheduledAtLeast10WeeksApart($congregationBlackoutCount[$h]["congID"], $dateBlackoutCount[$i]["startDate"]);

                        //If the date we're trying to schedule for is at least 10 weeks apart
                        // from the last time the congregation was scheduled, move on to the next step
                        //Else, break the for loop and move on to the next congregation
                        if ($scheduledAtLeast10Apart == true) {
                            $year = substr($dateBlackoutCount[$i]["startDate"], 0, 4);
                            $scheduledEndDate = date("Y-m-d", strtotime("+6 days", strtotime($dateBlackoutCount[$i]["startDate"])));
                            $newYear = substr($scheduledEndDate, 0, 4);
                            //If the year for the end date goes to the next year, use that year for the contains holiday function
                            if(($newYear - $year) == 1) {
                                $dateIsAHoliday = $this->DateRange->containsHoliday($newYear, $dateBlackoutCount[$i]["startDate"], $scheduledEndDate);
                            }else {
                                $dateIsAHoliday = $this->DateRange->containsHoliday($year, $dateBlackoutCount[$i]["startDate"], $scheduledEndDate);
                            }
                            //If the date is not a holiday, schedule the congregation for that holiday
                            //If it is a holiday, check if the congregation was scheduled the holiday the year before
                            if ($dateIsAHoliday == false) {
                                $congID = $congregationBlackoutCount[$h]["congID"];
                                $scheduledStartDate = $dateBlackoutCount[$i]["startDate"];
                                $scheduledWeekNum = $this->DateRange->getWeekNumber($dateBlackoutCount[$i]["startDate"]);
                                $scheduledRotationNum = $this->DateRange->getRotationNumber($dateBlackoutCount[$i]["startDate"]);

                                $insertedIntoCongSch = $this->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum, 0, null, null);
                                $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($scheduledStartDate, "", $congID);
                                if ($insertedIntoCongSch && $updatedLastDatesServed) {
                                    unset($congregationBlackoutCount[$h]);
                                    $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                    if ($h == 12) {
                                        $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCount();
                                    }

                                    break;
                                } else {
                                    $scheduleCreated = false;
                                    return $scheduleCreated;
                                }
                            } else {
                                //See if the holiday was scheduled a year ago
                                $scheduledHolidayYearAgo = $this->scheduledHolidayAYearAgo($year, $dateBlackoutCount[$i]["startDate"], $congregationBlackoutCount[$h]["congID"]);

                                //If the congregation was scheduled for the holiday a year ago, move on to the next congregation
                                //Else, schedule the congregation for that holiday
                                if ($scheduledHolidayYearAgo) {
                                    break;
                                } else {
                                    $congID = $congregationBlackoutCount[$h]["congID"];
                                    $scheduledStartDate = $dateBlackoutCount[$i]["startDate"];
                                    $scheduledWeekNum = $this->DateRange->getWeekNumber($dateBlackoutCount[$i]["startDate"]);
                                    $scheduledRotationNum = $this->DateRange->getRotationNumber($dateBlackoutCount[$i]["startDate"]);

                                    $insertedIntoCongSch = $this->insertNewScheduledCong($congID, $scheduledStartDate, $scheduledWeekNum, $scheduledRotationNum, 1, null, null);
                                    $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($scheduledStartDate, $scheduledStartDate, $congID);
                                    if ($insertedIntoCongSch && $updatedLastDatesServed) {
                                        unset($congregationBlackoutCount[$h]);
                                        $congregationBlackoutCount = array_values($congregationBlackoutCount);

                                        if ($h == 12) {
                                            $congregationBlackoutCount = $this->CongregationBlackout->getCongBlackoutCount();
                                        }

                                        break;
                                    } else {
                                        $scheduleCreated = false;
                                        return $scheduleCreated;
                                    }
                                }
                            }
                        }
                    }
                }
            }
//        }


        $scheduleCreated = $this->scheduleCongsWithNoBlackouts($rotNum);

        return $scheduleCreated;
    }//end scheduleCongregations

    /* function to schedule all the congregations that don't have blackouts (listed as completely available)
     * @param $rotNum - the rotation that is being scheduled
     * @param $numOfRotations - the number of distinct rotations in congregation blackouts
     * @return $scheduled - boolean variable indicating if the all the congregations got scheduled
     * */
    function scheduleCongsWithNoBlackouts($rotNum) {
        //Get all the congregation IDs
        $allCongregationIDs = $this->Congregation->getCongregationID();

        //Find all the start dates that weren't scheduled
        $scheduled = true;

            //Get the start dates for each rotation, use for comparisons sake
//            $startDates = $this->DateRange->getStartDateBasedRotWithoutZero($numOfRotations[$i]['rotation_number']);
            $startDates = $this->DateRange->getStartDateBasedRotWithoutZero($rotNum);

            //Get the start dates for the congregation_schedule table
//            $congSchStartDates = $this->getStartDatesByRotation($numOfRotations[$i]['rotation_number']);
            $congSchStartDates = $this->getStartDatesByRotation($rotNum);

            //Take all the start dates from congregation_schedule and move them into a normal indexed array
            $allCongSchStartDates = $this->Functions->turnIntoNormalArray($congSchStartDates, 'startDate');

            //Create array that holds all the start dates not scheduled
            $missingStartDates = $this->Functions->createMissingValuesArray($startDates, $allCongSchStartDates, 'startDate');

            //Get the congregation IDs from the congregation_schedule table
//            $scheduledCongIDs = $this->getCongIDsByRotation($numOfRotations[$i]['rotation_number']);
            $scheduledCongIDs = $this->getCongIDsByRotation($rotNum);

            $congSchCongIDs = $this->Functions->turnIntoNormalArray($scheduledCongIDs, 'congID');

            $missingCongIDs = $this->Functions->createMissingValuesArray($allCongregationIDs, $congSchCongIDs, 'congID');

            //Schedule all the congregations that don't have blackouts
            for($e = 0; $e < sizeof($missingCongIDs); $e++) {
                //See if the congregation passes the 10 week rule
                $atLeast10WeeksApart = $this->scheduledAtLeast10WeeksApart($missingCongIDs[$e], $missingStartDates[$e]);
                if($atLeast10WeeksApart) {
                    $year = substr($missingStartDates[$e], 0, 4);
                    $scheduledEndDate = date("Y-m-d", strtotime("+6 days", strtotime($missingStartDates[$e])));
                    $dateIsAHoliday = $this->DateRange->containsHoliday($year, $missingStartDates[$e], $scheduledEndDate);
                    if($dateIsAHoliday) {
                        $hadHolidayAYearAgo = $this->scheduledHolidayAYearAgo($year, $missingStartDates[$e], $missingCongIDs[$e]);
                        if ($hadHolidayAYearAgo == false) {
                            $scheduledWeekNum = $this->DateRange->getWeekNumber($missingStartDates[$e]);
                            $scheduledRotationNum = $this->DateRange->getRotationNumber($missingStartDates[$e]);

                            $insertedIntoCongSch = $this->insertNewScheduledCong($missingCongIDs[$e], $missingStartDates[$e], $scheduledWeekNum, $scheduledRotationNum, 1, null, null);
                            $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($missingStartDates[$e], $missingStartDates[$e], $missingCongIDs[$e]);
                            if (!($insertedIntoCongSch && $updatedLastDatesServed)) {
                                $scheduled = false;
                                return $scheduled;
                            }
                        }
                    }else {
                        $scheduledWeekNum = $this->DateRange->getWeekNumber($missingStartDates[$e]);
                        $scheduledRotationNum = $this->DateRange->getRotationNumber($missingStartDates[$e]);
                        $insertedIntoCongSch = $this->insertNewScheduledCong($missingCongIDs[$e], $missingStartDates[$e], $scheduledWeekNum, $scheduledRotationNum, 0, null, null);
                        $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($missingStartDates[$e], "", $missingCongIDs[$e]);
                        if (!($insertedIntoCongSch && $updatedLastDatesServed)) {
                            $scheduled = false;
                            return $scheduled;
                        }
                    }
                }
            }

        //Schedule congregations with flags if there are any
        return $this->scheduleFlaggedCongregations($rotNum);
    }//end scheduleCongsWithNoBlackouts

    /* function to schedule flagged congregations if any
     * @param $rotNum - the rotation that is being scheduled
     * @return boolean - boolean that says whether the congregation was successfully flagged
     * */
    function scheduleFlaggedCongregations($rotNum) {
        //Boolean variable
        $scheduled = true;

        //Get all the start dates for rotation
        $startDates = $this->DateRange->getStartDateBasedRotWithoutZero($rotNum);

        //Get the start dates for the congregation_schedule table
        $congSchStartDates = $this->getStartDatesByRotation($rotNum);

        //Take all the start dates from congregation_schedule and move them into a normal indexed array
        $allCongSchStartDates = $this->Functions->turnIntoNormalArray($congSchStartDates, 'startDate');

        //Create array that holds all the start dates not scheduled
        $missingStartDates = $this->Functions->createMissingValuesArray($startDates, $allCongSchStartDates, 'startDate');

        //Get all the congregation IDs
        $allCongregationIDs = $this->Congregation->getCongregationID();

        //Get the congregation IDs from the congregation_schedule table
        $scheduledCongIDs = $this->getCongIDsByRotation($rotNum);

        $congSchCongIDs = $this->Functions->turnIntoNormalArray($scheduledCongIDs, 'congID');

        $missingCongIDs = $this->Functions->createMissingValuesArray($allCongregationIDs, $congSchCongIDs, 'congID');
        //Schedule the flagged congregations
        for($e = 0; $e < sizeof($missingCongIDs); $e++) {
            //See if the reason the congregation didn't get scheduled was because of the 10 week rule
            $atLeast10WeeksApart = $this->scheduledAtLeast10WeeksApart($missingCongIDs[$e], $missingStartDates[$e]);
            if($atLeast10WeeksApart == false) {
                //Schedule the congregation with a flag
                //Give reason for flagging: "Breaks 10 week rule"

                //Check if holiday
                $year = substr($missingStartDates[$e], 0, 4);
                $scheduledEndDate = date("Y-m-d", strtotime("+6 days", strtotime($missingStartDates[$e])));
                $dateIsAHoliday = $this->DateRange->containsHoliday($year, $missingStartDates[$e], $scheduledEndDate);

                if($dateIsAHoliday) {
                    $scheduledWeekNum = $this->DateRange->getWeekNumber($missingStartDates[$e]);
                    $scheduledRotationNum = $this->DateRange->getRotationNumber($missingStartDates[$e]);

                    $insertedIntoCongSch = $this->insertNewScheduledCong($missingCongIDs[$e], $missingStartDates[$e], $scheduledWeekNum, $scheduledRotationNum, 1,
                        "Yes", "Breaks 10 Week Rule");
                    $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($missingStartDates[$e], $missingStartDates[$e], $missingCongIDs[$e]);
                    if (!($insertedIntoCongSch && $updatedLastDatesServed)) {
                        $scheduled = false;
                        return $scheduled;
                    }
                }else {
                    $scheduledWeekNum = $this->DateRange->getWeekNumber($missingStartDates[$e]);
                    $scheduledRotationNum = $this->DateRange->getRotationNumber($missingStartDates[$e]);
                    $insertedIntoCongSch = $this->insertNewScheduledCong($missingCongIDs[$e], $missingStartDates[$e], $scheduledWeekNum, $scheduledRotationNum, 0,
                        "Yes", "Breaks 10 Week Rule");
                    $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($missingStartDates[$e], "", $missingCongIDs[$e]);
                    if (!($insertedIntoCongSch && $updatedLastDatesServed)) {
                        $scheduled = false;
                        return $scheduled;
                    }
                }
            }else {

                $year = substr($missingStartDates[$e], 0, 4);
                //See if the reason the congregation didn't get scheduled was because of the holiday a year ago rule
                //Give reason for flagging: "Breaks holiday a year ago rule"
                $hadHolidayAYearAgo = $this->scheduledHolidayAYearAgo($year, $missingStartDates[$e], $missingCongIDs[$e]);
                if ($hadHolidayAYearAgo) {
                    $scheduledWeekNum = $this->DateRange->getWeekNumber($missingStartDates[$e]);
                    $scheduledRotationNum = $this->DateRange->getRotationNumber($missingStartDates[$e]);

                    $insertedIntoCongSch = $this->insertNewScheduledCong($missingCongIDs[$e], $missingStartDates[$e], $scheduledWeekNum, $scheduledRotationNum, 1,
                        "Yes", "Breaks Holiday a Year Ago Rule");
                    $updatedLastDatesServed = $this->Congregation->updateLastDatesServed($missingStartDates[$e], $missingStartDates[$e], $missingCongIDs[$e]);
                    if (!($insertedIntoCongSch && $updatedLastDatesServed)) {
                        $scheduled = false;
                        return $scheduled;
                    }
                }
            }
        }
        return $scheduled;
    }//end scheduleFlaggedCongregations

    /* function to check if a scheduled congregation is at least 10 weeks apart
     * @param $congID - the congregation ID
     * @param $startDate - the date which the congregation is going to be scheduled for
     * @return boolean - return true or false depending if the congregation is at least 10 weeks apart
     * */
    function scheduledAtLeast10WeeksApart($congID, $startDate) {
        //Test to see if the congregation was scheduled within a 10 week span of the date we're trying to schedule
        $scheduledAtLeast10Apart = true;
        $priorDate = $this->Congregation->getLastDateServed($congID);
        if (is_null($priorDate) == false) {
            $datetime1 = new DateTime($startDate);
            $datetime2 = new DateTime($priorDate);
            $interval = $datetime1->diff($datetime2);
            $daysDiff = $interval->format('%a days');
            $numOfDays = 0;
            for ($k = 0; $k < strlen($daysDiff); $k++) {
                if (!is_numeric(substr($daysDiff, $k, 1))) {
                    $numOfDays = intval(substr($daysDiff, 0, $k));
                    break;
                }
            }
            $numOfWeeks = $numOfDays / 7;
            if ($numOfWeeks < 10) {
                $scheduledAtLeast10Apart = false;
            }
        }

        return $scheduledAtLeast10Apart;
    }//end scheduledAtLeast10WeeksApart

    /* function to check if a scheduled congregation is scheduled on a holiday that they didn't do a year ago
     * @param $year - the year of the holiday that they're going to be scheduled for
     * @param $startDate - the date of the holiday week
     * @param $congID - the congregation ID
     * @return boolean - return true or false depending on if the congregation did the holiday a year ago
     * */
    function scheduledHolidayAYearAgo($year, $startDate, $congID) {
        //First, identify which holiday it is
        $holidayName = $this->DateRange->identifyHoliday($year, $startDate);
        $holidayArray = array("SundayBeforeEaster", "Easter", "Memorial", "Independence", "Labor", "Thanksgiving", "Christmas", "NewYears");
        $priorYear = date("Y", strtotime("-1 year", strtotime($year)));
        $holidayAYearAgo = date("Y-m-d");
        foreach ($holidayArray as $holiday) {
            if ($holiday == $holidayName) {
                if ($holidayName == "SundayBeforeEaster") {
                    $holidayAYearAgo = $this->DateRange->getSundayBeforeEaster($priorYear);
                } elseif ($holidayName == "Easter") {
                    $holidayAYearAgo = $this->DateRange->get_easter_datetime($priorYear);
                } elseif ($holidayName == "Memorial") {
                    $holidayAYearAgo = $this->DateRange->getMemorialDay($priorYear);
                } elseif ($holidayName == "Independence") {
                    $holidayAYearAgo = $this->DateRange->getIndependenceDay($priorYear);
                } elseif ($holidayName == "Labor") {
                    $holidayAYearAgo = $this->DateRange->getLaborDay($priorYear);
                } elseif ($holidayName == "Thanksgiving") {
                    $holidayAYearAgo = $this->DateRange->getThanksgiving($priorYear);
                } elseif ($holidayName == "Christmas") {
                    $holidayAYearAgo = $this->DateRange->getChristmas($priorYear);
                } elseif ($holidayName == "NewYears") {
                    $holidayAYearAgo = $this->DateRange->getNewYears($priorYear);
                } else {
                    break;
                }
            }
        }

        //Next, see if the congregation was scheduled for that holiday a year ago
        $lastHolidayServed = $this->Congregation->getLastHolidayServed($congID);

        //If the congregation was scheduled a year ago, return true
        //Else, return false
        if ($holidayAYearAgo == $lastHolidayServed) {
            return true;
        }else {
            return false;
        }
    }//end scheduledHolidayAYearAgo

    /* function to send emails to every congregation with the finalized schedule
     * @param $rotNum - the finalized rotation number
     * @return boolean - true or false if the emails were sent
     * */
    function sendFinalizedCongSchedule($rotNum) {
        $schedule = $this->LegacyHost->getLegacyDataForOneRotation($rotNum);
        $subject = "Final Schedule for Rotation: $rotNum (RAIHN Scheduler App)";
        $message = "<html>
                        <head>
                            <title>Final Schedule for Rotation: $rotNum (RAIHN Scheduler App)</title>
                            <style>
                                table {
                                    border-collapse: collapse;
                                }
                                
                                table, td, th {
                                    border: 1px solid black;
                                }
                                
                                th, td {
                                    padding: 15px;
                                    text-align: left;
                                }
                                
                                #email-schedule-headings {
                                    background-color: #0D6AA8;
                                    color: white;
                                }
                            </style>
                        </head>
                        <body>
                            <h4>Here is the final schedule for rotation: $rotNum</h4>
                            <table>
                                    <tr id='email-schedule-headings'>
                                        <th>Start Date</th>
                                        <th>Rotation Number: $rotNum</th>
                                        <th>Support Congregations</th>
                                    </tr>";
        for($i = 0; $i < sizeof($schedule); $i++) {
            if($schedule[$i]['holiday'] == 1) {
                $message .= "<tr>
                                <td><strong>".$schedule[$i]['startDate']." HOLIDAY!</strong></td>
                                <td><strong>".$this->Congregation->getCongregationName($schedule[$i]['congID'])."</strong></td>
                                <td></td>
                            </tr>";
            }else {
                $message .= "<tr>
                                <td>".$schedule[$i]['startDate']."</td>
                                <td>".$this->Congregation->getCongregationName($schedule[$i]['congID'])."</td>
                                <td></td>
                            </tr>";
            }
        }
        $message .= "        </table>
                        </body>
                    </html>";
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: brypickering@gmail.com' . "\r\n";

        $congregationEmails = $this->CongregationCoordinator->getCoordinatorEmailAll();
        for($i = 0; $i < sizeof($congregationEmails); $i++) {
            set_time_limit(30);
            $sentMail = mail($congregationEmails[$i]['coordinatorEmail'],$subject,$message,$headers);
            if(!$sentMail){
                return false;
            }
        }

        return true;
    }//end sendFinalizedSchedule

    /* function to update where a congregation is scheduled
     * @param $startDate - the start date you're scheduling the new congregation on
     * @param $congName - the new congregation you're scheduling
     * @param $rotation - the rotation number you're scheduling
     * @return boolean - return true or false if the schedule was updated
     * */
    function updateSchedule($startDate, $congName, $rotation) {
        $congID = $this->Congregation->getCongregationIDByName($congName);
        $sqlQuery = "UPDATE congregation_schedule SET congID = :congID, IsFlagged = :isFlag, ReasonForFlag = :reasonForFlag WHERE startDate = :startDate AND rotationNumber = :rotNum";
        $params = array(":congID" => $congID, ":isFlag" => null, ":reasonForFlag" => null, ":startDate" => $startDate, ":rotNum" => $rotation);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end updateSchedule

}//end CongregationSchedule
