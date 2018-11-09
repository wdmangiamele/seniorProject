<?php
	class BusDriver {
		private $db;

		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once('CalendarBus.class.php');
			require_once(__DIR__."/Functions.class.php");
			$this->db = new Database();
			$this->Functions = new Functions();
		}

			/* function to grab the bus driver data from MySQL
	 * echos back a formatted HTML Bootstrap table of the MySQL return results
	 */
		function getBusDriverBlackout() {
			$sqlQuery = "SELECT * FROM bus_blackout";
			$data = $this->db->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");

			// for($i = 0; $i < sizeof($data); $i++) {
			// 	$driverId = testSQLNullValue($data[$i]['driverID']);
			// 	$date = testSQLNullValue($data[$i]['date']);
			// 	$timeOfDay = testSQLNullValue($data[$i]['timeOfDay']);

			// 	$values = array($date, $timeOfDay);

			// 	$result[$row['name']] = $row['id'];

			// 	$blackouts[$driverId] =
			// }

			return $data;

		}//end getBusDriverData



		function insertSchedule($finalSchedule){

			//Get the congregation ID of the current user logged in
			$sqlQuery = "INSERT INTO bus_schedule VALUES (:driverID, :driverName, :dateIs, :timeOfDay, :role, :congID)";


			//parse the date out of the $schedule associative array
	        foreach ($finalSchedule as $key => $value) {
	            $date = substr($value[1],0,10);
	            $timeOf = substr($value[1],10,2);

				$primaryDriverName = $value[2];
				$backupDriverName = $value[5];

				$paramsPrimary = array(":driverID" => $value[0], ":driverName" => $primaryDriverName, ":dateIs" => $date, ":timeOfDay" => $timeOf, ":role" => 'Primary', ":congID" => "1");

				$paramsBackup = array(":driverID" => $value[3], ":driverName" => $backupDriverName, ":dateIs" => $date, ":timeOfDay" => $timeOf, ":role" => 'Backup', ":congID" => "1");

				$result4 = $this->db->executeQuery($sqlQuery, $paramsPrimary, "insert");
				$result5 = $this->db->executeQuery($sqlQuery, $paramsBackup, "insert");
			}

		}//end insertSchedule


		//this function pulls the entire schedule from the database
		function getSchedule(){
			$sqlQuery = "SELECT * FROM bus_schedule";

			$schedule = $this->db->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");

			return $schedule;
		}


		function getScheduleForMonth($month,$year){
			$sqlQuery = "SELECT * FROM bus_schedule where SUBSTRING(date,1,4) = :year AND SUBSTRING(date,6,2) = :month order by date, timeOfDay, role DESC";

			$params = array(":month" => $month, ":year" => $year);

			$schedule = $this->db->executeQuery($sqlQuery, $params, "select");

			return $schedule;
		}


		function getScheduleForADriver($driverID){
			$sqlQuery = "SELECT * FROM bus_schedule where driverID = :driverID";
			$params = array(":driverID" => $driverID);

			$schedule = $this->db->executeQuery($sqlQuery, $params, "select");

			return $schedule;
		}




		//this function will take in all the parameters to insert into the blackouts table
		function insertBlackouts($driverID, $date, $timeOfDay){

			$sqlQuery = "INSERT INTO bus_blackout VALUES (:driverID, :date, :timeOfDay)";

			$params = array(":driverID" => $driverID, ":date" => $date, ":timeOfDay" => $timeOfDay);
			$result = $this->db->executeQuery($sqlQuery, $params, "insert");
		}

		function getBusDriverID($userid) {
			$sqlQuery = "SELECT driverID FROM bus_driver JOIN users USING (userid) WHERE bus_driver.userID = :userid";
			$params = array(':userid' => $userid);
			$result = $this->db->executeQuery($sqlQuery, $params, "select");

			if($result){
				return $result[0]['driverID'];
			}else {
				return null;
			}
		}

		function getAllBlackout(){

			$data = $this->getBusDriverBlackout();

			//associative array with driverID mapped to array
			$blackout_final = array();

			$tempDriveriD = -5;

			$blackouts = array();

			for($i = 0; $i < sizeof($data); $i++) {
				$driverId = $this->Functions->testSQLNullValue($data[$i]['driverID']);
				$date = $this->Functions->testSQLNullValue($data[$i]['date']);
				$timeOfDay = $this->Functions->testSQLNullValue($data[$i]['timeOfDay']);

				//$object = (object) [$date => $timeOfDay];
				$object = (object) ['date' => $date, 'timeof' => $timeOfDay];


				//if the driverid and the temp are the same
				if ($driverId == $tempDriveriD){
					$blackouts[] = $object;
					$blackout_final[$driverId] = $blackouts;
				}
				else{
					//create new key for assoc array
					unset($blackouts);
					$blackouts[] = $object;
					$blackout_final[$driverId] = $blackouts;
				}

				$tempDriveriD = $data[$i]['driverID'];
			}
			return $blackout_final;
		}

		//function to get the bus drivers in order of most black out dates to least

		function getMostBlackouts(){
			$sqlQuery = "SELECT driverID FROM bus_blackout GROUP BY driverID ORDER BY COUNT(driverID) desc";

			$data = $this->db->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");

			return $data;
		}


		//this function will get the available of drivers based on one date, this will be used for manual edits
		function getAvailabilityDate($date, $timeOfDay){


			//go through all the drivers
			$sqlQuery = "SELECT driverID FROM bus_blackout WHERE (date = :date) AND (timeOfDay = :timeOfDay)";
			$params = array(':date' => $date, ':timeOfDay'=>$timeOfDay);
			$result = $this->db->executeQuery($sqlQuery, $params, "select");
		//	echo "<script type='text/javascript'>alert($result.toString());</script>";

			if($result){
				return $result;
			}else {
				return null;
			}
		}


		//this gets an array of [driverID] => 1, [drivingLimit] => 4
		function getDriverLimits(){
			$sql = "SELECT driverID, drivingLimit FROM bus_driver WHERE driverID <> '-1'";

			$data = $this->db->executeQuery($sql, $this->Functions->paramsIsZero(), "select");

			return $data;

		}

		function getNumberOfBusDrivers(){
			$sql = "SELECT count(driverID) FROM bus_driver WHERE driverID <> '-1'";

			$driverCount = $this->db->executeQuery($sql, $this->Functions->paramsIsZero(), "select");

			$numDrivers = $driverCount[0]['count(driverID)'];

			return $numDrivers;
		}

		function getAllDriverNames(){
			$sql = "SELECT driverID, name FROM bus_driver WHERE driverID <> '-1'";

			$driverName = $this->db->executeQuery($sql, $this->Functions->paramsIsZero(), "select");


			return $driverName;
		}

		function getADriverName($driverID){
			$sql = "SELECT name FROM bus_driver WHERE driverID = :driverID";

			$params = array(':driverID' => $driverID);
			$driverName = $this->db->executeQuery($sql, $params, "select");

			return $driverName;
		}

		function getDriverID($driverName){
			$sql = "SELECT driverID FROM bus_driver WHERE name = :driverName";

			$params = array(':driverName' => $driverName);
			$driverID = $this->db->executeQuery($sql, $params, "select");

			return $driverID;
		}

		function getAllDrivers(){
			$sql = "SELECT driverID FROM bus_driver WHERE driverID <> '-1'";

			$drivers = $this->db->executeQuery($sql, $this->Functions->paramsIsZero(), "select");

			return $drivers;
		}


		function editSchedule($driverID, $driverName, $date, $timeOfDay, $role){

			//first need to delete the current record
			$sql = "DELETE FROM bus_schedule WHERE (date = :date) AND (role = :role) AND (timeOfDay = :timeOfDay)";

			$params = array(':date' => $date, ':role'=>$role, ':timeOfDay'=>$timeOfDay);
			$result = $this->db->executeQuery($sql, $params, "DELETE");



			//then insert the new record
			$sqlQuery = "INSERT INTO bus_schedule VALUES (:driverID, :driverName, :date, :timeOfDay, :role, :congID)";

			$params = array(":driverID" => $driverID, ":driverName"=>$driverName, ":date" => $date, ":timeOfDay" => $timeOfDay, ":role"=>$role, ":congID"=>"1");
			$result = $this->db->executeQuery($sqlQuery, $params, "insert");


			if ($result > 0){
				return true;
			}
			else{
				return false;
			}
			//$cb = new CalendarBus();


			//$cb->scheduleDrivers();

		}



		function clearTable($tableName){
			$sql = "DELETE FROM ". $tableName;

			$result = $this->db->executeQuery($sql, $this->Functions->paramsIsZero(), "DELETE");

			return $tableName;
		}

		/* function to grab the bus driver data from MySQL
		 * echos back a formatted HTML Bootstrap table of the MySQL return results
		 */
		function getBusDriverData() {
			$sqlQuery = "SELECT * FROM bus_driver WHERE driverID <> '-1'";
			$data = $this->db->executeQuery($sqlQuery, $this->Functions->paramsIsZero(), "select");
			$bigString = "<table class='table'>";
				$bigString .= "<thead>";
					$bigString .= "<tr>";
						$bigString .= "<th scope='col'>#</th>";
						$bigString .= "<th scope='col'>Bus Driver Name</th>";
						$bigString .= "<th scope='col'>Home Phone</th>";
						$bigString .= "<th scope='col'>Cell Phone</th>";
						$bigString .= "<th scope='col'>Email</th>";
						$bigString .= "<th scope='col'>Address</th>";
					$bigString .= "</tr>";
				$bigString .= "</thead>";
				$bigString .= "<tbody>";
					for($i = 0; $i < sizeof($data); $i++) {
						$bigString .= "<tr>";
							$bigString .= "<th scope='row'>".($i+1)."</th>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['name'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['homePhone'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['cellPhone'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['email'])."</td>";
							$bigString .= "<td>".$this->Functions->testSQLNullValue($data[$i]['address'])."</td>";
						$bigString .= "</tr>";
					}
				$bigString .= "</tbody>";
			$bigString .= "</table>";
			echo $bigString;
		}//end getBusDriverData


		function sendFinalizedBusSchedule($monthHTML){

			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			$headers .= 'From: test@gmail.com' . "\r\n";
			$sentMail = mail("brypickering@gmail.com","Bus Schedule",$monthHTML,$headers);
			if(!$sentMail){
				return false;
			}

			return true;
		}





	}//end BusDriver
?>
