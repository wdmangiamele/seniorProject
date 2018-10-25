<?php

class CalendarBus {

  function __construct() {
    require_once("./inc/Controller/GoogleCalendarBus.php");
    require_once("testBlackoutsDRIVERS.php");


    $this->gCalendar = new GoogleCalendarBus();

    $this->client = $this->gCalendar->getClient();
    $this->service = new Google_Service_Calendar($this->client);

  } //end of constructor


  //pass in the final schedule so that we can push it
  function scheduleDrivers($schedule){

//    echo "<pre>";
//    print_r ($schedule);
//    echo "<pre>";


    //parse the date out of the $schedule associative array
    foreach ($schedule as $key => $value) {
      //echo "I went thru ";


        // echo "<pre>";
        // print_r ($value);
        // echo "<pre>";

        $driverID = $value[0];
        $date = $value[1];

        $event = new Google_Service_Calendar_Event(array(
            'summary' => 'John Smith',
            'location' => 'Host Congregation 1',
            'description' => 'Park on the right side of the street',
            'start' => array(
                'timeZone' => 'America/New_York',
                'dateTime' => $date."T09:00:00",
            ),
            'end' => array(
                'timeZone' => 'America/New_York',
                'dateTime' => $date . "T09:00:00",
            ),
        ));
        $calendarId = 'raihnbusdriver@gmail.com';
        $event = $this->service->events->insert($calendarId, $event);
        $finalHostCongScheduleArr = true;

    }


  }


} //end of class



 ?>
