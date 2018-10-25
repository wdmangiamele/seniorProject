<?php
	/*class Calendar {

		function __construct() {
			require_once(__DIR__."/../Data/db.class.php");
			require_once(__DIR__ . "/../functions.php");
            require_once(__DIR__."/RotationDate.class.php");
			$this->db = new Database();
			$this->rotationDate = new RotationDate();
		}



		function loadCalendarYear($blackoutWeek) {
			$blackoutWeekNumArray = array();
			for($i = 0; $i < sizeof($blackoutWeek); $i++) {
				if(date('w', strtotime($blackoutWeek[$i])) == 0) {
					$ddate = $blackoutWeek[$i];
					$date = new DateTime($ddate);
					$week = $date->format("W");
					$week++;
					if($week == 53) {
						$week = 01;
					}
					array_push($blackoutWeekNumArray, "Weeknumber: $week");
				}else {
					$ddate = $blackoutWeek[$i];
					$date = new DateTime($ddate);
					$week = $date->format("W");
					array_push($blackoutWeekNumArray, "Weeknumber: $week");
				}
			}
			return $blackoutWeekNumArray;
		}//end loadCalendarYear

		
	}*/
?>