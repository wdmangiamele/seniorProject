$(document).ready(function() {
	//Global Variables
	var blackoutWeekDates;

	//jQuery UI tooltip for flagged scheduled congregations
    $('[data-toggle="tooltip"]').tooltip();

    //"Enter blackouts" button for inputblackouts.php
    $("#blackoutSubmit").on("click", function() {
	    if($(".blackoutWeek:checked").length > 0) {
            $(".blackoutWeek:checked").each(function(i) {
                var congBlackoutDates = $("<div>").attr("class","cong-blackouts");

                var rotationBlackout = $("<p>").append($("<strong>").text("Rotation: "));
                rotationBlackout.append($("<span>").attr("class","rotation-blackout").text($("#rot-number").text()));

                var startDateBlackout = $("<p>").append($("<strong>").text("Blacked out week: "));
                if($(this).next($(".blackout-date-text")).length == 0) {
                    startDateBlackout.append($("<span>").attr("class","start-date-blackout").text("No blackout weeks"));
                }else {
                    startDateBlackout.append($("<span>").attr("class","start-date-blackout").text($(this).next($(".blackout-date-text")).text()));
                }

                congBlackoutDates.append(rotationBlackout);
                congBlackoutDates.append(startDateBlackout);

                $("#modalLabel").text("Please Confirm Blackouts");
                $(".modal-body").append(congBlackoutDates);
            });
        }else {
            $("#modalLabel").text("Nothing Selected");
            $("#input-data-save").prop("disabled",true);
        }
    });

    //Button for updating schedules on the adminCongSchedule.php page
	$("body").on("click", "#admin-submit", function() {
		var editedDivs = $("tr").filter(function() {
            var editedColor = $(this).css("background-color");
            return editedColor === "rgb(255, 202, 58)" || editedColor === "#FFCA3A";
		});
        $("#modalLabel").text("Nothing Changed");
        if(editedDivs) {
        	editedDivs.each(function(i) {
        		var optionVals = editedDivs.eq(i).children("td").eq(1).children("select").val().split(",");
        		var oldCong = editedDivs.eq(i).find($(".curr-sch-cong")).text();

        		var editedCong = $("<div>").attr("class","edited-congs");

        		var startDateHeading = $("<p>").append($("<strong>").text("Start Date: "));
                startDateHeading.append($("<span>").attr("class","updated-start-date").text(optionVals[2]));

                var oldCongHeading = $("<p>").append($("<strong>").text("Old Congregation: "));
                oldCongHeading.append($("<span>").attr("class","old-cong-name").text(oldCong));

                var newCongHeading = $("<p>").append($("<strong>").text("New Congregation: "));
                newCongHeading.append($("<span>").attr("class","updated-cong-name").text(optionVals[0]));

                var rotationHeading = $("<p>").append($("<strong>").text("Rotation: "));
                rotationHeading.append($("<span>").attr("class","updated-rotation").text(optionVals[1]));

        		editedCong.append(startDateHeading);
        		editedCong.append(oldCongHeading);
        		editedCong.append(newCongHeading);
        		editedCong.append(rotationHeading);

                $("#modalLabel").text("Please Confirm Changes");
        		$(".modal-body").append(editedCong);
			});
        }
	});

	//Finalize schedule button for adminCongSchedule.php
    $("body").on("click", "#admin-finalize", function() {
        //Getting the rotation number
        var rotNum = $(".tbl-heading").eq(1).attr("id").split("-");
        $("#finalizeLabel").append($("<span>").attr("id","Rotation-"+rotNum[1]).text("Finalize rotation "+rotNum[1]+"?"));

        var startDates = $(".start-date");
        var congNames = $(".congName");

        for(var i = 0; i < congNames.length; i++) {
            var holiday = startDates.eq(i).text().substr(11,8);
            if(holiday === "HOLIDAY!") {
                $(".modal-body").append($("<p>").append($("<strong>").text(startDates.eq(i).text()+": "+congNames.eq(i).find(":selected").text())));
            }else {
                $(".modal-body").append($("<p>").text(startDates.eq(i).text()+": "+congNames.eq(i).find(":selected").text()));
            }
        }
    });

    //On selection of one of the check marks for the input congregation blackouts
    $("body").on("change", ".blackoutWeek", function() {
        if($(this).is(':checked')) {
            if($(this).val() !== ('1970-01-01-'+$("#rot-number").text())) {
                $(".blackoutWeek").eq(13).prop('checked', false);
            }else {
                for(var i = 0; i < 13; i++) {
                    $(".blackoutWeek").eq(i).prop('checked', false);
                }
            }
        }
        $('#calendar').fullCalendar('gotoDate', this.value);
    });

    //The "Ok" button when the admin clicks to update changes made to the schedule
    $("body").on("click", "#conf-ok-btn", function() {
        window.location.replace("adminCongSchedule.php");
    });

    //Ok button once user gets confirmation that email has been sent
    $("body").on("click", "#email-cong-ok-btn", function() {
        window.location.replace("congregationcoordinators.php");
    });

    //Ok button for modal on adminCongSchedule.php page once scheduled has been finalized
    $("body").on("click", "#finalize-ok-btn", function() {
        window.location.replace("finalizedschedules.php");
    });

    //Ok button for input blackouts page (inputblackouts.php)
    $("body").on("click", "#input-ok-btn", function() {
        window.location.replace("inputblackouts.php");
    });

    //Schedule button for enteredblackoutsCongregation.php page
    $("body").on("click", ".schedule-button", function() {
        var lowestRotation = $(".rotation-number").eq(0).text();
        var currentRotationClicked = $(this).attr('id').split('-')[1];
        if(lowestRotation !== currentRotationClicked) {
            $("#conf-sch-yes").hide();
            $(".modal-title").text("Please schedule rotation "+lowestRotation+" first");
        }else {
            var rotNum = $(this).attr("id").split("-");
            $(".modal-title").text("Schedule rotation "+rotNum[1]+"?");
        }
    });

    //Select option for Scheduled Rotations page
    $("body").on("change", "#sch-rot-nums-select", function() {
        $("#rotation-sch-div").empty();
        $("#admin-cong-buttons").empty();

        $(".loader").show();

        var getSelectedRot = postData({rotation_number: $(this).val()},"inc/Service/Congregation/fetchselectedrotation.php"),
            getFullSchedule = postData({rotation_number: $(this).val()},"inc/Service/Congregation/fetchfullschedule.php"),
            eligibleCongregations = postData({rotation_number: $(this).val()},"inc/Service/Congregation/fetchEligibleCongregations.php");
        $.when(getSelectedRot,getFullSchedule,eligibleCongregations).then(function(selectedRot, fullSchedule, eligibleCongs) {
            $(".loader").hide();
            //Get all the start dates for each rotation
            //Helps create "Admin Congregation Schedule" page
            var startDates = Object.keys(eligibleCongs[0]);
            startDates = startDates.sort(function (a, b) {
                return new Date(a).getTime() - new Date(b).getTime()
            });
            var table = $("<table>").addClass("table");
            table.attr("id","final-cong-schedule");

            var congCount = 0;

            var tableHead = $("<thead>");
            tableHead.addClass("rotation-head");
            var tableRow = $("<tr>");
            var tableHeading1 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading1.text("Start Date");
            var tableHeading2 = $("<th>").attr("scope", "col").addClass("tbl-heading").attr("id","Rotation-"+selectedRot[0]["selected"]);
            tableHeading2.text("Rotation #"+selectedRot[0]["selected"]);
            var tableHeading3 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading3.text("Approved Schedule as of:");

            tableRow.append(tableHeading1);
            tableRow.append(tableHeading2);
            tableRow.append(tableHeading3);
            tableHead.append(tableRow);

            table.append(tableHead);

            var tableBody = $("<tbody>");
            for(var h = 0; h < 13; h++) {
                var tableBodyRow = $("<tr>").addClass("scheduled-date");

                var tableData = $("<td>").addClass("start-date");
                if(fullSchedule[0][h]["isFlagged"]) {
                    if(fullSchedule[0][h]["holiday"] == 1){
                        var strongTag = $("<strong>");
                        strongTag.text(fullSchedule[0][h]["startDate"]+" HOLIDAY!");

                        tableData.append($("<img src='img/warningsymbol.svg'/>").addClass("warning-symbol").attr({"data-toggle": "tooltip",
                                                                                    "title": "Currently Scheduled "+fullSchedule[0][h]["reasonForFlag"]}));
                        tableData.append(strongTag);
                    }else {
                        tableData.append($("<img src='img/warningsymbol.svg'/>").addClass("warning-symbol").attr({"data-toggle": "tooltip",
                                                                                "title": "Currently Scheduled "+fullSchedule[0][h]["reasonForFlag"]}));
                        tableData.append("  "+fullSchedule[0][h]["startDate"]);
                    }
                }else {
                    if(fullSchedule[0][h]["holiday"] == 1){
                        var strongTag = $("<strong>");
                        strongTag.text(fullSchedule[0][h]["startDate"]+" HOLIDAY!");
                        tableData.append(strongTag);
                    }else {
                        tableData.text(fullSchedule[0][h]["startDate"]);
                    }
                }

                var tableData2 = $("<td>").addClass("congName").attr("id","cong"+congCount);

                //Create select option for all the congregations eligible, currently scheduled, and not eligible
                var selectOption = $("<select>").addClass("form-control congNames");
                selectOption.append(createHeader("Currently Scheduled"));

                var firstOption = $("<option>").addClass("curr-sch-cong").attr({"selected": "selected","value": fullSchedule[0][h]["congName"]
                    +","+fullSchedule[0][h]["rotationNumber"]}).text(fullSchedule[0][h]["congName"]);
                selectOption.append(firstOption);

                selectOption.append(createSpaceOption());

                selectOption.append(createHeader("Eligible Congregations"));

                for (var k = 0; k < eligibleCongs[0][startDates[h]].length; k++) {
                    if (eligibleCongs[0][startDates[h]][k]["eligible"] !== "No") {
                        var eligibleOption = $("<option>").attr("value", eligibleCongs[0][startDates[h]][k]["title"]+","+fullSchedule[0][h]["rotationNumber"]+
                            ","+fullSchedule[0][h]["startDate"]).text(eligibleCongs[0][startDates[h]][k]["title"]);
                        selectOption.append(eligibleOption);
                    }
                }

                selectOption.append(createSpaceOption());

                var divider = $("<option>").attr("disabled", "disabled");
                divider.text("──────────");
                selectOption.append(divider);

                selectOption.append(createSpaceOption());

                selectOption.append(createHeader("Ineligible Congregations"));

                for (var k = 0; k < eligibleCongs[0][startDates[h]].length; k++) {
                    if (eligibleCongs[0][startDates[h]][k]["eligible"] === "No") {
                        var ineligibleOption = $("<option>").attr("value", eligibleCongs[0][startDates[h]][k]["title"]+","+fullSchedule[0][h]["rotationNumber"]+
                            ","+fullSchedule[0][h]["startDate"]).text(eligibleCongs[0][startDates[h]][k]["title"]);
                        selectOption.append(ineligibleOption);
                    }
                }

                //Append created select option to the row we're on
                tableData2.append(selectOption);

                var tableData3 = $("<td>");
                tableData3.text("");

                tableBodyRow.append(tableData);
                tableBodyRow.append(tableData2);
                tableBodyRow.append(tableData3);

                tableBody.append(tableBodyRow);
                congCount++;
            }

            table.append(tableBody);

            $("#rotation-sch-div").append(table);
            $("#admin-cong-buttons").append($("<button>").attr({"id": "admin-submit", "type": "submit", "data-toggle": "modal", "data-target":"#conf-data-submit"}).addClass("btn btn-primary").text("Submit Changes"));
            $("#admin-cong-buttons").append($("<button>").attr({"id": "admin-finalize", "type": "submit", "data-toggle": "modal", "data-target":"#conf-data-finalize"}).addClass("btn btn-success").text("Finalize Schedule"));
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
    });

    //When an admin changes a congregation, change the background
    $("body").on("change", ".congNames", function() {
    	var currentCong = $(this).find(".curr-sch-cong").val().split(",");
    	var newCong = $(this).val().split(",");
    	if(newCong[0] !== currentCong[0]) {
            $(this).parent().parent().css("background-color","#FFCA3A");
		}else {
            $(this).parent().parent().css("background-color","");
		}
    });

    //Send email icon on the finalized schedule page
    $("body").on("click", "#email-icon", function() {
        $(".modal-title").text("Send Rotation "+$("#sch-finalized-rot").val()+" Schedule?");
        $(".modal-body").append($("<p>").text("The schedule will be sent to all congregations"));
    });

    //Button on "viewenteredblackouts.php" page that refreshes the page
    $("body").on("click", "#refr-table-btn", function() {
        window.location.replace("enteredblackoutsCongregation.php");
    });

    //"Ok" button on the modal for the "enteredblackoutsCongregation.php" page
    $("body").on("click", "#sch-ok-btn", function() {
        window.location.replace("adminCongSchedule.php");
    });

    //Select option for the finalized congregation schedule page
    $("body").on("change", "#sch-finalized-rot", function() {
        $("#finalized-sch-div").empty();
        //Get the finalized schedules
        var getFullSchedule = getData({rotation_number: $(this).val()},"inc/Service/Congregation/fetchfinalizedschedules.php"),
            getSelectedRot = postData({rotation_number: $(this).val()},"inc/Service/Congregation/fetchselectedrotation.php");
        $.when(getFullSchedule, getSelectedRot).then(function(fullSchedule, selectedRot) {
            $(".loader").hide();

            var table = $("<table>").addClass("table");
            table.attr("id","final-cong-schedule");

            var congCount = 0;

            var tableHead = $("<thead>");
            tableHead.addClass("rotation-head");
            var tableRow = $("<tr>");
            var tableHeading1 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading1.text("Start Date");
            var tableHeading2 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading2.text("Rotation #"+selectedRot[0]["selected"]);
            var tableHeading3 = $("<th>").attr("scope", "col").addClass("tbl-heading");
            tableHeading3.text("Approved Schedule as of:");

            tableRow.append(tableHeading1);
            tableRow.append(tableHeading2);
            tableRow.append(tableHeading3);
            tableHead.append(tableRow);

            table.append(tableHead);

            var tableBody = $("<tbody>");
            for(var h = 0; h < 13; h++) {
                var tableBodyRow = $("<tr>");
                tableBodyRow.addClass("scheduled-date");

                var tableData = $("<td>");
                if(fullSchedule[0][h]["holiday"] == 1){
                    var strongTag = $("<strong>");
                    strongTag.text(fullSchedule[0][h]["startDate"]+" HOLIDAY!");
                    tableData.append(strongTag);
                }else {
                    tableData.text(fullSchedule[0][h]["startDate"]);
                }

                var tableData2 = $("<td>").addClass("congName").attr("id","cong"+congCount);

                tableData2.text(fullSchedule[0][h]["congName"]);

                var tableData3 = $("<td>");
                tableData3.text("");

                tableBodyRow.append(tableData);
                tableBodyRow.append(tableData2);
                tableBodyRow.append(tableData3);

                tableBody.append(tableBodyRow);
                congCount++;
            }

            table.append(tableBody);
            $("#finalized-sch-div").append(table);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
    });

    //Ok button for finalized schedule modal
    $("body").on("click", "#send-scb-ok-btn", function() {
        window.location.replace("finalizedschedules.php");
    });

    //Tool tip for flagged congregations on adminCongSchedule.php page
    $("body").on("mouseover", ".warning-symbol", function() {
        console.log($(this).attr("id"));
    });

    //Full calendar congregation blackout inputs
    $('#calendar').fullCalendar({

    });

    //Cancel button for adminCongSchedule.php make updates modal
    $("#conf-data-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
	});

    //Cancel button for adminCongSchedule.php finalize modal
    $("#conf-data-cancel-finalize").on("click", function() {
        $("#finalizeLabel").empty();
        $(".modal-body").empty();
        $("#finalizeLabel").css("color","");
    });

    //Finalize button for adminCongSchedule.php modal
    //Will send schedule to database to be finalized
    $("#conf-finalize").on("click", function() {
        var spanTag = $(".finalized-title").children("span");
        var rotNum = spanTag.eq(0).attr("id").split("-");
        var finalizeResult = postData({rotation_number: rotNum[1]},"inc/Service/Congregation/finalizeschedule.php");
        $.when(finalizeResult).then(function(result) {
            $("#finalizeLabel").text("Success: Schedule Finalized").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"finalize-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#finalizeLabel").text("Fail: Changes Not Made! Contact Admin!").css("color","#D63230");
        });
    });

    //Send data to PHP file to be updated in the database
    $("#conf-data-save").on("click", function() {
        var updatedStartDates = $(".updated-start-date");
        var updatedCongNames = $(".updated-cong-name");
        var updatedRotations = $(".updated-rotation");

        var updatedCongData = [];
        for(var i = 0; i < updatedCongNames.length; i++) {
            var updatedCong = {};
            updatedCong.startDate = updatedStartDates.eq(i).text();
            updatedCong.congName = updatedCongNames.eq(i).text();
            updatedCong.rotation = updatedRotations.eq(i).text();
            updatedCongData.push(updatedCong);
        }
        var updateData = postData({updatedData: updatedCongData},"inc/Service/Congregation/updateCongSch.php")
        $.when(updateData).then(function(updateDataResult) {
            $("#modalLabel").text("Success: Changes Made!").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"conf-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Changes Not Made! Contact Admin!").css("color","#D63230");
        });
	});

	//If the user clicks inside the "confirm password" field, show message
	$("#conf-password").focus(function() {
		$(this).next("p").css("display", "inline");
	});

	//If the user clicks outside the "confirm password" field, hide message
	$("#conf-password").focusout(function() {
		$(this).next("p").css("display", "none");
	});

	//While the user is typing inside the "confirm password" field, check to see if passwords match
	//Enable the submit button if the passwords match and the password has at least 8 characters
	$("#conf-password").keyup(function() {
		var newPass = $("#new-password").val();
		var confirmedPass = $("#conf-password").val();
		if(newPass === confirmedPass) {
			$("#pass-confirm-msg").css("color","#549F93");
			$("#done-word-conf").css("display","inline");

			//Checks to see if the word "done" is present for the "new password" field
			if($("#done-word-new").css("display") == "inline") {
				$("#pass-submit").prop("disabled",false);
			}
		}else {
			$("#pass-confirm-msg").css("color","#CC2936");
			$("#done-word-conf").css("display","none");
			$("#pass-submit").prop("disabled",true);
		}
	});

	//Cancel button for modal on enteredblackoutsCongregation.php
	$("#conf-sch-cancel").on("click", function() {
        $("#modalLabel").css("color","");
        $("#conf-sch-yes").show();
    });

	//"conf" --> "confirm"
    //Confirm button for modal on enteredblackoutsCongregation.php
    //Runs algorithm on particular rotation schedule
	$("#conf-sch-yes").on("click", function() {
        var titleText = $(".modal-title").text().split(" ");
        var rotNum = titleText[2].split("?");
        var scheduleRotations = postData({rotation_number: rotNum[0]}, "inc/Service/Congregation/schedulecongregations.php");
        $.when(scheduleRotations).then(function(scheduledResult) {
            if(scheduledResult) {
                $("#modalLabel").text("Success: Rotation Scheduled!").css("color","#549F93");
                $(".modal-footer").empty();
                var okButton = $("<button>").attr({"type":"button","id":"sch-ok-btn"}).addClass("btn btn-success").text("Ok");
                $(".modal-footer").append(okButton);
            }else {
                $("#modalLabel").text("Fail: Schedule not made! Contact Admin!").css("color","#D63230");
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Schedule not made! Contact Admin!").css("color","#D63230");
        });
    });

	//Email icon for congregationcoordinators.php page
    //Load select option with all congregation coordinator emails
	$("#coord-email-icon").on("click", function() {
        var coordinatorEmails = getData({}, "inc/Service/Congregation/fetchcoordinatoremails.php");
        $.when(coordinatorEmails).then(function(emails) {
            for(var i = 0; i < emails.length; i++) {
                $("#email-input-field").append($("<option>").attr("value",emails[i]["coordinatorEmail"]).text(emails[i]["coordinatorEmail"]));
            }
        });
    });

	//Cancel button for congregationcoordinators.php page
	$("#email-cong-cancel").on("click", function() {
        $("#modalLabel").css("color","");
    });

	//Send email button for congregation coordinator modal
	$("#email-cong-save").on("click", function() {
        var to = $("#email-input-field").val();
        var subject = $("#email-subject-field").val();
        var msg = CKEDITOR.instances.editor1.getData();

        var sentEmail = postData({to: to, subject: subject, msg: msg}, "inc/Service/Congregation/sendindividualemail.php");
        $.when(sentEmail).then(function(email) {
            if(email["sent"]){
                $("#modalLabel").text("Success: Email Sent!").css("color","#549F93");
                $(".modal-footer").empty();
                var okButton = $("<button>").attr({"type":"button","id":"email-cong-ok-btn"}).addClass("btn btn-success").text("Ok");
                $(".modal-footer").append(okButton);
            }else {
                $("#modalLabel").text("Fail: Email Not Sent!").css("color","#D63230");
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Email Not Sent!").css("color","#D63230");
        });
    });

	//Cancel button for inputblackotus.php modal
	$("#input-data-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
    });

	//Enter blackouts button for inputblackouts.php modal
    $("#input-data-save").on("click", function() {
        var congBlackouts = [];
        $(".blackoutWeek:checked").each(function(i) {
            congBlackouts.push($(this).val());
        });
        console.log(congBlackouts);
        var currUserEmail = $("#curr-user").text();
        var insertResult = postData({congBlackoutData: congBlackouts, email: currUserEmail}, "inc/Service/Congregation/insertcongblackoutdata.php");
        $.when(insertResult).then(function(congInsertResult) {
            $("#modalLabel").text("Success: Blackouts Entered!").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"input-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Blackouts Not Entered! Contact Admin!").css("color","#D63230");
        });
    });

	//If the user clicks inside the "new password" field, show message
	$("#new-password").focus(function() {
        $("#eight-chars-msg").css("display", "inline");
        $("#spec-chars-msg").css("display", "inline");
	});

	//If the user clicks outside the "new password" field, hide message
	$("#new-password").focusout(function() {
		$("#eight-chars-msg").css("display", "none");
        $("#spec-chars-msg").css("display", "none");
	});

	//While the user is typing inside the "new password" field, check if password is 8 characters
	$("#new-password").keyup(function() {
		var newPass = $("#new-password").val();
		if($("#new-password").val().length >= 8) {
			$("#eight-chars-msg").css("color","#549F93");
			$("#done-word-new").css("display","inline");
		}else {
			$("#eight-chars-msg").css("color","#CC2936");
			$("#done-word-new").css("display","none");
		}
	});

	//Changes the rotation number
	//Dynamically changes 13 week date ranges on inputblackouts.php
	$("#nxt-btn").click(function() {

		$("#prev-btn").css("display","inline");
		var rotNumber = parseInt($("#rot-number").text());
		$("#rot-number").text(rotNumber+1);

		var dateRanges = createCustomDateRangeArray();
		$(".blackout-checkboxes").empty();
		displayBlackoutRanges(dateRanges);

		//Remove "blackouts already inputted" message
        $("#blackouts-inputted-msg").remove();

		var userEmail = $("#curr-user").text();
		var updatedRotNum = $("#rot-number").text();
        checkSelectedBlackoutsForRotation(userEmail, updatedRotNum);

		//If the user has reached the last rotation number available to schedule, hide the next button
		if(parseInt($("#rot-number").text()) == getMaxRotationNumber()){
			$("#nxt-btn").css("display","none");
		}
	});

	//Changes the rotation number
	//Dynamically changes 13 week date ranges on inputblackouts.php
	$("#prev-btn").click(function() {

        $("#nxt-btn").css("display","inline");
		var rotNumber = parseInt($("#rot-number").text());
		$("#rot-number").text(rotNumber-1);

		var dateRanges = createCustomDateRangeArray();
		$(".blackout-checkboxes").empty();
		displayBlackoutRanges(dateRanges);

        //Remove "blackouts already inputted" message
        $("#blackouts-inputted-msg").remove();

        var userEmail = $("#curr-user").text();
        var updatedRotNum = $("#rot-number").text();
        checkSelectedBlackoutsForRotation(userEmail, updatedRotNum);

        //If the user has reached the first rotation number available to schedule, hide the prev button
		if(parseInt($("#rot-number").text()) == getMinRotationNumber()){
			$("#prev-btn").css("display","none");
		}
	});

	//Cancel button for the send final schedule modal
    $("#send-final-sch-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
    });

    //Send schedule button on finalize schedule modal
    $("#send-final-sch-save").on("click", function() {
        $(".modal-body").empty();
        var modalLoader = $("<div>").addClass("modal-loader");
        $(".modal-body").append(modalLoader);
        var sendEmail = postData({rotation_number: $("#sch-finalized-rot").val()}, "inc/Service/Congregation/sendfinalcongschedule.php");
        $.when(sendEmail).then(function(sendEmailResult) {
            $(".modal-loader").hide();
            if(sendEmailResult["sent"]) {
                $("#modalLabel").text("Success: Schedule Sent!").css("color","#549F93");
                $(".modal-footer").empty();
                var okButton = $("<button>").attr({"type":"button","id":"send-scb-ok-btn"}).addClass("btn btn-success").text("Ok");
                $(".modal-footer").append(okButton);
            }else {
                $("#modalLabel").text("Fail: Schedule not sent! (Sent error) Contact Admin!").css("color","#D63230");
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Schedule not sent! Contact Admin!").css("color","#D63230");
        });
    });

	//password field show/hide listener
	$(".pw-toggle-group a").click(function() {
		var current = $(this).html();
		switch (current){
			case ('<i class="fa fa-eye"></i> Show'):
				$(this).html('<i class="fa fa-eye-slash"></i> Hide').addClass("pw-hide").parent().find("input").attr("type", "text");
				break;
			case ('<i class="fa fa-eye-slash"></i> Hide'):
				$(this).html('<i class="fa fa-eye"></i> Show').removeClass("pw-hide").parent().find("input").attr("type", "password");
				break;
			default:
				break;
		}
	});

	//AJAX CALLS

	//Modular way to get data
	var getData = function(params, url) {
		if(params.length == 0) {
            return $.ajax({
                type: "get",
                dataType: 'json',
                url: url
            });
		}else {
            return $.ajax({
                type: "get",
                data: params,
                dataType: 'json',
                url: url
            });
		}
	}

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

	//Fetch the dates for congregations to input their blackouts on
    //Done on page load
	var blackoutWeekDates = getData({},"inc/Service/Congregation/fetchblackoutweeks.php");
	$.when(blackoutWeekDates).then(function(blackoutWeeks) {
	    //Create global variable blackoutWeekDates to use for later
		blackoutWeekDates = blackoutWeeks;
        displayBlackoutRanges(blackoutWeeks);
	}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
    });

	//Functions executed on load
    adminRotSchedules();
    createCongBlackoutsEnteredTable();
    getFinalizedSchedules();

    var userEmail = $("#curr-user").text();
    var rotNum = $("#rot-number").text();
    checkSelectedBlackoutsForRotation(userEmail, rotNum);

	//FUNCTIONS
    //Helps create admin page where congregation admin can edit scheduled rotations
    function adminRotSchedules() {
        //Setup the admin congregation schedule
        var getRotationNums = getData({},"inc/Service/Congregation/fetchScheduledRotationNums.php");
        $.when(getRotationNums).then(function (rotationNums) {
            if(rotationNums == null) {
                $("#admin-schedule").append($("<h4>").text("No Congregations Scheduled"));
            }else {
                $("#admin-schedule").append($("<p>").text("Select a scheduled rotation to edit"));
                var selectWithAllSchRots = $("<select>").attr("id","sch-rot-nums-select");
                selectWithAllSchRots.append(createHeader("Scheduled Rotations"));
                for(var i = 0; i < rotationNums.length; i++) {
                    var rotationOption = $("<option>").attr("value",rotationNums[i]["rotationNumber"]).text(rotationNums[i]["rotationNumber"]);
                    selectWithAllSchRots.append(rotationOption);
                }
                $("#admin-schedule").append(selectWithAllSchRots);

                var getFullSchedule = postData({rotation_number: rotationNums[0]["rotationNumber"]},"inc/Service/Congregation/fetchfullschedule.php"),
                    eligibleCongregations = postData({rotation_number: rotationNums[0]["rotationNumber"]},"inc/Service/Congregation/fetchEligibleCongregations.php");
                $.when(getFullSchedule,eligibleCongregations).then(function(fullSchedule, eligibleCongs) {
                    $(".loader").hide();

                    //Get all the start dates for each rotation
                    //Helps create "Admin Congregation Schedule" page
                    var startDates = Object.keys(eligibleCongs[0]);
                    startDates = startDates.sort(function (a, b) {
                        return new Date(a).getTime() - new Date(b).getTime()
                    });
                    var table = $("<table>").addClass("table");
                    table.attr("id","final-cong-schedule");

                    var congCount = 0;

                    var tableHead = $("<thead>");
                    tableHead.addClass("rotation-head");
                    var tableRow = $("<tr>");
                    var tableHeading1 = $("<th>").attr("scope", "col").addClass("tbl-heading");
                    tableHeading1.text("Start Date");
                    var tableHeading2 = $("<th>").attr("scope", "col").addClass("tbl-heading").attr("id","Rotation-"+rotationNums[0]["rotationNumber"]);
                    tableHeading2.text("Rotation #"+rotationNums[0]["rotationNumber"]);
                    var tableHeading3 = $("<th>").attr("scope", "col").addClass("tbl-heading");
                    tableHeading3.text("Approved Schedule as of:");

                    tableRow.append(tableHeading1);
                    tableRow.append(tableHeading2);
                    tableRow.append(tableHeading3);
                    tableHead.append(tableRow);

                    table.append(tableHead);

                    var tableBody = $("<tbody>");
                    for(var h = 0; h < 13; h++) {
                        var tableBodyRow = $("<tr>");
                        tableBodyRow.addClass("scheduled-date");

                        var tableData = $("<td>").addClass("start-date");
                        if(fullSchedule[0][h]["isFlagged"]) {
                            if(fullSchedule[0][h]["holiday"] == 1){
                                var strongTag = $("<strong>");
                                strongTag.text(fullSchedule[0][h]["startDate"]+" HOLIDAY!");

                                tableData.append($("<img src='img/warningsymbol.svg'/>").addClass("warning-symbol").attr("id",fullSchedule[0][h]["reasonForFlag"]).attr("title",fullSchedule[0][h]["reasonForFlag"]));
                                tableData.append(strongTag);
                            }else {
                                tableData.append($("<img src='img/warningsymbol.svg'/>").addClass("warning-symbol").attr("id",fullSchedule[0][h]["reasonForFlag"]).attr("title",fullSchedule[0][h]["reasonForFlag"]));
                                tableData.append("  "+fullSchedule[0][h]["startDate"]);
                            }
                        }else {
                            if(fullSchedule[0][h]["holiday"] == 1){
                                var strongTag = $("<strong>");
                                strongTag.text(fullSchedule[0][h]["startDate"]+" HOLIDAY!");
                                tableData.append(strongTag);
                            }else {
                                tableData.text(fullSchedule[0][h]["startDate"]);
                            }
                        }

                        var tableData2 = $("<td>").addClass("congName").attr("id","cong"+congCount);

                        //Create select option for all the congregations eligible, currently scheduled, and not eligible
                        var selectOption = $("<select>").addClass("form-control congNames");
                        selectOption.append(createHeader("Currently Scheduled"));

                        var firstOption = $("<option>").addClass("curr-sch-cong").attr({"selected": "selected","value": fullSchedule[0][h]["congName"]
                                +","+rotationNums[0]["rotationNumber"]}).text(fullSchedule[0][h]["congName"]);
                        selectOption.append(firstOption);

                        selectOption.append(createSpaceOption());

                        selectOption.append(createHeader("Eligible Congregations"));

                        for (var k = 0; k < eligibleCongs[0][startDates[h]].length; k++) {
                            if (eligibleCongs[0][startDates[h]][k]["eligible"] !== "No") {
                                var eligibleOption = $("<option>").attr("value", eligibleCongs[0][startDates[h]][k]["title"]+","+rotationNums[0]["rotationNumber"]+
                                    ","+fullSchedule[0][h]["startDate"]).text(eligibleCongs[0][startDates[h]][k]["title"]);
                                selectOption.append(eligibleOption);
                            }
                        }

                        selectOption.append(createSpaceOption());

                        var divider = $("<option>").attr("disabled", "disabled");
                        divider.text("──────────");
                        selectOption.append(divider);

                        selectOption.append(createSpaceOption());

                        selectOption.append(createHeader("Ineligible Congregations"));

                        for (var k = 0; k < eligibleCongs[0][startDates[h]].length; k++) {
                            if (eligibleCongs[0][startDates[h]][k]["eligible"] === "No") {
                                var ineligibleOption = $("<option>").attr("value", eligibleCongs[0][startDates[h]][k]["title"]+","+rotationNums[0]["rotationNumber"]+
                                    ","+fullSchedule[0][h]["startDate"]).text(eligibleCongs[0][startDates[h]][k]["title"]);
                                selectOption.append(ineligibleOption);
                            }
                        }

                        //Append created select option to the row we're on
                        tableData2.append(selectOption);

                        var tableData3 = $("<td>");
                        tableData3.text("");

                        tableBodyRow.append(tableData);
                        tableBodyRow.append(tableData2);
                        tableBodyRow.append(tableData3);

                        tableBody.append(tableBodyRow);
                        congCount++;
                    }

                    table.append(tableBody);

                    var rotationSchDiv = $("<div>").attr("id","rotation-sch-div");
                    rotationSchDiv.append(table);
                    $("#admin-schedule").append(rotationSchDiv);

                    var adminButtons = $("<div>").attr("id","admin-cong-buttons");
                    adminButtons.append($("<button>").attr({"id": "admin-submit", "type": "submit", "data-toggle": "modal", "data-target":"#conf-data-submit"}).addClass("btn btn-primary").text("Submit Changes"));
                    adminButtons.append($("<button>").attr({"id": "admin-finalize", "type": "submit", "data-toggle": "modal", "data-target":"#conf-data-finalize"}).addClass("btn btn-success").text("Finalize Schedule"));
                    $("#admin-schedule").append(adminButtons);
                }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                });
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
    }//end adminRotSchedules

    //Get all selected blackout weeks for a rotation
    function checkSelectedBlackoutsForRotation(congEmail, rotNum) {
        var selectedBlackouts = getData({congEmail: congEmail, rotation_number: rotNum}, 'inc/Service/Congregation/fetchselectedblackoutweeks.php');
        $.when(selectedBlackouts).then(function(blackouts) {
            if(blackouts) {
                var blackoutsSelectArr = [];
                for(var i = 0; i < blackouts.length; i++) {
                    blackoutsSelectArr.push(blackouts[i]['startDate']);
                }

                var blackoutWeekOptions = $(".blackoutWeek");
                for(var i = 0; i < blackoutWeekOptions.length; i++) {
                    if(jQuery.inArray(blackoutWeekOptions.eq(i).val(), blackoutsSelectArr) !== -1) {
                        blackoutWeekOptions.eq(i).prop('checked', true);
                    }
                }

                $(".blackout-header").append($("<p>").attr("id","blackouts-inputted-msg").text("Blackouts inputted for this rotation"))
            }
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
    }//end getSelectBlackoutsForRotation

    //Creates table for the "Blackouts Entered" page
    function createCongBlackoutsEnteredTable() {
        var parentDiv = $(".table-responsive");

        //Get all the rotations
        var getRotations = getData({},"inc/Service/Congregation/fetchrotations.php");
        $.when(getRotations).then(function(rotations) {
            //Create bootstrap table
            var table = $("<table>").addClass("table").attr("id","congs-entered-blackouts");
            var tableHeads = $("<thead>");
            var tableRowForHeads = $("<tr>");
            tableRowForHeads.append($("<th>").attr("scope","col"));
            tableRowForHeads.append($("<th>").attr("scope","col").text("Rotation Number"));

            //Fetch all the congregations
            var getCongregations = getData({},"inc/Service/Congregation/fetchcongregations.php");
            $.when(getCongregations).then(function(congregations) {

                //Fetch all the blackouts entered by each congregation per rotation
                var getCongBlackouts = getData({},"inc/Service/Congregation/fetchcongblackouts.php");
                $.when(getCongBlackouts).then(function(allCongBlackouts) {
                    $(".loader").hide();
                    for(var i = 0; i < congregations.length; i++) {
                        tableRowForHeads.append($("<th>").attr("scope","col").addClass("cong-headings").text(congregations[i]["congName"]));
                    }
                    tableHeads.append(tableRowForHeads);
                    table.append(tableHeads);

                    var tableBody = $("<tbody>");

                    //forLoopLength used to show maximum amount of rotations shown on the blackouts entered paged for
                    //congregation admins
                    var forLoopLength = 0;
                    if(rotations.length < 10) {
                        forLoopLength = rotations.length;
                    }else {
                        forLoopLength = 10;
                    }
                    for(var i = 0; i < forLoopLength; i++) {
                        var tableRow = $("<tr>").addClass("blackouts-per-rot");

                        //Add schedule button to table
                        var scheduleButton = $("<button>").addClass("btn btn-primary schedule-button").prop("disabled",true).attr({"id":"btn-"+rotations[i]["rotation_number"],"data-toggle": "modal", "data-target":"#conf-sch-submit"}).text("Schedule");
                        tableRow.append(scheduleButton);

                        //Add the rotation number
                        var rotationTableHead = $("<th>").attr("scope","row").addClass("rotation-number").text(rotations[i]["rotation_number"]);
                        tableRow.append(rotationTableHead);

                        //Add green checkmark or red 'X' depending on if congregation entered blackouts for a rotation
                        for(var j = 0; j < allCongBlackouts[rotations[i]["rotation_number"]].length; j++) {
                            if(allCongBlackouts[rotations[i]["rotation_number"]][j]["enteredBlackouts"] === "Yes") {
                                var tableData = $("<td>").append($("<img src='img/greencheckmark.svg'/>").attr({"alt":"Green Checkmark", "id":"check-"+allCongBlackouts[rotations[i]["rotation_number"]][j]["congName"]+"-"+rotations[i]["rotation_number"]}).addClass("green-checkmark"));
                                tableRow.append(tableData);
                            }else {
                                var tableData = $("<td>").append($("<img src='img/RedX100px.svg'/>").attr({"alt":"Red X", "id":"x-"+allCongBlackouts[rotations[i]["rotation_number"]][j]["congName"]+"-"+rotations[i]["rotation_number"]}).addClass("red-x"));
                                tableRow.append(tableData);
                            }
                        }

                        tableBody.append(tableRow);
                    }
                    table.append(tableBody);
                    parentDiv.append(table);

                    //Check to see if 'schedule' button should be enabled
                    //Only enabled if all congregations have entered blackouts for a rotation
                    for (var k = 0; k < rotations.length; k++) {
                        var buttonInRow = $("#btn-"+rotations[k]["rotation_number"]);
                        var numberOfCheckmarks = buttonInRow.siblings("td").children(".green-checkmark").length;
                        if(numberOfCheckmarks === 13) {
                            buttonInRow.prop("disabled",false);
                        }
                     }

                     //Create refresh button with a div holding the button
                    var adminBlackoutsEntered = $("<div>").attr("id","admin-blackouts-entered-buttons");
                    adminBlackoutsEntered.append($("<button>").attr("id","refr-table-btn").addClass("btn btn-primary").text("Refresh Table"));
                    $("#blackouts-per-rotation").append(adminBlackoutsEntered);
                }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                });
            });
        });
    }//end createCongBlackoutsEnteredTable

    //Creates an array of date ranges for a rotation
    //Used for inputblackouts.php
	function createCustomDateRangeArray() {
		var firstIndex = getCurrRotationsFirstWeek();
		var lastIndex = firstIndex + 12;
		var dateRanges = new Array();
		for (var i = firstIndex; i <= lastIndex; i++) {
			dateRanges.push(blackoutWeekDates[i]);
		};
		return dateRanges;
	}//end createCustomDateRangeArray

    //Creates a header for select option menu forms
	function createHeader(text) {
		return $("<option>").attr("disabled", "disabled").text(text);
	}//end createHeader

    //Creates a blank space option for select option menu forms
	function createSpaceOption() {
        return $("<option>").attr("disabled", "disabled").text("");
	}//end createSpaceOption

    //Display all the blackout weeks for each rotation
    //Used on inputblackouts.php for congregation users
	function displayBlackoutRanges(data) {
		for(var i = 0; i <= 12; i++) {
			if(data[i]['holiday'] == 1) {
                if(data[i]['startDate'] == "1970-01-01") {

                }else {
                    var label = $("<label>").addClass("checkbox-inline");

                    var input = $("<input>").attr("type", "checkbox");
                    input.attr("name", "blackoutWeek[]");
                    input.attr("class", "blackoutWeek");
                    input.attr("value", data[i]['startDate']);
                    label.append(input);
                    var blackoutDateText = $("<span>").addClass("blackout-date-text");
                    blackoutDateText.append("<strong>Week " + data[i]['weekNumber'] +
                        "	(" + data[i]['startDate'] + " to "
                        + data[i]['endDate'] + ") HOLIDAY!</strong>");
                    label.append(blackoutDateText);

                    $(".blackout-checkboxes").append(label);
                    $(".blackout-checkboxes").append("<br />");
                }
			}else {
                if(data[i]['startDate'] == "1970-01-01") {

                }else {
                    var label = $("<label>").addClass("checkbox-inline");

                    var input = $("<input>").attr("type", "checkbox");
                    input.attr("name", "blackoutWeek[]");
                    input.attr("class", "blackoutWeek");
                    input.attr("value", data[i]['startDate']);
                    label.append(input);
                    var blackoutDateText = $("<span>").addClass("blackout-date-text");;
                    blackoutDateText.append("Week " + data[i]['weekNumber'] +
                        "	(" + data[i]['startDate'] + " to "
                        + data[i]['endDate'] + ")");
                    label.append(blackoutDateText);

                    $(".blackout-checkboxes").append(label);
                    $(".blackout-checkboxes").append("<br />");
                }
			}
			if(i == 12) {
                var label = $("<label>").addClass("checkbox-inline");

                var input = $("<input>").attr("type","checkbox");
                input.attr("name","blackoutWeek[]");
                input.attr("class","blackoutWeek");
                input.attr("value","1970-01-01-"+$("#rot-number").text());
                label.append(input);
                label.append("No Blackouts (Available for the whole rotation)");

                $(".blackout-checkboxes").append(label);
                $(".blackout-checkboxes").append("<br />");
			}
		}
	}//end displayBlackoutRanges

    //Used to help find what, in blackoutWeekDates array, index is the first week of a specific rotation
	function getCurrRotationsFirstWeek() {
		var rotNumber = parseInt($("#rot-number").text());
		var indexOfFirstWeek;
		for (var i = 0; i < blackoutWeekDates.length; i += 13) {
			if(blackoutWeekDates[i]['rotation_number'] == rotNumber) {
				indexOfFirstWeek = i;
				break;
			}
		};
		return indexOfFirstWeek;
	}//end getCurrRotationsFirstWeek

    //Get all the finalized schedules from legacy_host_blackout table in MySQL
    function getFinalizedSchedules() {
        //Get all the finalized schedules
        var finalizedRotNums = getData({},"inc/Service/Congregation/fetchfinalizedrotationnums.php");
        $.when(finalizedRotNums).then(function(finalizedRots) {
            var selectASchText = $("<p>").text("Select a schedule");
            selectASchText.insertBefore("#final-sch-tools");

            var finalSchTools = $("#final-sch-tools");
            var selectWithAllSchRots = $("<select>").attr("id","sch-finalized-rot");
            selectWithAllSchRots.append(createHeader("Scheduled Rotations"));
            for(var i = 0; i < finalizedRots.length; i++) {
                var rotationOption = $("<option>").attr("value",finalizedRots[i]["rotation_number"]).text(finalizedRots[i]["rotation_number"]);
                selectWithAllSchRots.append(rotationOption);
            }

            //Select option added just before dummy span tag
            //Need a dummy span tag in order to place just before the email SVG icon
            //Email SVG icon is surrounded by PHP tags
            selectWithAllSchRots.insertBefore("#dummy-span");

            var getFullSchedule = getData({rotation_number: finalizedRots[0]["rotation_number"]},"inc/Service/Congregation/fetchfinalizedschedules.php");
            $.when(getFullSchedule).then(function(fullSchedule) {
                $(".loader").hide();

                var table = $("<table>").addClass("table");
                table.attr("id","final-cong-schedule");

                var congCount = 0;

                var tableHead = $("<thead>");
                tableHead.addClass("rotation-head");
                var tableRow = $("<tr>");
                var tableHeading1 = $("<th>").attr("scope", "col").addClass("tbl-heading");
                tableHeading1.text("Start Date");
                var tableHeading2 = $("<th>").attr("scope", "col").addClass("tbl-heading");
                tableHeading2.text("Rotation #"+finalizedRots[0]["rotation_number"]);
                var tableHeading3 = $("<th>").attr("scope", "col").addClass("tbl-heading");
                tableHeading3.text("Approved Schedule as of:");

                tableRow.append(tableHeading1);
                tableRow.append(tableHeading2);
                tableRow.append(tableHeading3);
                tableHead.append(tableRow);

                table.append(tableHead);

                var tableBody = $("<tbody>");
                for(var h = 0; h < 13; h++) {
                    var tableBodyRow = $("<tr>");
                    tableBodyRow.addClass("scheduled-date");

                    var tableData = $("<td>");
                    if(fullSchedule[h]["holiday"] == 1){
                        var strongTag = $("<strong>");
                        strongTag.text(fullSchedule[h]["startDate"]+" HOLIDAY!");
                        tableData.append(strongTag);
                    }else {
                        tableData.text(fullSchedule[h]["startDate"]);
                    }

                    var tableData2 = $("<td>").addClass("congName").attr("id","cong"+congCount);

                    tableData2.text(fullSchedule[h]["congName"]);

                    var tableData3 = $("<td>");
                    tableData3.text("");

                    tableBodyRow.append(tableData);
                    tableBodyRow.append(tableData2);
                    tableBodyRow.append(tableData3);

                    tableBody.append(tableBodyRow);
                    congCount++;
                }

                table.append(tableBody);

                var finalizedSchDiv = $("<div>").attr("id","finalized-sch-div");
                finalizedSchDiv.append(table);
                $("#finalized-schedule").append(finalizedSchDiv);
            }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(textStatus);
            });
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
    }//end getFinalizedSchedules

	/* Returns the maximum rotation number from date range array
	 * @return blackoutWeekDates[lastIndex]['rotation_number'] - last rotation number
	 */
	function getMaxRotationNumber() {
		var lastIndex = blackoutWeekDates.length - 1;
		return blackoutWeekDates[lastIndex]['rotation_number'];
	}//end getMinRotationNumber

	/* Returns the minimum rotation number from date range array
	 * @return blackoutWeekDates[0]['rotation_number'] - first rotation number
	 */
	function getMinRotationNumber() {
		return blackoutWeekDates[0]['rotation_number'];
	}//end getMinRotationNumber
});
