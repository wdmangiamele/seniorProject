<?php
    session_start();
    if(!isset($_SESSION['userID']) || !isset($_SESSION['role'])) {
        $_SESSION['appErrMsg'] = 'Login Error';
        header('Location: error.php');
    }elseif($_SESSION['role'] != 'Admin') {
        $_SESSION['appErrMsg'] = 'Permission Error';
        header('Location: error.php');
    }

    require_once('./inc/top_layout.php');
?>

<?php
    echo "<div id='main-body'>";
        if(isset($_SESSION['email'])) {
            echo "<h1>Welcome ".$_SESSION['email']."!</h1>";
        }
    echo "</div>";
?>

<?php require_once('./inc/bottom_layout.php'); ?>