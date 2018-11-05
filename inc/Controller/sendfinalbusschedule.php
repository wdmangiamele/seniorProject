<?php
    require_once(__DIR__."/BusDriver.class.php");
    $BusDriver = new BusDriver();

    $monthNum = $_POST['month'];

    $sendResult = $BusDriver->sendFinalizedBusSchedule($monthNum);

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
