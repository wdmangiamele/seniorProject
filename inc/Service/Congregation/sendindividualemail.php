<?php
    require_once(__DIR__."/CongregationCoordinator.class.php");
    $CongregationCoordinator = new CongregationCoordinator();

    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $msg = $_POST['msg'];

    $sendMailResult = $CongregationCoordinator->sendCoordintatorEmail($to, $subject, $msg);

    if($sendMailResult) {
        $sendMailResultArr = array("sent" => true);
        echo json_encode($sendMailResultArr);
    }else {
        $sendMailResultArr = array("sent" => false);
        echo json_encode($sendMailResultArr);
    }