<?php
	//load session vars
	session_start();


	unset($_SESSION['email']);
	unset($_SESSION['role']);
	$_SESSION['loginMsg'] = "<div class='alert alert-success'>
								Successfully logged out!
							</div>";
	header("Location: ./login.php");
?>
