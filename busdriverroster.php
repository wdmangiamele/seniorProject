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
    $busDriver->getBusDriverData();
?>

<?php require_once("./inc/bottom_layout.php"); ?>
