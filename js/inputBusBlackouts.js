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
                    var add_button_am = "<div><button class='blackoutButton AM' id='myButton' onclick='clickme(this)'>AM</button></div>";
                    var add_button_pm = "<div><button class='blackoutButton PM' id='myButton' onclick='clickme(this)'>PM</button></div>";
                    var hidden_button = '<div style="visibility:hidden"><button>Blackout</button></div>';
                    $(this).prepend(add_button_pm);
                    $(this).prepend(add_button_am);
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
            var dateTd = $(this).parent().parent()[0].outerHTML;
            var date = $(dateTd).attr('data-date');
            var timeOfDay = $(this).text();

            $.ajax({
                method: "POST",
                url: "./busbusiness.php",
                data: {
                    'type': 'inputBlackouts',
                    'date': date,
                    'timeOfDay' : timeOfDay
                },
                success: function(data) {
                   
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });

        }
    });

    alert('Blackouts successfully submitted');
    


}
