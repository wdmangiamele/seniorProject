<?php

class CalendarBus {

private $bus;


  function __construct() {
    require_once(__DIR__."/BusDriver.class.php");
    require_once(__DIR__."/CalendarBus.class.php");

    $this->bus = new BusDriver();

  } //end of constructor




  //pass in the final schedule so that we can push it
  function scheduleDrivers(){ //NO PARAMETER


      $finalBusDriverScheduleArr = array();
      $schedule = $this->bus->getSchedule();

      // echo "<pre>";
      // print_r($schedule);
      // echo "<pre>";
      //print_r($schedule);
      //parse the date out of the $schedule associative array
      foreach ($schedule as $key => $value) {
          $driverName = $value['driverName'];
          $date =$value['date'];
          $timeOf = $value['timeOfDay'];
          $realTime = "";
          $color;
      //echo "timeOF " . $timeOf;
          if($timeOf =='AM'){
              $realTime = "T09:00:00";
          }
          else{
              $realTime = "T18:00:00";
          }

          if ($value['role'] == 'Primary'){
              $color = "#0000ff";
          }
          else if ($value['role'] == 'Backup'){
              $color = "#008000";
          }

            //printf("BACKUPGUY" . $backupDriverName);

          //if not available, make red
          if ($driverName == "NO PRIMARY DRIVER AVAILABLE"){
            $color =  "#f20000";
          }
          else if ($driverName == "NO BACKUP DRIVER AVAILABLE"){
            $color =  "#f20000";
          }
          

          $driver = array(
              "title" => $driverName,
              "start" => $date . $realTime,
              "end" => $date . $realTime,
              "color"=> $color
          );

          array_push($finalBusDriverScheduleArr, $driver);


      } //for each

      return $finalBusDriverScheduleArr;

  }//function scheduleDrivers


} //end of class



 ?>
