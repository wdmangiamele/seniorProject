<?php


   //check how many drivers have submitted their blackout dates
	function getBusDriverBlackoutCount() {

	}


   //First, grab the bus drivers and their associated blackout dates
   //DriverID, date, timeofday

   function getBlackouts(){

   }


   //counts how many drivers have submitted their blackout dates
   //only when this threshold is reached, will the algorithm run
   function getBlackoutSubmitted(){
      $driverCount = getDriverCount();
      $driverSubmitted = getBusDriverBlackoutCount();
      $threshold = $driverCount * 0.85;
      if ($driverSubmitted >= $threshold){

      }
   }

   //returns the number of total drivers
   function getDriverCount(){

   }
   
   //we want to get the bus driver that has the most amount of blackout dates

   //sort the Drivers in order from most blackout dates to least blackout dates
   function sortDrivers(){

   }



   /*
   This is the main scheduling function



   */


   function schedule(){

   }


?>
