<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 6:55 PM
 */

class CongregationBlackout {
    function __construct() {
        require_once(__DIR__."/../../Data/db.class.php");
        require_once(__DIR__."/DateRange.class.php");
        require_once(__DIR__."/../Functions.class.php");
        $this->DateRange = new DateRange();
        $this->DB = new Database();
        $this->Functions = new Functions();
    }//end CongregationBlackout constructor

    /* function that counts the number entries for each entry of a specified column in MySQL
     * @param $array - the array of data from MySQL
     * @param $initialVal - an initial, starting value to use for comparison in the function
     * @param $key - the name of the key that will be used in the created associative array
     * @param $value - the name of the value that will be used in the created associative array
     * @return $countedArray - an array with each of the MySQL data entries counted in an associative array
     * */
    function countValues($array, $initialVal, $key, $value) {
        $countedArray = array();
        $comparableCongID = $initialVal;
        $congBlackoutCount = 0;
        for($i = 0; $i < sizeof($array); $i++) {
            if ($comparableCongID == $array[$i][$key]) {
                $congBlackoutCount++;
                if($i == (sizeof($array)-1)) {
                    $singleCount = [$key => $comparableCongID,
                        $value => $congBlackoutCount];
                    array_push($countedArray, $singleCount);
                }
            } else {
                $singleCount = [$key => $comparableCongID,
                    $value => $congBlackoutCount];
                array_push($countedArray, $singleCount);
                $comparableCongID = $array[$i][$key];
                $congBlackoutCount = 1;
                if($i == (sizeof($array)-1)) {
                    $singleCount = [$key => $comparableCongID,
                        $value => $congBlackoutCount];
                    array_push($countedArray, $singleCount);
                }
            }
        }

        return $countedArray;
    }//end countValues

    /* function that checks to see if a blackout week has more than 5 congregations blacking it out
     * @return $datesMoreThanFive - array holding start dates for blackout weeks with more than 5 congregations blacking it out
     * */
    function dateBlackoutCount() {
        $result = $this->getCongBlackouts("startDate");
        $countedBlackedOutDates = $this->countValues($result,$result[0]["startDate"],"startDate", "count");
        $sortedDates = $this->Functions->sortArray($countedBlackedOutDates,"startDate","count");
        return $sortedDates;
    }//end dateBlackoutCount

    /* function that checks to see if a blackout week has more than 5 congregations blacking it out
     * does it for one rotation
     * @return $sortedDates - array holding start dates for blackout weeks with more than 5 congregations blacking it out
     * */
    function dateBlackoutCountForOneRotation($rotNum) {
        $result = $this->getCongBlackoutsByRotationWithBlackouts($rotNum,"startDate");
        $countedBlackedOutDates = $this->countValues($result,$result[0]["startDate"],"startDate", "count");
        $sortedDates = $this->Functions->sortArray($countedBlackedOutDates,"startDate","count");
        return $sortedDates;
    }//end dateBlackoutCountForOneRotation

