$(document).ready(function() {

    var date = "";
    var timeOfDay = "";
    var role = "";





    $('#generateButton').on("click", function() {
        var month = $("#months option:selected").text();
        var year = $("#year option:selected").text();



        // $.ajax({
        //     type: "POST",
        //     url: "../createBusSchedule.php",
        //     data: {
        //         'month': '11', 'year': '2018'
        //     },
        //     success: function(data){
        //         console.log(month);
        //         console.log(year);
        //     },
        //     error: function (jqXHR, exception) {
        //            var msg = '';
        //            if (jqXHR.status === 0) {
        //                msg = 'Not connect.\n Verify Network.';
        //            } else if (jqXHR.status == 404) {
        //                msg = 'Requested page not found. [404]';
        //            } else if (jqXHR.status == 500) {
        //                msg = 'Internal Server Error [500].';
        //            } else if (exception === 'parsererror') {
        //                msg = 'Requested JSON parse failed.';
        //            } else if (exception === 'timeout') {
        //                msg = 'Time out error.';
        //            } else if (exception === 'abort') {
        //                msg = 'Ajax request aborted.';
        //            } else {
        //                msg = 'Uncaught Error.\n' + jqXHR.responseText;
        //            }
        //            alert(msg);
        //        }
        //});

    });

    $('#busCalendar').fullCalendar({
        eventSources : [{url: './eventSource.php'}],
        eventOrder: 'color,start', //this doesn't work
        eventClick: function(calEvent, jsEvent, view) {

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
                url: "./busbusiness.php",
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
                  url: "./busbusiness.php",
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
