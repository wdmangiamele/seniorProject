$(document).ready(function() {

    var date = "";

    $('#inputBusCalendar').fullCalendar({

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
                    var add_button = '<a class="add_event_label"><div id="container"><div id="content"><div id="contact-form"><input type="button" name="contact" value="Blackout" class="contact demo"/></div></div></div></a>';
                    $(this).append(add_button);
                });
            //}
        }
    });

});



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
