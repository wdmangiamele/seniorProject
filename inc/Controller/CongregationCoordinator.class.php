<?php
/**
 * Created by PhpStorm.
 * User: brypi
 * Date: 9/19/2018
 * Time: 6:58 PM
 */

class CongregationCoordinator {
    function __construct() {
        require_once(__DIR__."/../Data/db.class.php");
        require_once(__DIR__."/Functions.class.php");
        $this->DB = new Database();
        $this->Functions = new Functions();
    }

    /* function to grab all congregation coordinators from MySQL
     * @return $bigString - a formatted HTML Bootstrap table of the MySQL return results
     */
    function getCongregationCoordinators() {
        $sqlQuery = "SELECT * FROM CONGREGATION_COORDINATOR";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        $bigString = "<table class='table'>";
        $bigString .= "<thead>";
        $bigString .= "<tr>";
        $bigString .= "<th scope='col'>#</th>";
        $bigString .= "<th scope='col'>Coordinator Name</th>";
        $bigString .= "<th scope='col'>Coordinator Phone</th>";
        $bigString .= "<th scope='col'>Coordinator Email</th>";
        $bigString .= "</tr>";
        $bigString .= "</thead>";
        $bigString .= "<tbody>";
        for($i = 0; $i < sizeof($result); $i++) {
            $bigString .= "<tr>";
            $bigString .= "<th scope='row'>".($i+1)."</th>";
            $bigString .= "<td>".$this->Functions->testSQLNullValue($result[$i]['coordinatorName'])."</td>";
            $bigString .= "<td>".$this->Functions->testSQLNullValue($result[$i]['coordinatorPhone'])."</td>";
            $bigString .= "<td id='coordinator-email'>".$this->Functions->testSQLNullValue($result[$i]['coordinatorEmail'])."</td>";
            $bigString .= "</tr>";
        }
        $bigString .= "</tbody>";
        $bigString .= "</table>";
        echo $bigString;
    }//end getCongregationCoordinators

    /* function to get the email of a congregation coordinator
     * @param $congID - the ID of a congregation in MySQL
     * @param $userID - the ID of a user in MySQL
     * @return $result[0]["coordinatorEmail"] - the email of the coordinator
     * @return null - return nothing if no data was retrieved
     * */
    function getCoordinatorEmail($congID, $userID) {
        $sqlQuery = "SELECT coordinatorEmail FROM CONGREGATION_COORDINATOR WHERE congID = :congID AND userID = :userID";
        $params = array(":congID" => $congID, ":userID" => $userID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["coordinatorEmail"];
        }else {
            return null;
        }
    }//end getCoordinatorEmail

    /* function to get all emails of a congregation coordinator
     * @return $result - the emails of the coordinators
     * @return null - return nothing if no data was retrieved
     * */
    function getCoordinatorEmailAll() {
        $sqlQuery = "SELECT coordinatorEmail FROM CONGREGATION_COORDINATOR";
        $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getCoordinatorEmailAll

    /* function to get the name of a congregation coordinator
     * @param $congID - the ID of a congregation in MySQL
     * @param $userID - the ID of a user in MySQL
     * @return $result[0]["coordinatorName"] - the name of the coordinator
     * @return null - return nothing if no data was retrieved
     * */
    function getCoordinatorName($congID, $userID) {
        $sqlQuery = "SELECT coordinatorName FROM CONGREGATION_COORDINATOR WHERE congID = :congID AND userID = :userID";
        $params = array(":congID" => $congID, ":userID" => $userID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["coordinatorName"];
        }else {
            return null;
        }
    }//end getCoordinatorName

    /* function to get the phone number of a congregation coordinator
     * @param $congID - the ID of a congregation in MySQL
     * @param $userID - the ID of a user in MySQL
     * @return $result[0]["coordinatorPhone"] - the phone number of the coordinator
     * @return null - return nothing if no data was retrieved
     * */
    function getCoordinatorPhone($congID, $userID) {
        $sqlQuery = "SELECT coordinatorPhone FROM CONGREGATION_COORDINATOR WHERE congID = :congID AND userID = :userID";
        $params = array(":congID" => $congID, ":userID" => $userID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]["coordinatorPhone"];
        }else {
            return null;
        }
    }//end getCoordinatorPhone

    /* function to send email to individual coordinator
     * @param $to - the email you're sending to
     * @param $subject - the subject of the email
     * @param $msg - the actual message
     * */
    function sendCoordintatorEmail($to, $subject, $msg) {
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: brypickering@gmail.com' . "\r\n";

        $sentMail = mail($to, $subject, $msg, $headers);
        if($sentMail) {
            return true;
        }else {
            return false;
        }
    }//end sendCoordintatorEmail

    /* function to set the email of a congregation coordinator
     * @param $congID - the ID of a congregation in MySQL
     * @param $userID - the ID of a user in MySQL
     * @param $email - the new email of a congregation coordinator in MySQL
     * @return boolean - return true or false depending on if the value was successfully set
     * */
    function setCoordinatorEmail($congID, $userID, $email) {
        $sqlQuery = "UPDATE CONGREGATION_COORDINATOR SET coordinatorEmail = :email WHERE congID = :congID AND userID = :userID";
        $params = array(":email" => $email, ":congID" => $congID, ":userID" => $userID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setCoordinatorEmail

    /* function to set the name of a congregation coordinator
      * @param $congID - the ID of a congregation in MySQL
      * @param $userID - the ID of a user in MySQL
      * @param $email - the new name of a congregation coordinator in MySQL
      * @return boolean - return true or false depending on if the value was successfully set
      * */
    function setCoordinatorName($congID, $userID, $name) {
        $sqlQuery = "UPDATE CONGREGATION_COORDINATOR SET coordinatorName = :name WHERE congID = :congID AND userID = :userID";
        $params = array(":name" => $name, ":congID" => $congID, ":userID" => $userID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setCoordinatorName

    /* function to set the phone number of a congregation coordinator
     * @param $congID - the ID of a congregation in MySQL
     * @param $userID - the ID of a user in MySQL
     * @param $email - the new phone number of a congregation coordinator in MySQL
     * @return boolean - return true or false depending on if the value was successfully set
     * */
    function setCoordinatorPhone($congID, $userID, $phoneNum) {
        $sqlQuery = "UPDATE CONGREGATION_COORDINATOR SET coordinatorPhone = :phone WHERE congID = :congID AND userID = :userID";
        $params = array(":phone" => $phoneNum, ":congID" => $congID, ":userID" => $userID);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end setCoordinatorPhone

}//end CongregationCoordinator