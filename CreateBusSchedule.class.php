<?php

    class CreateBusSchedule{


        private $scheduleArrayForm;

        function __construct($month, $year){


            var_dump($month);

            require_once("Schedule.class.php");
            require_once("./inc/Controller/BusDriver.class.php");
            require_once("./inc/Controller/CalendarBus.class.php");

            $bus = new BusDriver();

            $numberOfDrivers = $bus->getNumberOfBusDrivers();

            //testing for Sept, 2018

            $schedule = new Schedule($month, $year,$numberOfDrivers);


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

            // echo "<pre>";
            // print_r($fullScheduleArray);
            // echo "<pre>";

            $bus->clearTable("bus_driver");

            $bus->insertSchedule($fullScheduleArray);


            $this->scheduleArrayForm = $calendarBus->scheduleDrivers();

            // echo "<pre>";
            // print_r($scheduleArrayForm);
            // echo "<pre>";


        }

        // function getSchedule(){
        //     $this->scheduleArrayForm;
        // }




    } //end of class

?>
