<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/18/2018
 * Time: 11:45 AM
 */

class RotationDate {
    function __construct() {
        require_once(__DIR__."/../Data/db.class.php");
        require_once(__DIR__."/Functions.class.php");
        $this->DB = new Database();
        $this->Functions = new Functions();
    }

    /* function to insert rotation numbers with start and end dates
     * @return $insertResult - either true or false depending on if the data was successfully inserted
     * */
    function insertRotationNum() {
        $sqlQuery = "SELECT DISTINCT rotation_number FROM date_range";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(),"select");
        $insertResult = true;

        if($result) {
            for($i = 0; $i < sizeof($result); $i++) {
                $sqlQuery2 = "SELECT startDate, endDate FROM date_range WHERE rotation_number = :rotNum";
                $params = array(":rotNum" => $result[$i]["rotation_number"]);
                $result2 = $this->DB->executeQuery($sqlQuery2, $params, "select");
                $startDateOfRotation = $result2[0]["startDate"];
                $endDateOfRotation = $result2[sizeof($result2) - 1]["endDate"];
                $sqlQuery3 = "INSERT INTO rotation_date VALUES (:rotNum2, :startDate, :endDate)";
                $params = array(":rotNum2" => $result[$i]["rotation_number"],
                                ":startDate" => $startDateOfRotation, ":endDate" => $endDateOfRotation);
                $result3 = $this->DB->executeQuery($sqlQuery3, $params, "insert");
                if($result3 < 0) {
                    $insertResult = false;
                    return $insertResult;
                }
            }
        }
        return $insertResult;
    }//end insertRotationNum

}//end RotationDate