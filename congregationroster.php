<?php
	session_start();
    if(!isset($_SESSION['userID']) || !isset($_SESSION['role'])) {
        $_SESSION['appErrMsg'] = 'Login Error';
        header('Location: error.php');
    }

    require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Business/Congregation/Congregation.class.php");

    $Congregation = new Congregation();
?>

<?php
    //Gets the data for all host congregations in MySQL
    $Congregation->getHostCongregationRoster();
?>

<?php require_once("./inc/bottom_layout.php"); ?>
