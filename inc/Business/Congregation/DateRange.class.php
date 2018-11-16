<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 7:52 PM
 */

class DateRange {
    function __construct() {
        require_once(__DIR__."/../../Data/db.class.php");
        require_once(__DIR__."/../Functions.class.php");
        require_once(__DIR__."/RotationDate.class.php");
        $this->DB = new Database();
        $this->Functions = new Functions();
        $this->RotationDate = new RotationDate();
    }

    /* function to if a date range has a holiday in it for a given year
     * @param $year - year that has the date range that is being tested
     * @param $sundayOfRotation - the start of the date range being tested
     * @param $saturdayOfRotation - the end of the date range being tested
     * @return $isThereAHoliday - returns true or false if there is a holiday inside the tested date range
     */
    function containsHoliday($year, $sundayOfRotation, $saturdayOfRotation) {
        $easterDate = date("Y-m-d",$this->get_easter_datetime($year)->getTimestamp()); //date will be incremented in a loop
        $sundayBeforeEaster = date("Y-m-d", strtotime("-7 days", strtotime($easterDate)));
        $memorialDayDate = date("Y-m-d", strtotime("last monday of may ".$year));
        $independenceDayDate = date("Y-m-d",strtotime("4 july ".$year));
        $laborDayDate = date("Y-m-d",strtotime("first monday of september ".$year));
        $thanksgivingDate = date("Y-m-d",strtotime("fourth thursday of november ".$year));
        $christmasDate = date("Y-m-d",strtotime("25 december ".$year));
        $newYearsDate = date("Y-m-d",strtotime("1 january ".$year));

        $holidayArray = array($sundayBeforeEaster, $easterDate, $memorialDayDate, $independenceDayDate, $laborDayDate,
            $thanksgivingDate, $christmasDate, $newYearsDate);

        $isThereAHoliday = false;
        for($i = 0; $i < sizeof($holidayArray); $i++) {
            if($sundayOfRotation <= $holidayArray[$i] && $holidayArray[$i] <= $saturdayOfRotation) {
                $isThereAHoliday = true;
                break;
            }
        }
        return $isThereAHoliday;
    }//end containsHoliday

    /* function to get Christmas for any given year
     * @param $year - the desired year
     * @return $christmasDate - the date of Christmas for given year in yyyy-mm-dd format
     * */
    function getChristmas($year) {
        $christmasDate = date("Y-m-d",strtotime("25 december ".$year));
        return $christmasDate;
    }//end getChristmas

    /* function to fetch all the date ranges from the date range table
     * @return $result - all the date range data from MySQL
     * @return null - return nothing if no data was retrieved
     * */
    function getDateRanges($orderByVar) {
        $sqlQuery = "SELECT * FROM date_range ORDER BY ".$orderByVar;
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getDateRanges

    /* function to get the distinct rotation numbers
     * @return $result - each distinct rotation number from MySQL
     * $return null - return nothing if no data was retrieved
     * */
    function getDistinctRotationNums() {
        $sqlQuery = "SELECT DISTINCT rotation_number FROM date_range";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getDistinctRotationNums

    /* function that finds Easter for any given year
     * @param $year - the year that you want to find Easter in
     * @return $base - non timestamp value of Easter for a given year
     */
    function get_easter_datetime($year) {
        $base = new DateTime("$year-03-21");
        $days = easter_days($year);

        try {
            return $base->add(new DateInterval("P{$days}D"));
        } catch (Exception $e) {
            //Add exception code here
        }
    }//end get_easter_datetime

    /* function to get Independence day for any given year
     * @param $year - the desired year
     * @return $independenceDayDate - the date of Independence Day for given year in yyyy-mm-dd format
     * */
    function getIndependenceDay($year) {
        $independenceDayDate = date("Y-m-d",strtotime("4 july ".$year));
        return $independenceDayDate;
    }//end getIndependenceDay

    /* function to get Labor day for any given year
     * @param $year - the desired year
     * @return $laborDayDate - the date of Labor Day for given year in yyyy-mm-dd format
     * */
    function getLaborDay($year) {
        $laborDayDate = date("Y-m-d",strtotime("first monday of september ".$year));
        return $laborDayDate;
    }//end getLaborDay

    /* function to get the highest rotation number in MySQL
     * @return $result[0]['MAX(rotation_number)'] - the maximum rotation number
     */
    function getMaximumRotationNumber() {
        $sqlQuery = "SELECT MAX(rotation_number) FROM date_range";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result[0]['MAX(rotation_number)']) {
            return $result[0]['MAX(rotation_number)'];
        }else {
            return null;
        }
    }//end getMaximumRotationNumber

    /* function to get memorial day for any given year
     * @param $year - the desired year
     * @return $memorialDayDate - the date of Memorial Day for given year in yyyy-mm-dd format
     * */
    function getMemorialDay($year) {
        $memorialDayDate = date("Y-m-d", strtotime("last monday of may ".$year));
        return $memorialDayDate;
    }//end getMemorialDay

    /* function to get the lowest rotation number in MySQL
     * @return $result[0]['MIN(rotation_number)'] - the minimum rotation number
     */
    function getMinimumRotationNumber() {
        $sqlQuery = "SELECT MIN(rotation_number) FROM date_range";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result[0]['MIN(rotation_number)']) {
            return $result[0]['MIN(rotation_number)'];
        }else {
            return null;
        }
    }//end getMinimumRotationNumber

