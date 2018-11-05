<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>EmailTemplate</title>
    <style type="text/css">
    td,th{
      text-align: center
    }
    </style>
    <script type="text/javascript">
      function getFirstDay(theYear, theMonth) {
        var firstDate = new Date(theYear, theMonth, 1)
        return firstDate.getDay()
      }
      // number of days in the month
      function getMonthLen(theYear, theMonth) {
        var oneDay = 1000 * 60 * 60 * 24
        var thisMonth = new Date(theYear, theMonth, 1)
        var nextMonth = new Date(theYear, theMonth + 1, 1)
        var len = Math.ceil((nextMonth.getTime() -
          thisMonth.getTime()) / oneDay)
        return len
      }
      // create array of English month names
      var theMonths = ["January", "February", "March", "April", "May", "June", "July", "August",
        "September", "October", "November", "December"
      ]

      function getObject(obj) {
        var theObj
        if (document.all) {
          if (typeof obj == "string") {
            return document.all(obj)
          } else {
            return obj.style
          }
        }
        if (document.getElementById) {
          if (typeof obj == "string") {
            return document.getElementById(obj)
          } else {
            return obj.style
          }
        }
        return null
      }



      function populateTable(month,year) {

        //Db Call here thats what myScheduleObject would be





        //get schedul for that Month
        var myScheduleObject = [
          {driverName:"John May",date:"2018-10-01",timeOfDay:"AM",role:"Primary"},
          {driverName:"Man Car",date:"2018-10-02",timeOfDay:"PM",role:"Backup"},
          {driverName:"Dan Car",date:"2018-10-03",timeOfDay:"AM",role:"Primary"},
          {driverName:"Boss Car",date:"2018-10-01",timeOfDay:"PM",role:"Backup"}
        ];


        var theMonth = month
        var theYear = year
        // initialize date-dependent variables
        var firstDay = getFirstDay(theYear, theMonth)
        var howMany = getMonthLen(theYear, theMonth)
        // fill in month/year in table header


        getObject("tableHeader").innerHTML = theMonths[theMonth] +
          " " + theYear

        // initialize vars for table creation
        var dayCounter = 1
        var TBody = getObject("tableBody")
        // clear any existing rows
        while (TBody.rows.length > 0) {
          TBody.deleteRow(0)
        }
        var newR, newC
        var done = false
        while (!done) {
          // create new row at end
          newR = TBody.insertRow(TBody.rows.length)
          for (var i = 0; i < 7; i++) {
            // create new cell at end of row
            newC = newR.insertCell(newR.cells.length)
            if (TBody.rows.length == 1 && i < firstDay) {
              // no content for boxes before first day
              newC.innerHTML = ""
              continue
            }
            if (dayCounter == howMany) {
              // no more rows after this one
              done = true
            }
            // plug in date (or empty for boxes after last day)



            newC.innerHTML = (dayCounter <= howMany) ?
              makeDayEvents(dayCounter,myScheduleObject) : ""

              dayCounter++
          }

        }
      }

      function makeDayEvents(day,_myScheduleObject){
        var myStringtoSend="";
        for (var i = 0; i < _myScheduleObject.length; i++) {
          var dateIs = _myScheduleObject[i].date
          var dayIs = dateIs.substring(8, 10);
          if(dayIs == day){
            myStringtoSend += _myScheduleObject[i].timeOfDay +" - " +  _myScheduleObject[i].driverName + " - " + _myScheduleObject[i].role +" <br/>"
            }
        }
        if(myStringtoSend==""){
          myStringtoSend = day;
        }
        return myStringtoSend;
      }

    </script>
  </head>
  <body>
      <?php
        require_once("./inc/Controller/BusDriver.class.php");
          $BusDriver = new BusDriver();
          $schedule = $BusDriver->getScheduleForMonth(10,2018);


          foreach ($schedule as $key => $value) {
              $driverName = $value['driverName'];
              $date =$value['date'];
              $timeOf = $value['timeOfDay'];
              $role = $value['role'];

              

          } //for each





          echo "<pre>";
          print_r($schedule);
          echo "<pre>";
       ?>


    <TABLE ID="calendarTable" BORDER=1 ALIGN="center">
      <TR>
        <TH ID="tableHeader" COLSPAN=7></TH>
      </TR>
      <TR>
        <TH>Sun</TH>
        <TH>Mon</TH>
        <TH>Tue</TH>
        <TH>Wed</TH>
        <TH>Thu</TH>
        <TH>Fri</TH>
        <TH>Sat</TH>
      </TR>
      <TBODY ID="tableBody"></TBODY>
      <TR>
    <script type="text/javascript">
      populateTable(10,2018)
    </script>

  </body>
</html>
