<?php
    require_once("BusDriver.class.php");

    $BusDriver = new BusDriver();

    $month = $_POST['month'];
    $year = $_POST['year'];

    $schedule = $BusDriver->getScheduleForMonth($month, $year);

    echo json_encode($schedule);
?>
