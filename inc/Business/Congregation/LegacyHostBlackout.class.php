<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 7:51 PM
 */

class LegacyHostBlackout {
    function __construct() {
        require_once(__DIR__."/../../Data/db.class.php");
        require_once(__DIR__."/../Functions.class.php");
        $this->DB = new Database();
        $this->Functions = new Functions();
    }//end LegacyHostBlackout constructor

    /* function to get all the legacy data
     * @return $result - all the legacy data
     * @return null - return no data if no data successfully fetched
     * */
    function getLegacyData() {
        $sqlQuery = "SELECT * FROM legacy_host_blackout";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getLegacyData

    /* function to get all the legacy data for one congregation
     * @param $congID - the congregation ID of a certain congregation in MySQL
     * @return $result - all the legacy data for one congregation
     * @return null - return no data if no data successfully fetched
     * */
    function getLegacyDataForOneCong($congID) {
        $sqlQuery = "SELECT * FROM legacy_host_blackout WHERE congID = :congID";
        $params = array(":congID" => $congID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getLegacyDataForOneCong

    /* function to get all the legacy data for one rotation
     * @param $rotNum - the desired rotation number
     * @return $result - all the legacy data for one rotation
     * @return null - return no data if no data successfully fetched
     * */
    function getLegacyDataForOneRotation($rotNum) {
        $sqlQuery = "SELECT * FROM legacy_host_blackout WHERE rotation_number = :rotNum ORDER BY startDate";
        $params = array(":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getLegacyDataForOneRotation

    /* function to get distinct rotation numbers
     * @return $result - all the distinct rotation nums
     * @return null - return no data if no data successfully fetched
     * */
    function getDistinctRotationNums() {
        $sqlQuery = "SELECT DISTINCT rotation_number FROM legacy_host_blackout ORDER BY rotation_number DESC";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getDistinctRotationNums

    /* function that inserts legacy data to legacy_host_blackout table in MySQL
     * @param $congID - the ID of the congregation to be inserted
     * @param $startDate - the start date of the week the congregation is scheduled for
     * @param $endDate - the end date of the week the congregation is scheduled for
     * @param $weekNumber - the rotation week number that congregation is scheduled for
     * @param $rotationNumber - the rotation number of the week the congregation is scheduled for
     * @return boolean - return true or false depending on if the data was inserted
     * */
    function insertLegacyData($congID, $startDate, $endDate, $holiday, $rotationNumber) {
        $sqlQuery = "INSERT INTO legacy_host_blackout VALUES (:congID, :startDate, :endDate, :holiday, :rotNum)";
        $params = array(":congID" => $congID, ":startDate" => $startDate,
            ":endDate" => $endDate, ":holiday" => $holiday, ":rotNum" => $rotationNumber);
        $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end insertLegacyData

}//end LegacyHostBlackout