<?php
    require_once(__DIR__."/../../Business/Congregation/CongregationSchedule.class.php");
    $CongregationSchedule = new CongregationSchedule();

    $rotNum = $_POST['rotation_number'];

    $sendResult = $CongregationSchedule->sendFinalizedCongSchedule($rotNum);

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