    /* function to get New Years date
     * @param $year - the desired year
     * @return $newYearsDate - the date of New Years for a given year in in yyyy-mm-dd format
     * */
    function getNewYears($year) {
        $newYearsDate = date("Y-m-d",strtotime("1 january ".$year));
        return $newYearsDate;
    }//end getNewYears

    /* function to get the rotation week number for a particular date
     * @param $startDate - the date you would like to find the week number for
     * @return $result[0]["weekNumber"] - the desired rotation week number of the date
     * @return null - return null if nothing is returned
     * */
    function getRotationNumber($startDate) {
        $sqlQuery = "SELECT rotation_number FROM date_range WHERE startDate = :startDate";
        $params = array(':startDate' => $startDate);
        $result = $this->DB->executeQuery($sqlQuery,$params, "select");
        if($result) {
            return $result[0]["rotation_number"];
        }else {
            return null;
        }
    }//end getRotationNumber

    /* function to get the start date values based on the rotation number
     * @param $rotNum - the rotation number of the start dates
     * @return $result - all the start dates returned from MySQL
     * @return null - return null if nothing is returned
     * */
    function getStartDateBasedRotation($rotNum) {
        $sqlQuery = "SELECT startDate FROM date_range WHERE rotation_number = :rotNum";
        $params = array(':rotNum' => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getStartDateBasedRotation

    /* function to get the start date values based on the rotation number without the week numbers of zero
     * @param $rotNum - the rotation number of the start dates
     * @return $result - all the start dates returned from MySQL
     * @return null - return null if nothing is returned
     * */
    function getStartDateBasedRotWithoutZero($rotNum) {
        $sqlQuery = "SELECT startDate FROM date_range WHERE NOT (weekNumber = :weekNumber) AND rotation_number = :rotNum";
        $params = array(':weekNumber' => 0, ':rotNum' => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getStartDateBasedRotWithoutZero

    /* function to get the date of the sunday before easter
     * @param $year - the desired year
     * @return $sundayBeforeEaster - the date of the sunday before Easter for given year in yyyy-mm-dd format
     * */
    function getSundayBeforeEaster($year) {
        $easterDate = date("Y-m-d",$this->get_easter_datetime($year)->getTimestamp()); //date will be incremented in a loop
        $sundayBeforeEaster = date("Y-m-d", strtotime("-7 days", strtotime($easterDate)));
        return $sundayBeforeEaster;
    }//end getSundayBeforeEaster

    /* function to get Thanksgiving for any given year
     * @param $year - the desired year
     * @return $thanksgivingDate - the date of Thanksgiving for given year in yyyy-mm-dd format
     * */
    function getThanksgiving($year) {
        $thanksgivingDate = date("Y-m-d",strtotime("fourth thursday of november ".$year));
        return $thanksgivingDate;
    }//end getThanksgiving

    /* function to get the total number of rotations that are in the database
     * @return sizeof($result) - returns the size of the data from the SELECT DISTINCT query
     * @return null - returns nothing if no data was retrieved
     * */
    function getTotalNumberOfRotations() {
        $sqlQuery = "SELECT DISTINCT rotation_number FROM date_range";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return sizeof($result);
        }else {
            return null;
        }
    }//end getTotalNumberOfRotations

    /* function to calculate the total number of weeks inputted into the database
     * @param $startYear - the start year for the number of weeks needed
     *						comes from figuring out the start year of the next rotation needed
     * @param $finalYear - the calculated end year of the number of weeks needed
     * @param $startDateNxtRotation - the start date of the next rotation needed
     */
    function getTotalNumOfWks($startYear, $finalYear, $startDateNxtRotation) {
        $startingWkNum = date('W', strtotime($startDateNxtRotation));
        if($this->has53Weeks($startYear) == true){
            $startingWkNum = 1;
        }else {
            $startingWkNum++;
        }
        $totalWeeks = 0;
        while($startYear <= $finalYear) {
            $yearHas53 = $this->has53Weeks($startYear);
            if($yearHas53 == true) {
                $totalWeeks+=53;
            }else {
                $totalWeeks+=52;
            }
            $startYear++;
        }
        $finalTotal = $totalWeeks - $startingWkNum;
        return $finalTotal;
    }//end getTotalNumOfWks

    /* function to get the rotation week number for a particular date
     * @param $startDate - the date you would like to find the week number for
     * @return $result[0]["weekNumber"] - the desired rotation week number of the date
     * @return null - return null if nothing is returned
     * */
    function getWeekNumber($startDate) {
        $sqlQuery = "SELECT weekNumber FROM date_range WHERE startDate = :startDate";
        $params = array(':startDate' => $startDate);
        $result = $this->DB->executeQuery($sqlQuery,$params, "select");
        if($result) {
            return $result[0]["weekNumber"];
        }else {
            return null;
        }
    }//end getWeekNumber

    /* function that tests if a given year has 53 weeks
     * @param $year - the desired year to be tested
     * @param boolean - true or false if the tested year has 53 weeks
     */
    function has53Weeks($year){
        $daysInFebruary = cal_days_in_month(CAL_GREGORIAN, 2, $year);
        $dayYearStartsOn = new DateTime();
        $dayYearStartsOn->setTimestamp(mktime(0,0,0,1,1,$year));
        $dayOfTheWeek = $dayYearStartsOn->format("w");
        if($dayOfTheWeek == 4 || ($dayOfTheWeek == 3 && $daysInFebruary == 29)) {
            return true;
        }else {
            return false;
        }
    }//end has53Weeks

    /* function to identify a holiday based on a date and year given
     * @param $year - the desired year
     * @param $result - the date that matches a holiday date
     * @return $holiday - the name of the holiday
     * */
    function identifyHoliday($year, $date) {
        $easterDate = date("Y-m-d",$this->get_easter_datetime($year)->getTimestamp()); //date will be incremented in a loop
        $sundayBeforeEaster = date("Y-m-d", strtotime("-7 days", strtotime($easterDate)));
        $memorialDayDate = date("Y-m-d", strtotime("last monday of may ".$year));
        $independenceDayDate = date("Y-m-d",strtotime("4 july ".$year));
        $laborDayDate = date("Y-m-d",strtotime("first monday of september ".$year));
        $thanksgivingDate = date("Y-m-d",strtotime("fourth thursday of november ".$year));
        $christmasDate = date("Y-m-d",strtotime("25 december ".$year));
        $newYearsDate = date("Y-m-d",strtotime("1 january ".$year));

        $holidayArray = array(
            "SundayBeforeEaster" => $sundayBeforeEaster,
            "Easter" => $easterDate,
            "Memorial" => $memorialDayDate,
            "Independence" => $independenceDayDate,
            "Labor" => $laborDayDate,
            "Thanksgiving" => $thanksgivingDate,
            "Christmas" => $christmasDate,
            "NewYears" => $newYearsDate
        );

        //If the date entered matches a date for a holiday, return the name of the holiday
        foreach ($holidayArray as $holiday => $value) {
            if($date == $holidayArray[$holiday]) {
                return $holiday;
            }
        }
    }

    function insertCalendarEvent($blackoutWeekNumArray) {
        $selectQuery = "SELECT congID FROM congregation_coordinator WHERE coordinatorEmail = :coorEmail";
        $params = array(':coorEmail' => $_SESSION['email']);
        $result = $this->DB->executeQuery($selectQuery, $params, "select");

        /*for($i = 0; $i < sizeof($blackoutWeekNumArray); $i++ ){*/
        $insertQuery = "INSERT INTO congregation_blackout (congID, weekNumber, startDate) VALUES (:congID, :weekNum, :startDate)";
        $params2 = array(':congID' => $result[0]['congID'], ':weekNum' => $blackoutWeekNumArray[0], ':startDate' => '2018-01-08');
        $result = $this->DB->executeQuery($insertQuery, $params2, "insert");
        if($result == 0) {
            return $params2;
        }
        /*}*/
        return true;
    }//end insertCalendarEvent

    /* function to insert date range data to the date_range table in MySQL
     * @param $strtDateNxtRotation - the start date of the next rotation needed
     * @param $numberOfYears - the number of years worth data that's wanted to be inserted
     * @param $startYear - the start year for the number of weeks needed
     *						comes from figuring out the start year of the next rotation needed
     * @param $nxtRotationNumber - th
     */
    function insertDateRange($strtDateNxtRotation, $numberOfYears, $startYear, $nxtRotationNumber) {
        $dateOfMonth = new DateTime();
        $dateOfMonth->setTimestamp(strtotime($strtDateNxtRotation));
        $sundayOfRotation = $dateOfMonth->format("Y-m-d");

        $totalRotations = $this->getTotalNumOfWks($startYear, ($startYear + $numberOfYears), $sundayOfRotation) / 13;
        $totalRotationsRounded = round($totalRotations, 0, PHP_ROUND_HALF_DOWN);

        $insertDataMsg = "";
        $x = 1;
        while($x <= $totalRotationsRounded) {
            //Insert date ranges per rotation (every 13 weeks)
            for($i = 1; $i <= 13; $i++){
                $saturdayOfRotation = date("Y-m-d", strtotime("+6 day",strtotime($sundayOfRotation)));
                if($sundayOfRotation <= ($startYear."-12-31") && ($startYear."-12-31") <= $saturdayOfRotation) {
                    $startYear++;
                }
                $isThereAHoliday = $this->containsHoliday($startYear, $sundayOfRotation, $saturdayOfRotation);
                if($isThereAHoliday) {
                    $insertQuery = "INSERT INTO date_range VALUES (:weekNum, :startDate, :endDate, :holiday, :rotNum)";
                    $params = array(':weekNum' => $i, ':startDate' => $sundayOfRotation, ':endDate' => $saturdayOfRotation,
                        ':holiday' => 1, ':rotNum' => $nxtRotationNumber);
                    $result = $this->DB->executeQuery($insertQuery, $params, "insert");
                    if($result < 0) {
                        $insertDataMsg = "Error";
                    }
                }else {
                    $insertQuery = "INSERT INTO date_range VALUES (:weekNum, :startDate, :endDate, :holiday, :rotNum)";
                    $params = array(':weekNum' => $i, ':startDate' => $sundayOfRotation, ':endDate' => $saturdayOfRotation,
                        ':holiday' => 0, ':rotNum' => $nxtRotationNumber);
                    $result = $this->DB->executeQuery($insertQuery, $params, "insert");
                    if($result < 0) {
                        $insertDataMsg = "Error";
                    }
                }
                $sundayOfRotation = date("Y-m-d", strtotime("+7 day",strtotime($sundayOfRotation)));
            }
            //Insert date ranges that serve as a 'no blackout' value
            $insertQuery = "INSERT INTO date_range VALUES (:weekNum, :startDate, :endDate, :holiday, :rotNum)";
            $params = array(':weekNum' => 0, ':startDate' => "1970-01-01", ':endDate' => null,
                ':holiday' => null, ':rotNum' => $nxtRotationNumber);
            $result = $this->DB->executeQuery($insertQuery, $params, "insert");
            if($result < 0) {
                $insertDataMsg = "Error";
            }
            $nxtRotationNumber++;
            $x++;
        }
        $this->RotationDate->insertRotationNum();
        if($insertDataMsg == "Error") {
            return false;
        }else {
            return true;
        }
    }//end insertDateRange

    /* function that prints out the black out weeks for each rotation number
     * @return $result - all the blackout week choices from the date range table
     */
    function showBlackoutWeeks() {
        $sqlQuery = "SELECT * FROM date_range WHERE NOT weekNumber = :weekNumber ORDER BY rotation_number";
        $params = array(':weekNumber' => 0);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        return $result;
    }

    function validateDate($date, $format) {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits, changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }//end validateDate

}//end DateRange