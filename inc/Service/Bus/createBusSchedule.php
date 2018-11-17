<?php
require(__DIR__."/../../Business/Bus/CreateBusSchedule.class.php");


$month = "";
$year = "";




 if (isset($_POST['generateButton'])){

    $month = (int)$_POST['month'];
    $year = (int)$_POST['year'];

    $CreateBusSchedule = new CreateBusSchedule($month, $year);

    header('Location: ../../../finalBusSchedule.php');

}


?>
