$(document).ready(function() {
	//Global Variables
	var blackoutWeekDates;

    $('[data-toggle="tooltip"]').tooltip();

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

    $("body").on("click", "#admin-finalize", function() {
        //Getting the rotation number
        var rotNum = $(".tbl-heading").eq(1).attr("id").split("-");
        $("#finalizeLabel").append($("<span>").attr("id","Rotation-"+rotNum[1]).text("Finalize rotation "+rotNum[1]+"?"));

        var startDates = $(".start-date");
        var congNames = $(".congName");

        for(var i = 0; i < congNames.length; i++) {
            var holiday = startDates.eq(i).text().substr(11,8);
            if(holiday) {
                $(".modal-body").append($("<p>").append($("<strong>").text(startDates.eq(i).text()+": "+congNames.eq(i).find(":selected").text())));
            }else {
                $(".modal-body").append($("<p>").text(startDates.eq(i).text()+": "+congNames.eq(i).find(":selected").text()));
            }
        }
    });

    //On selection of one of the check marks for the host congregation blackouts
    $("body").on("change", ".blackoutWeek", function() {
        $('#calendar').fullCalendar('gotoDate', this.value);
    });

    //The "Ok" button when the admin clicks to update changes made to the schedule
    $("body").on("click", "#conf-ok-btn", function() {
        window.location.replace("adminCongSchedule.php");
    });

    $("body").on("click", "#finalize-ok-btn", function() {
        window.location.replace("adminCongSchedule.php");
    });

    $("body").on("click", "#input-ok-btn", function() {
        window.location.replace("inputblackouts.php");
    });

    $("body").on("click", ".schedule-button", function() {
        var rotNum = $(this).attr("id").split("-");
        $(".modal-title").text("Schedule rotation "+rotNum[1]+"?");
    });

    //Select option for Scheduled Rotations page
    $("body").on("change", "#sch-rot-nums-select", function() {
        $("#rotation-sch-div").empty();
        $("#admin-cong-buttons").empty();

        $(".loader").show();

        var getSelectedRot = postData({rotation_number: $(this).val()},"inc/Controller/fetchselectedrotation.php"),
            getFullSchedule = postData({rotation_number: $(this).val()},"inc/Controller/fetchfullschedule.php"),
            eligibleCongregations = postData({rotation_number: $(this).val()},"inc/Controller/fetchEligibleCongregations.php");
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

    //Button on "viewenteredblackouts.php" page that refreshes the page
    $("body").on("click", "#refr-table-btn", function() {
        window.location.replace("viewenteredblackouts.php");
    });

    //"Ok" button on the modal for the "viewenteredblackouts.php" page
    $("body").on("click", "#sch-ok-btn", function() {
        window.location.replace("viewenteredblackouts.php");
    });

    //Select option for the finalized congregation schedule page
    $("body").on("change", "#sch-finalized-rot", function() {
        $("#finalized-sch-div").empty();
        //Get the finalized schedules
        var getFullSchedule = getData({rotation_number: $(this).val()},"inc/Controller/fetchfinalizedschedules.php"),
            getSelectedRot = postData({rotation_number: $(this).val()},"inc/Controller/fetchselectedrotation.php");
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

    $("body").on("mouseover", ".warning-symbol", function() {
        console.log($(this).attr("id"));
    });

    //Full calendar congregation blackout inputs
    $('#calendar').fullCalendar({

    });

    $("#conf-data-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
	});

    $("#conf-data-cancel-finalize").on("click", function() {
        $(".modal-body").empty();
        $("#finalizeLabel").css("color","");
    });

    $("#conf-finalize").on("click", function() {
        var spanTag = $(".finalized-title").children("span");
        var rotNum = spanTag.eq(0).attr("id").split("-");
        var finalizeResult = postData({rotation_number: rotNum[1]},"inc/Controller/finalizeschedule.php");
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
        var updateData = postData({updatedData: updatedCongData},"inc/Controller/updateCongSch.php")
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

	$("#conf-sch-cancel").on("click", function() {
        $("#modalLabel").css("color","");
    });

	$("#conf-sch-yes").on("click", function() {
        var titleText = $(".modal-title").text().split(" ");
        var rotNum = titleText[2].split("?");
        var scheduleRotations = postData({rotation_number: rotNum[0]}, "inc/Controller/schedulecongregations.php");
        $.when(scheduleRotations).then(function(scheduledResult) {
            $("#modalLabel").text("Success: Rotation Scheduled!").css("color","#549F93");
            $(".modal-footer").empty();
            var okButton = $("<button>").attr({"type":"button","id":"sch-ok-btn"}).addClass("btn btn-success").text("Ok");
            $(".modal-footer").append(okButton);
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            $("#modalLabel").text("Fail: Schedule not made! Contact Admin!").css("color","#D63230");
        });
    });
	
	$("#input-data-cancel").on("click", function() {
        $(".modal-body").empty();
        $("#modalLabel").css("color","");
    });

    $("#input-data-save").on("click", function() {
        var congBlackouts = [];
        $(".blackoutWeek:checked").each(function(i) {
            congBlackouts.push($(this).val());
        });
        var currUserEmail = $("#curr-user").text();
        var insertResult = postData({congBlackoutData: congBlackouts, email: currUserEmail}, "inc/Controller/insertcongblackoutdata.php");
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

	//Changes the rotation number on modal
	//Dynamically changes 13 week date ranges on modal
	$("#nxt-btn").click(function() {
		$("#prev-btn").css("display","inline");
		var rotNumber = parseInt($("#rot-number").text());
		$("#rot-number").text(rotNumber+1);

		var dateRanges = createCustomDateRangeArray();
		$(".blackout-checkboxes").empty();
		displayBlackoutRanges(dateRanges);

		if(parseInt($("#rot-number").text()) == getMaxRotationNumber()){
			$("#nxt-btn").css("display","none");
		}
	});

	//Changes the rotation number on modal
	//Dynamically changes 13 week date ranges on modal
	$("#prev-btn").click(function() {
		$("#nxt-btn").css("display","inline");
		var rotNumber = parseInt($("#rot-number").text());
		$("#rot-number").text(rotNumber-1);

		var dateRanges = createCustomDateRangeArray();
		$(".blackout-checkboxes").empty();
		displayBlackoutRanges(dateRanges);

		if(parseInt($("#rot-number").text()) == getMinRotationNumber()){
			$("#prev-btn").css("display","none");
		}
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
	var blackoutWeekDates = getData({},"inc/Controller/fetchblackoutweeks.php");
	$.when(blackoutWeekDates).then(function(blackoutWeeks) {
		blackoutWeekDates = blackoutWeeks;
        displayBlackoutRanges(blackoutWeeks);
	}).fail(function (XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
    });

    adminRotSchedules();
    createCongBlackoutsEnteredTable();
    getFinalizedSchedules();

	//FUNCTIONS
    function adminRotSchedules() {
        //Setup the admin congregation schedule
        var getRotationNums = getData({},"inc/Controller/fetchScheduledRotationNums.php");
        $.when(getRotationNums).then(function (rotationNums) {
            $("#admin-schedule").append($("<p>").text("Select a scheduled rotation to edit"));
            var selectWithAllSchRots = $("<select>").attr("id","sch-rot-nums-select");
            selectWithAllSchRots.append(createHeader("Scheduled Rotations"));
            for(var i = 0; i < rotationNums.length; i++) {
                var rotationOption = $("<option>").attr("value",rotationNums[i]["rotationNumber"]).text(rotationNums[i]["rotationNumber"]);
                selectWithAllSchRots.append(rotationOption);
            }
            $("#admin-schedule").append(selectWithAllSchRots);

            var getFullSchedule = postData({rotation_number: rotationNums[0]["rotationNumber"]},"inc/Controller/fetchfullschedule.php"),
                eligibleCongregations = postData({rotation_number: rotationNums[0]["rotationNumber"]},"inc/Controller/fetchEligibleCongregations.php");
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

                            tableData.append($("<img src='img/warningsymbol.svg'/>").addClass("warning-symbol").attr("id",fullSchedule[0][h]["reasonForFlag"]));
                            tableData.append(strongTag);
                        }else {
                            tableData.append($("<img src='img/warningsymbol.svg'/>").addClass("warning-symbol").attr("id",fullSchedule[0][h]["reasonForFlag"]));
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
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(textStatus);
        });
    }

    //Creates table for the "Blackouts Entered" page
    function createCongBlackoutsEnteredTable() {
        var parentDiv = $(".table-responsive");

        //Get all the rotations
        var getRotations = getData({},"inc/Controller/fetchrotations.php");
        $.when(getRotations).then(function(rotations) {
            var table = $("<table>").addClass("table").attr("id","congs-entered-blackouts");
            var tableHeads = $("<thead>");
            var tableRowForHeads = $("<tr>");
            tableRowForHeads.append($("<th>").attr("scope","col"));
            tableRowForHeads.append($("<th>").attr("scope","col").text("Rotation Number"));

            var getCongregations = getData({},"inc/Controller/fetchcongregations.php");
            $.when(getCongregations).then(function(congregations) {

                var getCongBlackouts = getData({},"inc/Controller/fetchcongblackouts.php");
                $.when(getCongBlackouts).then(function(allCongBlackouts) {
                    $(".loader").hide();
                    for(var i = 0; i < congregations.length; i++) {
                        tableRowForHeads.append($("<th>").attr("scope","col").addClass("cong-headings").text(congregations[i]["congName"]));
                    }
                    tableHeads.append(tableRowForHeads);
                    table.append(tableHeads);

                    var tableBody = $("<tbody>");
                    for(var i = 0; i < rotations.length; i++) {
                        var tableRow = $("<tr>").addClass("blackouts-per-rot");

                        var scheduleButton = $("<button>").addClass("btn btn-primary schedule-button").prop("disabled",true).attr({"id":"btn-"+rotations[i]["rotation_number"],"data-toggle": "modal", "data-target":"#conf-sch-submit"}).text("Schedule");
                        tableRow.append(scheduleButton);

                        var rotationTableHead = $("<th>").attr("scope","row").addClass("rotation-number").text(rotations[i]["rotation_number"]);
                        tableRow.append(rotationTableHead);

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

                    for (var k = 0; k < rotations.length; k++) {
                        var buttonInRow = $("#btn-"+rotations[k]["rotation_number"]);
                        var numberOfCheckmarks = buttonInRow.siblings("td").children(".green-checkmark").length;
                        if(numberOfCheckmarks === 13) {
                            buttonInRow.prop("disabled",false);
                        }
                     }

                    var adminBlackoutsEntered = $("<div>").attr("id","admin-blackouts-entered-buttons");
                    adminBlackoutsEntered.append($("<button>").attr("id","refr-table-btn").addClass("btn btn-primary").text("Refresh Table"));
                    $("#blackouts-per-rotation").append(adminBlackoutsEntered);
                }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(textStatus);
                });
            });
        });
    }//end createCongBlackoutsEnteredTable

	function createCustomDateRangeArray() {
		var firstIndex = getCurrRotationsFirstWeek();
		var lastIndex = firstIndex + 12;
		var dateRanges = new Array();
		for (var i = firstIndex; i <= lastIndex; i++) {
			dateRanges.push(blackoutWeekDates[i]);
		};
		return dateRanges;
	}//end createCustomDateRangeArray

	function createHeader(text) {
		return $("<option>").attr("disabled", "disabled").text(text);
	}//end createHeader

	function createSpaceOption() {
        return $("<option>").attr("disabled", "disabled").text("");
	}//end createSpaceOption

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

    function getFinalizedSchedules() {
        //Get all the finalized schedules
        var finalizedRotNums = getData({},"inc/Controller/fetchfinalizedrotationnums.php");
        $.when(finalizedRotNums).then(function(finalizedRots) {
            $("#finalized-schedule").append($("<p>").text("Select a schedule"));
            var selectWithAllSchRots = $("<select>").attr("id","sch-finalized-rot");
            selectWithAllSchRots.append(createHeader("Scheduled Rotations"));
            for(var i = 0; i < finalizedRots.length; i++) {
                var rotationOption = $("<option>").attr("value",finalizedRots[i]["rotation_number"]).text(finalizedRots[i]["rotation_number"]);
                selectWithAllSchRots.append(rotationOption);
            }
            $("#finalized-schedule").append(selectWithAllSchRots);

            var getFullSchedule = getData({rotation_number: finalizedRots[0]["rotation_number"]},"inc/Controller/fetchfinalizedschedules.php");
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
