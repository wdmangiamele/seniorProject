<?php
require(__DIR__."/../../Business/Bus/CreateBusSchedule.class.php");
require(__DIR__."/../../Business/Bus/BusDriver.class.php");


$month = (int)$_POST['month'];
$year = (int)$_POST['year'];

$CreateBusSchedule = new CreateBusSchedule($month, $year);

$busSchedule = array("busSchedule" => true);


echo json_encode($busSchedule);



?>
