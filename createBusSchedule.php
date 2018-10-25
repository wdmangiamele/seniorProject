<?php
require("./CreateBusSchedule.class.php");

//  if (isset($_POST['submit'])){
//
//     $month = $_POST['month'];
//     $year = $_POST['year'];
//
//
// }

$CreateBusSchedule = new CreateBusSchedule(10, 2018);

header('Location: finalBusSchedule.php');




 ?>
