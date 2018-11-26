<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $rotNum = $_POST['rotation_number'];

    //Send finalized rotation schedule to all congregations via email
    $sendResult = $CongregationSchedule->sendFinalizedCongSchedule($rotNum);

    //Create JSON data based on if the email was successfully sent
    $sendResultArr = array();
    if($sendResult) {
        $sendResultArr = array(
            "sent" => true
        );
    }else {
        $sendResultArr = array(
            "sent" => false
        );
    }

    echo json_encode($sendResultArr);