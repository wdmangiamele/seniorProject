<?php
	require_once("./inc/Data/db.class.php");
	$this->db = new Database();

	echo getCongBlackouts();

	//First, grab congregations and their blackout dates
	function getCongBlackouts() {
		$sqlQuery = "SELECT * FROM congregation_blackout";
		$data = $this->db->executeQuery($sqlQuery,paramsIsZero(),"select");
		return $data;
	}

	//Second, loop through all congregations with their blackout dates and
			//count out each date that's blacked out
	function getBlackouts() {
		$congCount = getCongregations();
		$congSubmitted = getCongBlackouts();
		$threshold = $congCount * .85;
		if($congSubmitted >= $threshold) {

		}


	}

	//Third, sort the dates from most to least blacked out
	//Return sorted array
	function sortBlackouts() {

	}

	//Forth, check to see if more than 5 host congregations have a week blacked out
			//Schedule that week first
	function moreThan5Congregations() {

	}

	//Fifth, start at the first week, looking first at the congregation who has the most blacked out weeks
			//Compare the week they're about to be scheduled for to the last week they were scheduled
				//Make sure that the week they're about to be scheduled for is at least 10 weeks apart from the last time they were scheduled
					//If the week is scheduled at least 10 weeks apart:
						//If there's a holiday for that week:
							//Check if the congregation did the holiday last
								//If the congregation hasn't already done the holiday:
									//insert the holiday into the Congregation table for the respective congregation
								//Else:
									//don't schedule the congregation
						//Else:
							//Schedule the congregation
					//Else: return false

			//Then go to the next most blacked out congregation
	function scheduleBlackouts() {

	}

	function getCongregations() {
		//Get the total number of Congregations
	}
?>
