<?php
	class Users {

		function __construct() {
            require_once(__DIR__."/../Data/db.class.php");
            require_once(__DIR__."/Functions.class.php");
            $this->DB = new Database();
            $this->Functions = new Functions();
		}

		/* function to change the password of the respective user
		 * @return boolean - boolean showing if password was successfully changed
		 */
		function changePassword($newPass, $email) {
			$bytes = openssl_random_pseudo_bytes ( strlen($newPass) );
			$salt = bin2hex($bytes);
			$hashedPass = hash('sha256', ($newPass.$salt));
			$userID = $this->getUserID($email);

			$sqlQuery = "UPDATE users SET password = :pass, salt = :salt WHERE userID = :userID";
			$params = array(':pass' => $hashedPass, ':salt' => $salt, ':userID' => $userID);
			$result = $this->DB->executeQuery($sqlQuery, $params, "update");
			if($result > 0) {
				return true;
			}else {
				return false;
			}

		}//end changePassword

        /* function to check the user role see if it's correctly able to see a page
         * @param $correctUserRole - the correct user role
         * @param $currUserRole - the current role of the user
         * @return boolean - boolean showing user is correct
         */
		function checkIncorrectUserRole($correctUserRole, $currUserRole) {

		}//end checkIncorrectUserRole

		/* function to get the user ID from MySQL
		 * @param $email - email of the user
		 * @return $result[0]['userID'] - the user ID from MySQL
		 */
		function getUserID($email) {
			$sqlQuery = "SELECT userID FROM users WHERE email = :email";
			$params = array(':email' => $email);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
			if($result[0]['userID']){
				return $result[0]['userID'];
			}else {
				return null;
			}
		}

		/* function to get the user role from MySQL
		 * @param $email - email of the user
		 * @return $result[0]['userType'] - the user role from MySQL
		 */
		function getUserRole($email) {
			$sqlQuery = "SELECT userType FROM users WHERE email = :email";
			$params = array(':email' => $email);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
			if($result[0]['userType']){
				return $result[0]['userType'];
			}else {
				return null;
			}
		}//end getUserRole

		/* function to check if the user needs to change their login password
		 * @param $email - the user's email to help find the salt for the password
		 */
		function needsNewPass($email) {
			$sqlQuery = "SELECT salt FROM users WHERE email = :email";
			$params = array(':email' => $email);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
			if(is_null($result[0]["salt"])) {
				return true;
			}else {
				return false;
			}
		}//end checkIfNewUser

		/* function that will send a reset password email
		 * @param $email - the email of the user who needs their password reset
		 * @return boolean - returns true or false based upon if the email was sent
		 * */
        function sendResetPassEmail($email) {
            $to = $email;
            $subject   = 'Forgotten Password Reset - Raihn Scheduler App';
            $resetlink = 'http://localhost:8012/RAIHN/forgotpassword.php?state=pass&email='.$email;
            $message   = '<html>
										<head>
											<title>Forgotten Password For Sports Agent Directory</title>
										</head>
										<body>
											<p>
												We have received a Reset Password request for your account.
												To reset your password, simply click the link below and enter your new password.
												If you did not request a new password or you no longer need to reset your password, you may simply ignore this email.
											</p>
											<br/>
											<p>Click on the following link to reset your password: <br/> <a href="'.$resetlink.'">'.$resetlink.'</a></p>
										</body>
									  </html> ';
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            $headers .= 'From: brypickering@gmail.com' . "\r\n";

            if(mail($to,$subject,$message,$headers)){
                return true;
            }else{
                return false;
            }
        }//end sendResetPassEmail

		/* function that verifies the user's email and password
		 * @param $email - the email that the user enters
		 * @param $pass - the password that the user enters
		 * @return bool - returns either true or false if the credentials are correct
		 */
		function verifyCredentials($email, $pass) {
			$sqlQuery = "SELECT email, password, salt FROM users WHERE email = :email";
			$params = array(':email' => $email);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
			if(is_null($result[0]["email"])) {
				return false;
			}

			//Checks to see if the password that was entered was correct even if there is no salt
			if(is_null($result[0]["salt"])) {
				if(sizeof($result) == 0) {
					return false;
				}else {
					if($result[0]["password"] != $pass) {
						return false;
					}else {
						return true;
					}
				}
			}else{
				//Checks to see if the password that was entered was correct when there is salt
				if(sizeof($result) == 0) {
					return false;
				}else {
					$hashedPass = hash('sha256', ($pass.$result[0]["salt"]));
					if($hashedPass != $result[0]["password"]) {
						return false;
					}else {
						return true;
					}
				}
			}
		}//end verifyCredentials

        /* function that verifies just the user's email
         * @param $email - the email that the user enters
         * @return boolean - returns either true or false if the email is correct
         */
        function verifyEmail($email) {
		    $sqlQuery = "SELECT email FROM users WHERE email = :email";
		    $params = array(":email" => $email);
            $result = $this->DB->executeQuery($sqlQuery, $params, "select");
		    if(is_null($result[0]['email'])) {
		        return false;
            }else {
		        return true;
            }
        }//end verifyEmail

	}//end Users
?>
