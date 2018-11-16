<?php
	session_start();
    require_once("./inc/top_layout.php");
    require_once("./inc/Business/Bus/BusDriver.class.php");
?>


<?php
	$busDriver = new BusDriver();

	//Gets the data for all the bus drivers
	
	// $busDriver->getBusDriverData();
	// echo "<pre>";
	// print_r($busDriver->getScheduleForADriver(1));
	// echo "<pre>";




?>

<?php require_once("./inc/bottom_layout.php"); ?>
