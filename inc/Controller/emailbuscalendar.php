<?php


require_once("BusDriver.class.php");

$htmlBusSchedule = $_POST['htmlSchedule'];

$BusDriver = new BusDriver();

$busSchedule = $BusDriver->sendFinalizedBusSchedule($htmlBusSchedule);



$sendResultArr = array();
if($busSchedule) {
    $sendResultArr = array(
        "sent" => true
    );
}else {
    $sendResultArr = array(
        "sent" => false
    );
}


echo json_encode($sendResultArr);

?>
