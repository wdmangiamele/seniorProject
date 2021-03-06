<?php
   require_once(__DIR__.'/../../Business/Bus/BusDriver.class.php');

   //start the session
   session_start();
   $date = "";
   $timeOfDay = "";

   $db = new BusDriver();


 if (isset($_POST['submitLimits'])){

    $dayLimit = $_POST['drivingLimits'];
    $driverID = $db->getBusDriverID($_SESSION['userID']);

    $result = $db->updateDriverLimits($driverID, $dayLimit);

    header('Location: ../../../inputBusBlackouts.php');

}




if (isset($_POST['type'])){
    switch ($_POST['type']){

       case 'manualEdit':

          //   $db = new BusDriver();

            $date = $_POST['date'];
            $timeOfDay = $_POST['timeOfDay'];

            $result2 = $db->getAvailabilityDate($date, $timeOfDay);


            //this result looks like
            //Array ([0]=>Array ([driverID] => 1))
            //these are the drivers that are NOT AVAILABLE

            //create a assoociateve array of ALL bus drivers KEy: BUS ID, Alue: Blacked/Whited
            $allBusDrivers = $db->getAllDrivers();
            $allDrivers = array();
            $cantDrive = array();
            foreach($allBusDrivers as $value){
                //this is an array of all the drivers
                $allDrivers[] = $value['driverID'];
            }

            if($result2 != null){
                foreach($result2 as $value2){
                    //this is an array of all the drivers that can't drive
                    $cantDrive[] = $value2['driverID'];
                }
            }
            else{
                $cantDrive = ['driverID'=>'1'];
            }

            $possibleDrivers = array_diff($allDrivers, $cantDrive);



            if(count($cantDrive) == 1){
                $possibleDrivers[0]= '1';
            }

            //get possible driver names
            foreach($possibleDrivers as $value){
            //get driver name based on $values
                $name = $db->getADriverName($value);
                $driverNames[] = $name;
            }

            //get driver names
            foreach($allDrivers as $value){
                //get driver name based on $values
                $name = $db->getADriverName($value);
                $allDriverNames[] = $name;
            }

            if (sizeof($possibleDrivers) == 0){
                echo json_encode($allDriverNames);
                break;
            }
            else{
                echo json_encode($driverNames);
                break;
            }


         case 'sendToDB':
             $db = new BusDriver();
             $driverName = $_POST['driver'];

             $date = $_POST['date'];
             $timeOfDay = $_POST['timeOfDay'];
             $role = $_POST['role'];

             $result = $db->getDriverID($driverName);
             $driverID = $result[0]['driverID'];


             $db->editSchedule($driverID, $driverName, $date, $timeOfDay, $role);
        case 'inputBlackouts':

            $db = new BusDriver();

            $date = $_POST['date'];
            $timeOfDay = $_POST['timeOfDay'];
            $driverID = $db->getBusDriverID($_SESSION['userID']);

            $db->insertBlackouts($driverID, $date, $timeOfDay);
        case 'checkblackouts':

            $db = new BusDriver();


            $month = (int)$_POST['month'];
            $year = (int)$_POST['year'];

            $allBlackouts = $db->getAllSubmittedBlackouts();

            $blackoutDrivers = $db->getNonSubmittedBlackouts($month, $year);

            $blackoutsall = array();
            foreach($allBlackouts as $value){
                //this is an array of all the drivers
                //print_r($allBlackouts);
                $blackouts[] = $value['name'];
            }

            $blackoutsthis = array();
            foreach($blackoutDrivers as $value){
                //this is an array of all the drivers
                //print_r($allBlackouts);
                $blackoutsthis[] = $value['name'];
            }

            $diffArray = array_diff($blackouts, $blackoutsthis);

            echo json_encode($diffArray);
            break;


    }


}




?>
