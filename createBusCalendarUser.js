$(document).ready(function() {

    $('#busCalendar').fullCalendar({
        eventSources : [{url: '../eventSource.php'}],
        eventOrder: 'color,start' //this doesn't work
      });

}
