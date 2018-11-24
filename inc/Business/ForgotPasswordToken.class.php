<?php

class ForgotPasswordToken {

    function __construct() {
        require_once(__DIR__."/../Data/db.class.php");
        require_once(__DIR__."/Functions.class.php");
        $this->DB = new Database();
        $this->Functions = new Functions();
    }

    /* function to create token for forgot password table
     * @param $email - the correct user role
     * @return $token - token for forgot password
     * @return null - return null if no token was inserted
     */
    function createForgotPasswordToken($email) {
        //Create token based on the length of the email
        $bytes = openssl_random_pseudo_bytes ( strlen($email) );
        $token = bin2hex($bytes);
        $sqlQuery = "INSERT INTO forgot_password_token (token, email, isUsed) VALUES (:token, :email, :isUsed)";
        $params = array(':token' => $token, ':email' => $email, ':isUsed' => 'No');
        $result = $this->DB->executeQuery($sqlQuery, $params, "insert");
        if($result > 0){
            return $token;
        }else {
            return null;
        }
    }//end createForgotPasswordToken

    /* function get the email based on the token created
     * @param $token - the token used for forget password
     * @return $result - the email of the user that used the token
     * @return null - return null is no email was got
     * */
    function getEmail($token) {
        $sqlQuery = "SELECT * FROM forgot_password_token WHERE token = :token";
        $params = array(':token' => $token);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]['email'];
        }else {
            return null;
        }
    }//end getEmail

    /* function to get token
     * @return $result - return token
     * @return null - return null if no token was gotten
     * */
    function getToken($token) {
        $sqlQuery = "SELECT token FROM users WHERE token = :token";
        $params = array(':token' => $token);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result;
        }else {
            return null;
        }
    }//end getToken

    /* function to see if token is already used
     * @param $token - desired token
     * @return $result - the isUsed parameter for a token
     * @return null - return null if nothing was received
     * */
    function getTokenUsage($token) {
        $sqlQuery = "SELECT isUsed FROM forgot_password_token WHERE token = :token";
        $params = array(':token' => $token);
        $result = $this->DB->executeQuery($sqlQuery, $params, "select");
        if($result) {
            return $result[0]['isUsed'];
        }else {
            return null;
        }
    }//end getTokenUsage

    /* function to update the token used status to 'yes' isUsed
     * @param $token - the token that was used
     * @result boolean - return true or false depending on if it was updated
     * */
    function updateIsUsedToken($token) {
        $sqlQuery = "UPDATE forgot_password_token SET isUsed = :isUsed WHERE token = :token";
        $params = array(':isUsed' => 'Yes', ':token' => $token);
        $result = $this->DB->executeQuery($sqlQuery, $params, "update");
        if($result > 0) {
            return true;
        }else {
            return false;
        }
    }//end updateIsUsedToken
}