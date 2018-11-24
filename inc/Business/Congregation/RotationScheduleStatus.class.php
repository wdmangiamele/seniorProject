<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 11/21/2018
 * Time: 2:03 PM
 */

class RotationScheduleStatus {
    function __construct() {
        require_once(__DIR__."/../../Data/db.class.php");
        require_once(__DIR__."/../Functions.class.php");

        $this->DB = new Database();
        $this->Functions = new Functions();
    }
    /* function to get any rotations not scheduled or finalized
     * @return $result - any non finalized or scheduled rotations
     * @return null - return nothing if no rotations were found
     * */
    function getNonScheduledFinalizedRotations() {
        $sqlQuery = "SELECT * FROM rotation_schedule_status WHERE NOT rotation_status = :schStatus AND NOT rotation_status = :finalStatus";
        $params = array(":schStatus" => 'Scheduled', ":finalStatus" => 'Finalized');
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getNonScheduledFinalizedRotations

    /* function to get any rotations scheduled or finalized
     * @return $result - any finalized or scheduled rotations
     * @return null - return nothing if no rotations were found
     * */
    function getScheduledFinalizedRotations() {
        $sqlQuery = "SELECT * FROM rotation_schedule_status WHERE rotation_status = :schStatus OR rotation_status = :finalStatus";
        $params = array(":schStatus" => 'Scheduled', ":finalStatus" => 'Finalized');
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getScheduledFinalizedRotations

    /* function to insert rotation numbers with a status of ('Not Scheduled', 'Scheduled', 'Finalized')
     * @return $insertResult - either true or false depending on if the data was successfully inserted
     * */
    function insertRotationStatus() {
        $sqlQuery = "SELECT DISTINCT rotation_number FROM date_range";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(),"select");
        $insertResult = true;

        if($result) {
            for($i = 0; $i < sizeof($result); $i++) {
                $sqlQuery2 = "INSERT INTO rotation_schedule_status VALUES (:rotNum, :status)";
                $params = array(":rotNum" => $result[$i]["rotation_number"], ":status" => 'Not Scheduled');
                $result3 = $this->DB->executeQuery($sqlQuery2, $params, "insert");
                if($result3 < 0) {
                    $insertResult = false;
                    return $insertResult;
                }
            }
        }
        return $insertResult;
    }//end insertRotationStatus

    /* function to update rotation status
     * @param $rotNum - the rotation number to be updated
     * @param $status - the new status
     * @return boolean - return true or false if the rotation status was updated
     * */
    function updateRotationStatus($rotNum, $status) {
        $sqlQuery = "UPDATE rotation_schedule_status SET rotation_status = :rotStatus WHERE rotation_number = :rotNum";
        $params = array(":rotStatus" => $status, ":rotNum" => $rotNum);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end updateRotationStatus
}