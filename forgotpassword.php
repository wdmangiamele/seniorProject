<?php
    session_start();
    require_once(__DIR__."/inc/Business/Users.class.php");
    require_once(__DIR__."/inc/Business/ForgotPasswordToken.class.php");

    $Users = new Users();
    $ForgotPasswordToken = new ForgotPasswordToken();

    //Check to see if token is set
    if($_GET['state'] == 'pass' && (!isset($_GET['tok']))) {
        $_SESSION['appErrMsg'] = 'Permission Error';
        header('Location: error.php');
    }

    //Checks if token in URL has already been used
    if(isset($_GET['tok'])) {
        //Check if token is has already been used
        $isUsed = $ForgotPasswordToken->getTokenUsage($_GET['tok']);
        if($isUsed == 'Yes') {
            $_SESSION['appErrMsg'] = 'Permission Error';
            header('Location: error.php');
        }elseif(is_null($isUsed)) {
            $_SESSION['appErrMsg'] = 'Admin Error';
            header('Location: error.php');
        }
    }

    require_once("./inc/top_layout.php");

    //Checks to see if there's a reset error message present
    if(isset($_SESSION['resetMsg'])) {
        //Set the current reset error variable equal to its corresponding sessions variable
        $resetMsg = $_SESSION['resetMsg'];

        //Unset or remove the value of the session variable
        unset($_SESSION['resetMsg']);
    }

    //Checks to see if there's a password error message present
    if(isset($_SESSION['pwdMsg'])) {
        //Set the current password error variable equal to its corresponding sessions variable
        $pwdMsg = $_SESSION['pwdMsg'];

        //Unset or remove the value of the session variable
        unset($_SESSION['pwdMsg']);
    }

    //If the send email button is submitted
    if(isset($_POST['email-sub-btn'])) {
        //Verify the email
        if($Users->verifyEmail($_POST['raihn-email'])) {
            $token = $ForgotPasswordToken->createForgotPasswordToken($_POST['raihn-email']);
            if(!is_null($token)) {
                if($Users->sendResetPassEmail($_POST['raihn-email'], $token)){
                    $_SESSION['resetMsg'] = '<div class="alert alert-msg alert-success text-center">Password reset link has been sent to: ' .$_POST['raihn-email']. '</div>';
                    header("Location: forgotpassword.php?state=email");
                }else{
                    $_SESSION['resetMsg'] = '<div class="alert alert-msg alert-danger text-center">Error: Reset failed, please try again later</div>';
                    header("Location: forgotpassword.php?state=email");
                }
            }else {
                $_SESSION['resetMsg'] = '<div class="alert alert-msg alert-danger text-center">Error: Reset failed, please try again later</div>';
                header("Location: forgotpassword.php?state=email");
            }
        }else {
            $_SESSION['resetMsg'] = '<div class="alert alert-msg alert-danger text-center">Error: Incorrect Email!</div>';
            header("Location: forgotpassword.php?state=email");
        }
    }

    if(isset($_POST['pass-submit'])) {
        //Check if token is set as variable in URL
        if(isset($_GET['tok'])) {
            $token = $_GET['tok'];

            $email = $ForgotPasswordToken->getEmail($token);
            $changePassRes = $Users->changePassword($_POST['new-password'], $email);
            if($changePassRes) {
                $updatedToken = $ForgotPasswordToken->updateIsUsedToken($token);
                if($updatedToken) {
                    $_SESSION['loginMsg'] = "<div class='alert alert-success'>
                                              <strong>Success!</strong> Password set!
                                            </div>";
                    header("Location: index.php");
                }else {
                    $_SESSION['pwdMsg'] = "<div class='alert alert-danger'>
                                        <strong>Error!</strong> Trouble setting password. Please contact admin!
                                    </div>";
                    header("Location: forgotpassword.php?state=pass&token=".$token);
                }
            }else {
                $_SESSION['pwdMsg'] = "<div class='alert alert-danger'>
                                        <strong>Error!</strong> Trouble setting password. Please contact admin!
                                    </div>";
                header("Location: forgotpassword.php?state=pass&token=".$token);
            }

        }else {
            $_SESSION['pwdMsg'] = "<div class='alert alert-danger'>
											<strong>Error!</strong> Trouble setting password. Please contact admin!
										</div>";
            header("Location: forgotpassword.php?state=email");
        }
    }
?>
    <?php
        if(isset($pwdMsg)) {
            echo $pwdMsg;
        }
        if(isset($resetMsg)) {
            echo $resetMsg;
        }
    ?>

    <?php if(isset($_GET['state'])): ?>
        <?php if($_GET['state'] == 'email'): ?>
            <form method="post" class="forgot-submit" action="forgotpassword.php">
                <p>Please enter your email for the RAIHN Scheduler App</p>
                <div class="form-group row">
                    <label for="raihn-email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="raihn-email" name="raihn-email" placeholder="Enter email" required>
                    </div>
                </div>
                <div id="submit-button">
                    <button id="email-sub-btn" name="email-sub-btn" type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        <?php elseif ($_GET['state'] == 'pass'): ?>
            <form method="post" class="forgot-submit">
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
        <?php endif; ?>
    <?php endif; ?>
<?php require_once("./inc/bottom_layout.php"); ?>
