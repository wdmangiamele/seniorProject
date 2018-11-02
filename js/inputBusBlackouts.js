$(document).ready(function() {

    var date = "";


    $('#inputBusCalendar').fullCalendar({

        header: {
         center: 'title',
         right : 'prev,today,next'
         },

        dayClick: function(date, jsEvent, view) {
          date = date.format();

          $('#content').attr('date',date);

          // change the day's background color just for fun
          //$(this).css('background-color', 'red');
        },
        eventAfterAllRender: function(view){
            //if(view.name == 'month'){
                $('.fc-day').each(function(){
                    $(this).css('position','relative');
                    var add_button = "<div><button id='myButton' onclick='clickme()'>Click me</button></div>";
                    $(this).prepend(add_button);
                    $(this).prepend(add_button);
                    $(this).prepend(add_button);
                });
            //}
        }
    });

});

function clickme(){
    console.log('hey');
    document.getElementById("myButton").style.background='#000f00';

}



// function getBlackouts(){
//     //console.log('hi');
//     //console.log( $('#inputBusCalendar').fullCalendar('clientEvents'));
//
//     var evs = $('#calendar').fullCalendar('getView').getShownEvents();
//     console.log(evs);
//
//   // it would go through all events and check for evetns that are red.
//   // take their date,time,driverID(by session variable)
//   // next push these to database
//
//
//
// }
