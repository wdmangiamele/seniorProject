<?php
  require_once("Schedule.class.php");
	require_once("./inc/Controller/BusDriver.class.php");
	require_once("./inc/Controller/CalendarBus.class.php");
	require_once("./inc/Controller/Blackouts.class.php");


  $bus = new BusDriver();
    //$calendarBus = new CalendarBus();


    $numberOfDrivers = $bus->getNumberOfBusDrivers();

    //testing for Sept, 2018
    $schedule = new Schedule(9,2018,$numberOfDrivers);


    //this gets an array of the drivers with the most to least blackout dates
    $mostBlackouts = $bus->getMostBlackouts();


    $blackouts = $bus->getAllBlackout();

    //make a call to the db to get a list of all the drivers and there associated driver names
    //Array ( [0] => Array ( [driverID] => 1 [name] => John ) [1] => Array ( [driverID] => 2 [name] => Bill )
    $driverNames = $bus->getAllDriverNames();


    $drivingLimits = $bus->getDriverLimits();


  //primary schedule
  $result = $schedule->createDraftSchedule($mostBlackouts, $driverNames, $blackouts, "");

  // echo "<pre>";
  // print_r($result);
  // echo "<pre>";

  //backup schedule
  $calendarWithBackups = $schedule->createDraftSchedule($mostBlackouts, $driverNames, $blackouts, $result);

  $fullScheduleArray = array_merge_recursive($result, $calendarWithBackups);
  
  $calendarBus = new CalendarBus();
  
  $scheduleArrayForm = $calendarBus->scheduleDrivers($fullScheduleArray);

  // echo "<pre>";
  // print_r($scheduleArrayForm);
  // echo "<pre>";


  echo json_encode($scheduleArrayForm);



?>
