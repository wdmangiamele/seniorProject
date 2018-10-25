<?php
	session_start();
    require_once("./inc/top_layout.php");
	require_once(__DIR__."/inc/Controller/CongregationCoordinator.class.php");

	$CongregationCoordinator = new CongregationCoordinator();
?>

<?php
  	//Gets the data for all host congregation coordinators in MySQL
	$CongregationCoordinator->getCongregationCoordinators();
?>

<?php require_once("./inc/bottom_layout.php"); ?>
