<?php
	session_start();
    require_once("./inc/top_layout.php");
    require_once("./inc/Business/Bus/BusDriver.class.php");

    if(!isset($_SESSION['userID']) || !isset($_SESSION['role'])) {
        $_SESSION['appErrMsg'] = 'Login Error';
        header('Location: error.php');
    }
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
