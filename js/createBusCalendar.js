$(document).ready(function() {

    var date = "";
    var timeOfDay = "";
    var role = "";

    //Modular way to post data
    var postData = function(params, url) {
        if(params.length == 0) {
            return $.ajax({
                type: "post",
                dataType: 'json',
                url: url
            });
        }else {
            return $.ajax({
                type: "post",
                data: params,
                dataType: 'json',
                url: url
            });
        }
    }

    //"Ok" button for the send final bus schedule page
    $("body").on("click", "#send-bus-sch-ok-btn", function () {
        window.location.replace("finalBusSchedule.php");
    });

    //Send email icon on the finalized schedule page
    $("#email-bus-icon").on("click", function() {
        var theMonths = ["January", "February", "March", "April", "May", "June", "July", "August",
          "September", "October", "November", "December"
        ];
        var monthYear = $('div.fc-left').children()[0].outerText;
        var split = monthYear.split(" ");
        var month = split[0];
        var monthNum = "";

        for (i=0; i<theMonths.length; i++){
            if (theMonths[i] == month){
                monthNum = i + 1;
            }
        }


        var year = split[1];
        $('#modalBusLabel').text("Send Schedule for " + month + " " + year);
    });

    //Cancel button for the send final schedule modal
    $("#send-bus-final-sch-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
    });

    //Send schedule button on finalize schedule modal
    $("#send-bus-final-sch-save").on("click", function() {
        $(".modal-body").empty();
        var modalLoader = $("<div>").addClass("modal-loader");
        $(".modal-body").append(modalLoader);
        window.location.replace("inc/Service/Bus/sendfinalbusschedule.php");


        // var sendEmail = postData({htmlSchedule: htmlTable}, "inc/Controller/sendfinalbusschedule.php");
        // $.when(sendEmail).then(function(sendEmailResult) {
        //     $(".modal-loader").hide();
        //     if(sendEmailResult["sent"]) {
        //         $("#modalLabel").text("Success: Schedule Sent!").css("color","#549F93");
        //         $(".modal-footer").empty();
        //         var okButton = $("<button>").attr({"type":"button","id":"send-bus-sch-ok-btn"}).addClass("btn btn-success").text("Ok");
        //         $(".modal-footer").append(okButton);
        //     }else {
        //         $("#modalLabel").text("Fail: Schedule not sent! (Sent error) Contact Admin!").css("color","#D63230");
        //     }
        // }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        //     $("#modalLabel").text("Fail: Schedule not sent! Contact Admin!").css("color","#D63230");
        // });
    });


    $('#generateButton').on("click", function() {
        var month = $("#months option:selected").text();
        var year = $("#year option:selected").text();
    });



    $('#busCalendarAdmin').fullCalendar({
        eventSources : [{url: 'inc/Service/Bus/eventSource.php'}],
        eventOrder: 'color,start', //this doesn't work
        eventClick: function(calEvent, jsEvent, view) {
            console.log(calEvent);


            date = calEvent.start._i.substring(0,10);
            timeOfDay = calEvent.start._i.substring(11,16);
            var color = calEvent.color;


            //primary driver
            if(color == '#0000ff'){
                role = 'Primary';
            }
            else if (color == '#008000'){
                role = 'Backup';
            }

            if (timeOfDay == '09:00'){
                timeOfDay = 'AM';
            }
            else{
                timeOfDay = 'PM';
            }

            $.ajax({
                method: "POST",
                url: "inc/Service/Bus/busbusiness.php",
                data: {
                    'type': 'manualEdit',
                    'date': date,
                    'timeOfDay' : timeOfDay
                },
                success: function(data) {
                    createSelectMenu(data);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(textStatus);
                }
            });

            var result = new BstrapModal().Show();



        }

      });

      $('#busCalendarUser').fullCalendar({
          eventSources : [{url: 'inc/Service/Bus/eventSource.php'}],
          eventOrder: 'color,start' //this doesn't work
      });

      Object.objsize = function(Myobj) {
          var osize = 0, key;
          for (key in Myobj) {
              if (Myobj.hasOwnProperty(key)) osize++;
          }
          return osize;
      };



      function createSelectMenu(param){
          var obj = JSON.parse(param);

          var size = (Object.objsize(obj));

          for (i=0; i<size; i++){
              var o = new Option(obj[i][0].name, obj[i][0].name);
              /// jquerify the DOM object 'o' so we can use the html method
              $(o).html(obj[i][0].name);
              $("#selectList").append(o);
          }

      }

      var BstrapModal = function (title, body, buttons) {
          var title = title || "Change Driver", body = body ||
          "<select id='selectList'></select>",
          buttons = buttons || [{ Value: "SUBMIT", Css: "1btn-primary", Callback: function (event) { BstrapModal.Submit(); } }, { Value: "CLOSE", Css: "btn-primary", Callback: function (event) { BstrapModal.Close(); } }];
          var GetModalStructure = function () {
              var that = this;
              that.Id = BstrapModal.Id = Math.random();
              var buttonshtml = "";
              for (var i = 0; i < buttons.length; i++) {
                  buttonshtml += "<button id="+Id+" type='button' class='btn " +
                  (buttons[i].Css||"") + "' name='btn" + that.Id +
                  "'>" + (buttons[i].Value||"SUBMIT") +
                  "</button>";
              }
              return "<div class='modal fade' name='dynamiccustommodal' id='" + that.Id + "' tabindex='-1' role='dialog' data-backdrop='static' data-keyboard='false' aria-labelledby='" + that.Id + "Label'><div class='modal-dialog'> <div class='modal-content'><div class='modal-header'><h4 class='modal-title'>" + title + "</h4></div><div class='modal-body'> <div class='row'><div class='col-xs-12 col-md-12 col-sm-12 col-lg-12'>" + body + "</div></div></div><div class='modal-footer bg-default'> <div class='col-xs-12 col-sm-12 col-lg-12'>" + buttonshtml + "</div></div></div></div></div>";
      }();
          BstrapModal.Delete = function () {
              var modals = document.getElementsByName("dynamiccustommodal");
              if (modals.length > 0) document.body.removeChild(modals[0]);
          };
          BstrapModal.Submit = function () {

              var selectedDriver = $("#selectList option:selected").text();

              $.ajax({
                  method: "POST",
                  url: "inc/Service/Bus/busbusiness.php",
                  data: {
                      'type': 'sendToDB',
                      'date': date,
                      'timeOfDay' : timeOfDay,
                      'driver': selectedDriver,
                      'role': role
                  },
                  success: function(data) {
                      window.location.replace("finalBusSchedule.php");
                  },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                      //alert(textStatus);
                  }
              });
              BstrapModal.Close();
          };
          BstrapModal.Close = function () {
              $(document.getElementById(BstrapModal.Id)).modal('hide');
              BstrapModal.Delete();
          };
          this.Show = function () {
              BstrapModal.Delete();
              document.body.appendChild($(GetModalStructure)[0]);
              var btns = document.querySelectorAll("button[name='btn" + BstrapModal.Id + "']");
              for (var i = 0; i < btns.length; i++) {
                  btns[i].addEventListener("click", buttons[i].Callback || BstrapModal.Close);
              }
              $(document.getElementById(BstrapModal.Id)).modal('show');
          };
      };


});
