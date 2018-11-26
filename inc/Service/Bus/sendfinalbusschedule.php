<?php
    require_once(__DIR__."/../../Business/Bus/BusDriver.class.php");
    $BusDriver = new BusDriver();
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>RAIHN Scheduler</title>
    <script type="text/javascript" src="../../../js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
       function getFirstDay(theYear, theMonth) {
         var firstDate = new Date(theYear, theMonth, 1);
         return firstDate.getDay();
       }

       // number of days in the month
       function getMonthLen(theYear, theMonth) {
         var oneDay = 1000 * 60 * 60 * 24;
         var thisMonth = new Date(theYear, theMonth, 1);
         var nextMonth = new Date(theYear, theMonth + 1, 1);
         var len = Math.ceil((nextMonth.getTime() - thisMonth.getTime()) / oneDay);
         return len;
       }

       // create array of English month names
       var theMonths = ["January", "February", "March", "April", "May", "June", "July", "August",
         "September", "October", "November", "December"
       ];

       function getObject(obj) {
         var theObj;
         if (document.all) {
           if (typeof obj == "string") {
             return document.all(obj);
           } else {
             return obj.style;
           }
         }
         if (document.getElementById) {
           if (typeof obj == "string") {
             return document.getElementById(obj);
           } else {
             return obj.style;
           }
         }
         return null;
       }

       function populateTable(month, year) {
         <?php
          $schedule = $BusDriver->getScheduleForMonth(month, year);
         ?>;

         var schedule = <?php echo json_encode($schedule); ?>;
         //month -1 as our array begins at 0 index
         var theMonth = month - 1;
         var theYear = year;
         // initialize date-dependent variables
         var firstDay = getFirstDay(theYear, theMonth);
         var howMany = getMonthLen(theYear, theMonth);
         // fill in month/year in table header


         getObject("tableHeader").innerHTML = theMonths[theMonth] +
           " " + theYear;

         // initialize vars for table creation
         var dayCounter = 1;
         var TBody = getObject("tableBody");
         // clear any existing rows
         while (TBody.rows.length > 0) {
           TBody.deleteRow(0);
         }
         var newR, newC;
         var done = false;
         while (!done) {
           // create new row at end
           newR = TBody.insertRow(TBody.rows.length);
           for (var i = 0; i < 7; i++) {
             // create new cell at end of row
             newC = newR.insertCell(newR.cells.length);
             if (TBody.rows.length == 1 && i < firstDay) {
               // no content for boxes before first day
               newC.innerHTML = "";
               continue;
             }
             if (dayCounter == howMany) {
               // no more rows after this one
               done = true;
             }
             // plug in date (or empty for boxes after last day)
             newC.innerHTML = (dayCounter <= howMany) ?
               makeDayEvents(dayCounter,schedule) : "";

             dayCounter++;
           }

         }
       }

       function makeDayEvents(day, _myScheduleObject) {
         var myStringtoSend = day + "<br/>";
         $.each(_myScheduleObject, function(key, value) {

           //console.log('stuff : ' + key + ", " + value.date + value.role);
           var dateIs = value.date;
           var dayIs = dateIs.substring(8, 10);
           if (dayIs == day) {
             myStringtoSend += value.timeOfDay + " - " + value.driverName + " - " + value.role + " <br/>";
           }
         });
         if (myStringtoSend == "") {
           myStringtoSend = day;
         }
         return myStringtoSend;
       }

    </script>
  </head>

  <style>

  #calendarTable {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
        width: 100%;
    }

    #calendarTable td, #calendarTable th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #calendarTable tr:nth-child(even){background-color: #f2f2f2;}

    #calendarTable tr:hover {background-color: #ddd;}

    #calendarTable th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
    </style>

  <body>
    <TABLE style="font-family: Arial;border-collapse: collapse; width: 100%;" ID="calendarTable">
      <TR>
        <TH style="padding-top: 12px; padding-bottom: 12px; background-color: #0D6AA8; color: white;" ID="tableHeader" COLSPAN=7></TH>
      </TR>
      <TR style="background-color: #ddd;">
        <TH>Sun</TH>
        <TH>Mon</TH>
        <TH>Tue</TH>
        <TH>Wed</TH>
        <TH>Thu</TH>
        <TH>Fri</TH>
        <TH>Sat</TH>
      </TR>
      <tbody id="tableBody"></tbody>
  </body>
  <script type="text/javascript">
    
    populateTable(<?php echo $_GET['month']?>,<?php echo $_GET['year']?>);
    
    var htmlTable = $('body').html();

    $.ajax({
        type: "POST",
        url: "emailbuscalendar.php",
        data: {
            'htmlSchedule' : htmlTable
        },
        success: function (response) {
            window.location.replace("../../../finalBusSchedule.php");
            //service.php response
            console.log(response);
        },
        error: function (){
            alert("error");
        }
    });
  </script>
</html>
