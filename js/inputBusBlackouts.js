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
                    var add_button = "<div><button class='blackoutButton' id='myButton' onclick='clickme(this)'>Click me</button></div>";
                    var hidden_button = '<div style="visibility:hidden"><button>Blackout</button></div>';
                    $(this).prepend(add_button);
                    $(this).prepend(add_button);
                    $(this).prepend(hidden_button);
                });
            //}
        }
    });

});

function clickme(button){
    //change the button's color to red 
    button.style.background='#ff0000';

    //console.log(button.style);

}



function getBlackouts(){

    // it would go through all events and check for evetns that are red.
    //console.log($('.blackoutButton').style.background('#ff0000').length);
    //console.log($('.blackoutButton').css('background-color')=="rgb(255,0,0)".length);

    //go through each button
    $('.blackoutButton').each(function(){
        //check if the button background color is red
        if(this.style.backgroundColor == "rgb(255, 0, 0)"){
            //since this button is blackout out, send the information to the DB


        }
    });
    




    // take their date,time,driverID(by session variable)
    // next push these to database

  



}
