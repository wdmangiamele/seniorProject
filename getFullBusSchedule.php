<?php
//   require_once("Schedule.class.php");
// 	require_once("./inc/Controller/BusDriver.class.php");
// 	require_once("./inc/Controller/CalendarBus.class.php");
//
//   $bus = new BusDriver();
//     //$calendarBus = new CalendarBus();
//
//
//     $numberOfDrivers = $bus->getNumberOfBusDrivers();
// 
//     //testing for Sept, 2018
//     $schedule = new Schedule(9,2018,$numberOfDrivers);
//
//
//     //this gets an array of the drivers with the most to least blackout dates
//     $mostBlackouts = $bus->getMostBlackouts();
//
//
//     $blackouts = $bus->getAllBlackout();
//
//     //make a call to the db to get a list of all the drivers and there associated driver names
//     //Array ( [0] => Array ( [driverID] => 1 [name] => John ) [1] => Array ( [driverID] => 2 [name] => Bill )
//     $driverNames = $bus->getAllDriverNames();
//
//
//     $drivingLimits = $bus->getDriverLimits();
//
// //  trying to get it in the form key = driver ID, value = driverlimit and send this to create draft
//     $newDrivingLimits=array();
//
//
//     foreach ($drivingLimits as $drivers){
//       $driverID="";
//       $driverLimit=0;
//       foreach ($drivers as $key => $value){
//
//             if($key=="driverID"){
//               $driverID = $value;
//             }
//             if($key=="drivingLimit"){
//               $drivingLimits = $value;
//             }
//         }
//         $newDrivingLimits[$driverID] = $drivingLimits;
//     }
//
//     // echo "<pre>";
//     // print_r($newDrivingLimits);
//     // echo "<pre>";
//
//   //primary schedule
//   $primarySchedule = $schedule->createDraftSchedule($mostBlackouts, $driverNames, $blackouts, "", $newDrivingLimits);
//
//
//
//
//   // echo "<pre>";
//   // print_r($primarySchedule);
//   // echo "<pre>";
//
//   //backup schedule
//   $calendarWithBackups = $schedule->createDraftSchedule($mostBlackouts, $driverNames, $blackouts, $primarySchedule, $newDrivingLimits);
//
//   $fullScheduleArray = array_merge_recursive($primarySchedule, $calendarWithBackups);
//
//   $calendarBus = new CalendarBus();
//
//   $scheduleArrayForm = $calendarBus->scheduleDrivers($fullScheduleArray);
//
//   // echo "<pre>";
//   // print_r($scheduleArrayForm);
//   // echo "<pre>";
//
//
//   //NEED TO UNCOMMENT FOR THE SCHEDULE TO SHOW
//   echo json_encode($scheduleArrayForm);



?>
