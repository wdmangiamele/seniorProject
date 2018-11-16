<?php
	class Congregation {

		function __construct() {
            require_once(__DIR__."/../../Data/db.class.php");
            require_once(__DIR__."/../Functions.class.php");
			$this->DB = new Database();
			$this->Functions = new Functions();
		}

        /* function to get all the congregation names
         * @return $result - MySQL data holding all the congregation names
         * @return null - return nothing if no data was returned
         * */
		function getAllCongregationNames() {
            $sqlQuery = "SELECT congName FROM congregation";
            $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
            if($result) {
                return $result;
            }else {
                return null;
            }
        }//end getAllCongregationNames

		/* function to get all the congregations from MySQL
		 * @return $result - MySQL data holding all the congregations
		 * @return null - return nothing if no data was returned
		 * */
        function getCongregations() {
            $sqlQuery = "SELECT * FROM congregation";
            $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
            if($result) {
                return $result;
            }else {
                return null;
            }
        }//end getCongregations

        /* function to get the address of a congregation in the database
         * @return $result[0]['congAddress'] - the address of the congregation
         * @return null - return nothing if no data was returned
         * */
        function getCongregationAddress($congID) {
            $sqlQuery = "SELECT congAddress FROM congregation WHERE congID = :congID";
            $params = array(":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
            if($result) {
                return $result[0]["congAddress"];
            }else {
                return null;
            }
		}//end getCongregationAddress

        /* function to get the comments of a congregation in the database
         * @return $result[0]['comments'] - the comments of the congregation
         * @return null - return nothing if no data was returned
         * */
        function getCongregationComments($congID) {
            $sqlQuery = "SELECT comments FROM congregation WHERE congID = :congID";
            $params = array(":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
            if($result) {
                return $result[0]["comments"];
            }else {
                return null;
            }
        }//end getCongregationComments

        /* function to get all the congregation IDs
         * @return $result - all the congregation IDs
         * @return null - return nothing if no data was returned
         * */
        function getCongregationID() {
            $sqlQuery = "SELECT congID FROM congregation";
            $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
            if($result) {
                return $result;
            }else {
                return null;
            }
        }//end getCongregationID

        /* function to get the congregation ID by congregation name
         * @param $congName - the name of the congregation
         * @return $result - the desired congregation ID
         * @return null - return nothing if no data was returned
         * */
        function getCongregationIDByName($congName) {
            $sqlQuery = "SELECT congID FROM congregation WHERE congName = :congName";
            $params = array(":congName" => $congName);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
            if($result) {
                return $result[0]["congID"];
            }else {
                return null;
            }
        }//end getCongregationIDByName

        /* function to get a congregation name from MySQL
         * @param $congID - the congregation ID that will be used to help find the name
		 * @return $result - data from MySQL holding congregation name
		 * @return null - return nothing if no data was returned
		 * */
        function getCongregationName($congID) {
		    $sqlQuery = "SELECT congName FROM congregation WHERE congID = :congID";
		    $params = array(":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
            if($result) {
                return $result[0]["congName"];
            }else {
                return null;
            }
        }//end getCongregationName

		/* function to grab the host congregation data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getHostCongregationRoster() {
			$sqlQuery = "SELECT * FROM congregation";
            $result = $this->DB->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
			$bigString = "<table class='table'>";
				$bigString .= "<thead>";
					$bigString .= "<tr>";
						$bigString .= "<th scope='col'>#</th>";
						$bigString .= "<th scope='col'>Congregation Name</th>";
						$bigString .= "<th scope='col'>Congregation Address</th>";
						$bigString .= "<th scope='col'>Comments</th>";
					$bigString .= "</tr>";
				$bigString .= "</thead>";
				$bigString .= "<tbody>";
					for($i = 0; $i < sizeof($result); $i++) {
						$bigString .= "<tr>";
							$bigString .= "<th scope='row'>".($i+1)."</th>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($result[$i]['congName'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($result[$i]['congAddress'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($result[$i]['comments'])."</td>";
						$bigString .= "</tr>";
					}
				$bigString .= "</tbody>";
			$bigString .= "</table>";
			echo $bigString;
		}//end getHostCongregationRoster

        /* function to get the last date a congregation served
         * @param $congID - the ID of the congregation to be looked for
         * @return $result - last date served for a desired congregation
         * @return null - return nothing if no data was retrieved
         * */
        function getLastDateServed($congID) {
            $sqlQuery = "SELECT lastDateServed FROM congregation WHERE congID = :congID";
            $params = array(":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
            if($result) {
                return $result[0]["lastDateServed"];
            }else {
                return null;
            }
        }//end getLastDateServed

        /* function to get the last holiday a congregation served
         * @param $congID - the ID of the congregation to be looked for
         * @return $result - last holiday served for a desired congregation
         * @return null - return nothing if no data was retrieved
         * */
        function getLastHolidayServed($congID) {
            $sqlQuery = "SELECT lastHolidayServed FROM congregation WHERE congID = :congID";
            $params = array(":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
            if($result) {
                return $result[0]["lastHolidayServed"];
            }else {
                return null;
            }
        }//end getLastHolidayServed

        /* function to set the lastDateServed column in MySQL
         * @param $congID - the congregation ID of a certain congregation in MySQL
         * @param $lastDateServed - the new value of the lastDateServed column
         * @return boolean - return true or false depending on if the value was successfully set
         * */
        function setLastDateServed($congID, $lastDateServed) {
            $sqlQuery = "UPDATE congregation SET lastDateServed = :lastDateServed WHERE congID = :congID";
            $params = array(":lastDateServed" => $lastDateServed, ":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "update");
            if($result > 0) {
                return true;
            }else {
                return false;
            }
        }//end setLastDateServed

        /* function to set the lastHolidayServed column in MySQL
         * @param $congID - the congregation ID of a certain congregation in MySQL
         * @param $lastHolidayServed - the new value of the lastHolidayServed column
         * @return boolean - return true or false depending on if the value was successfully set
         * */
        function setLastHolidayServed($congID, $lastHolidayServed) {
            $sqlQuery = "UPDATE congregation SET lastHolidayServed = :lastHolidayServed WHERE congID = :congID";
            $params = array(":lastHolidayServed" => $lastHolidayServed, ":congID" => $congID);
            $result = $this->DB->executeQuery($sqlQuery, $params, "update");
            if($result > 0) {
                return true;
            }else {
                return false;
            }
        }//end setLastHolidayServed

        /* function to update the lastDateServed column in MySQL
         * @param $lastDateServed - the new lastDateServed value
         * @param $lastHolidayServed - the new holiday date, if the date is a holiday
         * @param $congID - the ID of the congregation you're trying to change
         * @return boolean - return true or false based on if the table was successfully updated
         * */
        function updateLastDatesServed($lastDateServed, $lastHolidayServed, $congID) {
            if($lastHolidayServed == "") {
                $sqlQuery = "UPDATE congregation SET lastDateServed = :lastDateServed WHERE congID = :congID";
                $params = array(":lastDateServed" => $lastDateServed, ":congID" => $congID);
                $result = $this->DB->executeQuery($sqlQuery, $params, "update");
                if($result > 0) {
                    return true;
                }else {
                    return false;
                }
            }else {
                $sqlQuery = "UPDATE congregation SET lastDateServed = :lastDateServed, lastHolidayServed = :lastHolidayServed WHERE congID = :congID";
                $params = array(":lastDateServed" => $lastDateServed,":lastHolidayServed" => $lastHolidayServed,":congID" => $congID);
                $result = $this->DB->executeQuery($sqlQuery, $params, "update");
                if($result > 0) {
                    return true;
                }else {
                    return false;
                }
            }
        }//end updateLastDatesServed

	}//end Congregation
?>
