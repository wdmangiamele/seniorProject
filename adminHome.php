<?php
    session_start();
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