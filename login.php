<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Controller/Users.class.php");

    $Users = new Users();

	//Checks to see if the login error is present
	if(isset($_SESSION['loginMsg'])) {
		//Set the login value from the session variable and store into a new variable
		$loginMsg = $_SESSION['loginMsg'];

		//Remove the value of the session variable
		unset($_SESSION['loginMsg']);
	}

	//If the sign in button was pressed, verify the user
	if(isset($_POST['login-submit'])) {
		//If the email is correct, then verify the password
		$result = $Users->verifyCredentials($_POST['raihn-email'], $_POST['raihn-password']);
		if($result == true) {
			$needsNewPass = $Users->needsNewPass($_POST['raihn-email']);
			if($needsNewPass) {
				$_SESSION['email'] = $_POST['raihn-email'];
				$_SESSION['role'] = $Users->getUserRole($_POST['raihn-email']);
                $_SESSION['userID'] = $Users->getUserID($_POST['raihn-email']);
				$_SESSION['resetPassMsg'] = "<div class='alert alert-warning'>
												<strong>Warning!</strong> Must reset your password!
											</div>";
				header("Location: ./setuppassword.php");
			}else {
				//Sets a session variable to store the user's email
				$_SESSION['email'] = $_POST['raihn-email'];
				$_SESSION['role'] = $Users->getUserRole($_POST['raihn-email']);
				$_SESSION['userID'] = $Users->getUserID($_POST['raihn-email']);
				header("Location: ./index.php");
			}
		}else {
			//Create login error that will be saved as a session variable
			$_SESSION['loginMsg'] = "<div class='alert alert-danger'>
											<strong>Error!</strong> Incorrect email or password!
										</div>";

			//Redirect the user back to the login page
			header("Location: login.php");
		}
	}
?>
	<?php
		//If there's a login message, display it here
		if(isset($loginMsg)) {
			 echo $loginMsg;
		}
	?>
	<form method="post" id="login-form" action="login.php">
		<div class="form-group row">
			<label for="raihn-email" class="col-sm-2 col-form-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="raihn-email" name="raihn-email" placeholder="Enter email" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-10">
				<div class="pw-toggle-group">
				  	<input type="password" class="form-control" id="raihn-password" name="raihn-password" placeholder="Password" required>
					<a id="regPwToggleLink"><i class="fa fa-eye"></i> Show</a>
				</div>
			</div>
		</div>
        <p><a href="forgotpassword.php?state=email">Forgot Password</a></p>
		<div id="submit-button">
			<button id="login-submit" name="login-submit" type="submit" class="btn btn-primary">Sign In</button>
		</div>
	</form>

<?php require_once("./inc/bottom_layout.php"); ?>
