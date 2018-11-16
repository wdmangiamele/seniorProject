<?php


require_once(__DIR__."/../../Business/Bus/CalendarBus.class.php");

$CalendarBus = new CalendarBus();

$busSchedule = $CalendarBus->scheduleDrivers();

echo json_encode($busSchedule);

?>
