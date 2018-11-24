<?php


class Schedule{

  private $month;
  private $year;
  private $numberOfDrivers;

  function __construct($_month, $_year, $_numberOfDrivers) {
    $this->month = $_month;
    $this->year = $_year;
    $this->numberOfDrivers = $_numberOfDrivers;
  }


  // Array ( [0] => 2018-09-01 [1] => 2018-09-02 [2] => 2018-09-03 [3] => 2018-09-04 [4] =>
  //this function will return the number of days in a given month, when you specify the monthnumber and the year
  function getDaysInMonth(){
    $monthOutline=array();
 		for($d=1; $d<=31; $d++){
 		    $time=mktime(12, 0, 0, $this->month, $d, $this->year);
            if (date('m', $time)==$this->month){
                $monthOutline[]=date('Y-m-d', $time) . 'AM';
                $monthOutline[]=date('Y-m-d', $time) . 'PM';
            }
        }

    return $monthOutline;
  }

  function getDriverName($driverID, $driverNames){
    for ($i=0; $i<count($driverNames); $i++){

      if ($driverID == $driverNames[$i]['driverID']){
        return $driverNames[$i]['name'];
      }
      else{
        continue;
      }
    }
  } //function getDriverName

  //this function will create a blank array of 10 drivers
  function resetDrivingLimits(){
    $array = array_fill(0, $this->numberOfDrivers, 0);
    return $array;
  }

  // j = Day of the month without leading zeros	 Ex: 1 to 31 for October
  function getWeekNumber($date){
      $date = substr($date,0,10);
      return ceil( date( 'j', strtotime( $date ) ) / 7 );
  }