    /* function that gets blackout weeks for one congregation
     * @param $id - the id of congregation
     * @return $result - the blackout week data fetched from MySQL
     * @return null - return nothing if no data was fetched
     * */
    function getBlackoutsForOneCongregation($congID) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE congID = :congID";
        $params = array(':congID' => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getBlackoutsForOneCongregation

    /* function that fetches all data from congregation_blackouts
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackouts($orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_blackout ORDER BY ".$orderByVar;
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackouts

    /* function that fetches all data from congregation_blackouts where the congregations have blackouts
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackoutsWithBlackouts($orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE NOT weekNumber = :weekNum ORDER BY ".$orderByVar;
        $params = array(":weekNum" => 0);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackoutsWithBlackouts

    /* function that fetches data from congregation_blackouts for a single rotation
     * @param $rotNum - the desired rotation number to get blackouts for
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackoutsByRotation($rotNum, $orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE rotation_number = :rotNum ORDER BY ".$orderByVar;
        $params = array(":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackoutsByRotation

    /* function that fetches all distinct congIDs from congregation_blackouts
     * @param $rotNum - the desired rotation number to get blackouts for
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackoutsDistinctCongIDByRotation($rotNum, $orderByVar) {
        $sqlQuery = "SELECT DISTINCT congID FROM congregation_blackout WHERE rotation_number = :rotNum ORDER BY ".$orderByVar;
        $params = array(":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackoutsDistinctCongIDByRotation

    /* function that fetches blackout dates that aren't a "no blackouts" date (1970-01-01)
     * @param $rotNum - the desired rotation number to get blackouts for
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackoutsByRotationWithBlackouts($rotNum, $orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE NOT (weekNumber = :weekNum) AND rotation_number = :rotNum ORDER BY ".$orderByVar;
        $params = array(":weekNum" => 0, ":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackoutsByRotation

    /* function that fetches all data from congregation_blackouts by start date
     * @param $startDate - the desired start date to get blackouts for
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackoutsByStartDate($startDate) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE startDate = :startDate";
        $params = array(":startDate" => $startDate);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackoutsByStartDate

    /* function that fetches blackouts by congID and rotation number
     * @param $congID - the congregation ID
     * @param $rotNum - the rotation number
     * @return $result - congregation blackouts for a congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getCongBlackoutsByCongIDAndRotNum($congID, $rotNum) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE congID = :congID AND rotation_number = :rotNum";
        $params = array(":congID" => $congID, ":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongBlackoutsByCongIDAndRotNum

    /* function that gets the blackouts entered, counts the number of dates each congregation blacked out, then sorts them
     * function helps determine which congregation has the most blackout/unavailability week
     * @return $sortedBlackouts - sorted number of blackout dates entered for each congregation
     * */
    function getCongBlackoutCount() {
        $result = $this->getCongBlackouts("congID");
        $countedData = $this->countValues($result,$result[0]["congID"],"congID","count");
        $sortedBlackouts = $this->Functions->sortArray($countedData,"congID","count");
        return $sortedBlackouts;
    }//end getCongBlackoutCount

    /* function that gets the congregations with blackouts entered,
     * counts the number of dates each congregation blacked out, then sorts them
     * function helps determine which congregation has the most blackout/unavailability week
     * @return $sortedBlackouts - sorted number of blackout dates entered for each congregation
     * */
    function getCongBlackoutCountWithBlackouts($rotNum) {
        $result = $this->getCongBlackoutsByRotationWithBlackouts($rotNum,"congID");
        $countedData = $this->countValues($result,$result[0]["congID"],"congID","count");
        $sortedBlackouts = $this->Functions->sortArray($countedData,"congID","count");
        return $sortedBlackouts;
    }//end getCongBlackoutCountWithBlackouts

    /* function that gets the congregations with blackouts entered,
     * counts the number of dates each congregation blacked out, then sorts them
     * function helps determine which congregation has the most blackout/unavailability week
     * @return $sortedBlackouts - sorted number of blackout dates entered for each congregation
     * */
    function getCongBlackoutCountByRotationWithBlackouts() {
        $result = $this->getCongBlackoutsWithBlackouts("congID");
        $countedData = $this->countValues($result,$result[0]["congID"],"congID","count");
        $sortedBlackouts = $this->Functions->sortArray($countedData,"congID","count");
        return $sortedBlackouts;
    }//end getCongBlackoutCountByRotationWithBlackouts

    /* function that fetches all the congregations with no blackouts
     * @param $orderByVar - variable used to help order the incoming select query
     * @return $result - all the congregations with no blackouts
     * @return null - return no data if no data successfully fetched
     * */
    function getCongsWithNoBlackouts($orderByVar) {
        $sqlQuery = "SELECT * FROM congregation_blackout WHERE weekNumber = :weekNum ORDER BY ".$orderByVar;
        $params = array(":weekNum" => 0);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCongsWithNoBlackouts

    /* function to get the distinct rotation numbers from congregation blackouts table
     * @return $result - if data was successfully fetched return the data
     * @return null - return no data if no data successfully fetched
     * */
    function getDistinctRotationNums() {
        $sqlQuery = "SELECT DISTINCT rotation_number FROM congregation_blackout";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getDistinctRotationNums

    /* function to insert blackouts for congregations
     * @param $blackoutWeek - array containing all the blackout choices the congregation selected
     * @param $email - the email of the logged in user
     * @return boolean - return true or false if the blackout was inserted
     * */
    function insertBlackout($blackoutWeek, $email) {
        //Get the congregation ID of the current user logged in
        $sqlQuery = "SELECT congID FROM congregation_coordinator WHERE coordinatorEmail = :email";
        $params = array(":email" => $email);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");

        if($result){
            //Check to see if blackouts already exists for a congregation
            //If so delete the existing blackouts

            //This if handles if the congregation chose "No Blackouts"
            //Incoming data for no blackouts will look like: 1970-01-01-53
            if(strlen($blackoutWeek[0]) > 10) {
                $rotNum = substr($blackoutWeek[0], 11);
                $sqlQueryCongBlackouts = "SELECT * FROM congregation_blackout WHERE congID = :congID AND rotation_number = :rotNum";
                $params = array(":congID" => $result[0]['congID'], ":rotNum" => $rotNum);
                $resultBlackouts = $this->DB->executeQuery($sqlQueryCongBlackouts, $params, "select");
                if($resultBlackouts) {
                    $sqlQueryDelete = "DELETE FROM congregation_blackout WHERE congID = :congID AND rotation_number = :rotNum";
                    $params = array(":congID" => $result[0]['congID'], ":rotNum" => $rotNum);
                    $resultDelete = $this->DB->executeQuery($sqlQueryDelete, $params, "delete");
                }
            }else {
                $rotNum = $this->DateRange->getRotationNumber($blackoutWeek[0]);
                $sqlQueryCongBlackouts = "SELECT * FROM congregation_blackout WHERE congID = :congID AND rotation_number = :rotNum";
                $params = array(":congID" => $result[0]['congID'], ":rotNum" => $rotNum);
                $resultBlackouts = $this->DB->executeQuery($sqlQueryCongBlackouts, $params, "select");
                if($resultBlackouts) {
                    $sqlQueryDelete = "DELETE FROM congregation_blackout WHERE congID = :congID AND rotation_number = :rotNum";
                    $params = array(":congID" => $result[0]['congID'], ":rotNum" => $rotNum);
                    $resultDelete = $this->DB->executeQuery($sqlQueryDelete, $params, "delete");
                }
            }

            for($i = 0; $i < sizeof($blackoutWeek); $i++) {
                //This if handles if the congregation chose "No Blackouts"
                //Incoming data for no blackouts will look like: 1970-01-01-53
                if(strlen($blackoutWeek[$i]) > 10) {
                    $rotationNum = substr($blackoutWeek[$i], 11);
                    $blackoutWeekStartDate = substr($blackoutWeek[$i], 0, 10);

                    //Insert the blackout date to MySQL
                    $sqlQuery3 = "INSERT INTO congregation_blackout VALUES (:congID, :weekNumber, :startDate, :rotNum)";
                    $params3 = array(":congID" => $result[0]["congID"], ":weekNumber" => 0,
                        ":startDate" => $blackoutWeekStartDate, ":rotNum" => $rotationNum);
                    $result4 = $this->DB->executeQuery($sqlQuery3, $params3, "insert");
                    if($result4 <= 0) {
                        return false;
                    }
                }else {
                    //Get the week number of the date that was submitted
                    $result2 = $this->DateRange->getWeekNumber($blackoutWeek[$i]);

                    $result3 = $this->DateRange->getRotationNumber($blackoutWeek[$i]);

                    if($result2 && $result3) {
                        //Insert the blackout date to MySQL
                        $sqlQuery3 = "INSERT INTO congregation_blackout VALUES (:congID, :weekNumber, :startDate, :rotNum)";
                        $params3 = array(":congID" => $result[0]["congID"], ":weekNumber" => $result2,
                            ":startDate" => $blackoutWeek[$i], ":rotNum" => $result3);
                        $result4 = $this->DB->executeQuery($sqlQuery3, $params3, "insert");
                        if($result4 <= 0) {
                            return false;
                        }
                    }else {
                        return false;
                    }
                }
            }
        }else {
            return false;
        }
        return true;
    }//end insertBlackout

    /* function to set the startDate column in MySQL
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $startDate - the new start date value
     * @return boolean - return true or false depending on if the value was successfully set
     * */
    function setStartDate($congID, $startDate) {
        $sqlQuery = "UPDATE congregation_blackout SET startDate = :startDate WHERE congID = :congID";
        $params = array(":weekNumber" => $startDate, ":congID" => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setStartDate

    /* function to set the weekNumber column in MySQL
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @param $weekNumber - the new week number value
     * @return boolean - return true or false depending on if the value was successfully set
     * */
    function setWeekNumber($congID, $weekNumber) {
        $sqlQuery = "UPDATE congregation_blackout SET weekNumber = :weekNumber WHERE congID = :congID";
        $params = array(":weekNumber" => $weekNumber, ":congID" => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setWeekNumber

}//end CongregationBlackout