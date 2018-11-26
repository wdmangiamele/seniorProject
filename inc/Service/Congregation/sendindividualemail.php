<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationCoordinator.class.php");
    $CongregationCoordinator = new CongregationCoordinator();

    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $msg = $_POST['msg'];

    //Send an email to a single congregation
    $sendMailResult = $CongregationCoordinator->sendCoordintatorEmail($to, $subject, $msg);

    //Create JSON data based on if the email was successfully sent
    if($sendMailResult) {
        $sendMailResultArr = array("sent" => true);
        echo json_encode($sendMailResultArr);
    }else {
        $sendMailResultArr = array("sent" => false);
        echo json_encode($sendMailResultArr);
    }