<?php
    session_start();
    require_once("./inc/top_layout.php");
?>

<?php
    if(isset($_SESSION['appErrMsg'])) {
        if($_SESSION['appErrMsg'] == 'Login Error') {
            echo "<div class='alert alert-danger'>
                    <p><strong>Error!</strong> You are not logged in!</p>
                    <p id='back-to-login'><a href='index.php'><<< Login</a></p>
                </div>";
        }elseif($_SESSION['appErrMsg'] == 'Permission Error') {
            echo "<div class='alert alert-danger'>
                    <p><strong>Error!</strong> Permission Denied!</p>
                </div>";
        }elseif($_SESSION['appErrMsg'] == 'Admin Error') {
            echo "<div class='alert alert-danger'>
                    <p><strong>Error!</strong> Something went wrong! Contact Admin!</p>
                </div>";
        }
    }
?>

<?php require_once("./inc/bottom_layout.php"); ?>
