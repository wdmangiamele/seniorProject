<?php


require_once("./inc/Controller/CalendarBus.class.php");

$CalendarBus = new CalendarBus();

$busSchedule = $CalendarBus->scheduleDrivers();

echo json_encode($busSchedule);

?>
