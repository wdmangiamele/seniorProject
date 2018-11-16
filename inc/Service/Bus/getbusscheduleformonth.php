<?php
    require_once(__DIR__."/../../Business/Bus/BusDriver.class.php");

    $BusDriver = new BusDriver();

    $month = $_POST['month'];
    $year = $_POST['year'];

    $schedule = $BusDriver->getScheduleForMonth($month, $year);


    // echo "<pre>";
    // print_r($schedule);
    // echo "<pre>";

    echo json_encode($schedule);
?>