  function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
  }




  function createDraftSchedule($mostBlackouts, $driverNames,$blackouts, $primarySchedule,$drivingLimits){
    $currentDriverIndex = 0;

    //just sets the first driver we will attempt to schedule
    $currentDriverToSchedule = $mostBlackouts[$currentDriverIndex]['driverID'];

    //Creates a array for that specific month. pass in : monthNumber & Year
    //Array ( [0] => 2018-09-01AM [1] => 2018-09-01PM [2] => 2018-09-02AM [3] => 2018-09-02PM [4] => 2018-09-03AM [5] => 2018-09-03PM [6]
    $monthOutline = $this->getDaysInMonth();

    $storedWeekNumber = 1;
    $draftSchedule = array();

    $areDrivingLimitsZero = false;


    $tempCounterMonthOutline = 1;
    for ($i=0;$i<sizeof($monthOutline);$i++){

        //if even i, update counter, unless first time through
        if ($i != 0){
            if ($i%2 == 0){
                $tempCounterMonthOutline++;
            }
        }

        //week number within the current month
        $week_num = $this->getWeekNumber($monthOutline[$i]); //111112222233344455

        //if the week number has changed, need to reset the # of days driven for each drivers
        if ($week_num != $storedWeekNumber){
            $this->resetDrivingLimits();
        }

        //checks if no one is available on a specific date i.e. if it checks the data against 10 drivers push 'no drivers available' to $draftSchedule
        $unavailableDrivers = 0;

        top:

        $currentDriverToSchedule = $mostBlackouts[$currentDriverIndex]['driverID'];

        //assume they can work this shift
        $scheduleBoolean = true;


        //if the driving limits aren't all 0, we need to go through and do all the logic 
        if ($areDrivingLimitsZero == false){
            //start going through this drivers blackout dates
            for($j=0;$j<sizeof($blackouts[$currentDriverToSchedule]);$j++){
                $currentBlackoutDay = (int)(substr($blackouts[$currentDriverToSchedule][$j]->{'date'}, strrpos($blackouts[$currentDriverToSchedule][$j]->{'date'}, '-') + 1));
                $currentBlackoutDay = $currentBlackoutDay . $blackouts[$currentDriverToSchedule][$j]->{'timeof'};
                $currentSlot = $tempCounterMonthOutline . substr($monthOutline[$i],10,2);


                if($tempCounterMonthOutline >= (sizeof($monthOutline)/2)){
                    $tempCounterMonthOutline = 1;
                }

                //if the blackoutday is equal to the current slot we are trying to fill, don't schedule them!

                if($currentBlackoutDay == $currentSlot) {

                    $unavailableDrivers++;

                    //if we have already gone through all the drivers, need to flag as 'NO DRIVER AVAILABLE'  

                    if($unavailableDrivers == $this->numberOfDrivers){
                        // echo "GOT HERE NO DRIVER " .$currentSlot . "<br/>";
                        // echo "this is " . $monthOutline[$i];

                        // echo "<pre>";
                        // print_r($draftSchedule);
                        // echo "<pre>";

                        if (array_key_exists($monthOutline[$i], $draftSchedule)){
                            $draftSchedule[$monthOutline[$i]] = [-1,$monthOutline[$i],"NO BACKUP DRIVER"];
                        }
                        else{
                            $draftSchedule[$monthOutline[$i]] = [-1,$monthOutline[$i],"NO PRIMARY DRIVER"];
                        }
                        
                        $unavailableDrivers = 0;
                        $scheduleBoolean = false;
                        break;
                    }
                    

                    $scheduleBoolean = false;
                    $currentDriverIndex++;
                    if ($currentDriverIndex == $this->numberOfDrivers){
                        $currentDriverIndex = 0;
                    }

                    goto top; // stops here

                }

            }//end of inner for loop-1

            if ($scheduleBoolean){

                //if primarySchedule isn't null, we will start scheduling blackouts
                // echo " current driver to schedule" . $currentDriverToSchedule;
                // echo " schedule them";
                // echo "<br>";

                //primary schedule is defined, we are making the backup schedule now 
                if($primarySchedule != ""){
                    
                    //when primary driver already scheduled is equal to the driver to schedule, can't schedule as the backup
                    if($primarySchedule[$monthOutline[$i]][0] == $currentDriverToSchedule){
                        $currentDriverIndex++;
                        if ($currentDriverIndex == $this->numberOfDrivers){
                            $currentDriverIndex = 0;
                        }
                        goto top; // stops here
                    }
                    //THIS SCHEDULES BACKUP drivers
                    // When the primary driver isn't the same as the backup driver, valid to schedule
                    else{
                        // echo "currentDriverISTHISKID: " .$currentDriverToSchedule;
                        // echo ("blackout day we are checkin:   ". $currentBlackoutDay);

                        //if the primary driver is NO DRIVER AVAILABLE, then make the backup driver also NO DRIVER AVAILABLE
                        if ($primarySchedule[$monthOutline[$i]][0] == -1){
                            $draftSchedule[$monthOutline[$i]] = [-1,$monthOutline[$i],"NO BACKUP DRIVER AVAILABLE"];
                        }
                        else{
                            $draftSchedule[$monthOutline[$i]] = [($currentDriverToSchedule), $monthOutline[$i], $this->getDriverName($currentDriverToSchedule, $driverNames)];
                            $currentDriverIndex++;
                            if ($currentDriverIndex == $this->numberOfDrivers){
                                $currentDriverIndex = 0;
                            }
    
                            $unavailableDrivers = 0;

                        }
                        
                        //need to increment $drivingLimitsCount for the driver that just got scheduled
                        //$drivingLimitsCount[$currentDriverToSchedule]+=1;

                        //print_r($drivingLimitsCount[$currentDriverToSchedule]);

                    } //end of else
                } //end of if

                //THIS SCHEDUES PRIMARY drivers
                else{ //WHEN primarySchedule is not defined

                    foreach($drivingLimits as $value){
                        if($value == '0'){
                            $areDrivingLimitsZero = true;
                        }
                        else{
                            $areDrivingLimitsZero = false;
                            break;
                        }
                    }


                    // echo "<pre>";
                    // print_r($drivingLimits);
                    // echo "<pre>";
                    // echo "Current driver index " . $currentDriverIndex;
                    // echo "<br>";
                    // echo "Current driving limits for this driver " . $drivingLimits[$currentDriverToSchedule];
                    // echo "<br>";
                    // echo "Current driver to schedule " . $currentDriverToSchedule;
                    // echo "<br>";
                    // echo "Current driver to schedule " . $currentDriverToSchedule;
                    // //is this driver's driving limit 0, then they cant drive 
                    
                    if($drivingLimits[$currentDriverToSchedule] == 0 ){

                        $currentDriverIndex++;
                        if ($currentDriverIndex == $this->numberOfDrivers){
                            $currentDriverIndex = 0;
                        }
                        goto top; // stops here
                    }
                    else{
                        $drivingLimits[$currentDriverToSchedule]--;
                    }

                    $draftSchedule[$monthOutline[$i]] = [($currentDriverToSchedule), $monthOutline[$i], $this->getDriverName($currentDriverToSchedule, $driverNames)];
                    if ($currentDriverIndex == $this->numberOfDrivers){
                        $currentDriverIndex = 0;
                    }
                    else{
                        $currentDriverIndex++;
                    }
                    $unavailableDrivers = 0;


                }

                if ($currentDriverIndex == $this->numberOfDrivers){
                    $currentDriverIndex = 0;
                }


            }//scheduleBoolean
        
        }//end of areDrivingLImitsZero
        
        //this means that all the drivinglimits are 0, so we know there is NO DRIVER AVAILABLE don't need to go through all the logic 
        else{
            
            $draftSchedule[$monthOutline[$i]] = [-1,$monthOutline[$i],"NO PRIMARY DRIVER AVAILABLE"];

        }




    } //end of for outer loop

    return $draftSchedule;

  } //end of function create draft schedule

} //end of class schedule

?>
