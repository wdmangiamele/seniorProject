<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Business/Users.class.php");

    $Users = new Users();

	if(isset($_SESSION['resetPassMsg'])) {
		$resetPassMsg = $_SESSION['resetPassMsg'];

		unset($_SESSION['resetPassMsg']);
	}

	//If the create password button is submitted
	if(isset($_POST['pass-submit'])) {
		$result = $Users->verifyCredentials($_SESSION['email'], $_POST['curr-password']);
		if($result == true) {
			$updtPwdResult = $Users->changePassword($_POST['new-password'], $_SESSION['email']);
			if($updtPwdResult == true) {
                $pwdMsg = "<div class='alert alert-success'>
										  <strong>Success!</strong> Password set!
										</div>";
			}else {
                $pwdMsg = "<div class='alert alert-danger'>
											<strong>Error!</strong> Trouble setting password. Please contact admin!
										</div>";
			}
		}else {
            $pwdMsg = "<div class='alert alert-danger'>
										<strong>Error!</strong> Incorrect current password!
									</div>";
		}
	}
?>
	<?php
		//If there's a current password error message, display it here
		if(isset($pwdMsg)) {
			 echo $pwdMsg;
		}

		if(isset($resetPassMsg)) {
			echo $resetPassMsg;
		}
	?>
	<form method="post" id="login-form"" action="setuppassword.php">
		<div class="form-group row">
			<label for="raihn-password" class="col-sm-2 col-form-label">Current Password</label>
			<div class="col-sm-10">
				<div class="pw-toggle-group">
			  		<input type="password" class="form-control" id="curr-password" name="curr-password" required>
					<a id="regPwToggleLink"><i class="fa fa-eye"></i> Show</a>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="new-password" class="col-sm-2 col-form-label">New Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="new-password" name="new-password" required>
			  <p id="eight-chars-msg"><strong>Password must be 8 characters long</strong><span id="done-word-new"><strong>: DONE!</strong></span></p>
			</div>
		</div>
		<div class="form-group row">
			<label for="conf-password" class="col-sm-2 col-form-label">Confirm New Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="conf-password" name="conf-password" required>
			  <p id="pass-confirm-msg"><strong>Passwords must match</strong><span id="done-word-conf"><strong>: DONE!</strong></span></p>
			</div>
		</div>
		<div id="submit-button">
			<button id="pass-submit" name="pass-submit" type="submit" class="btn btn-primary" disabled>Create Password</button>
		</div>
	</form>

<?php require_once("./inc/bottom_layout.php"); ?>
