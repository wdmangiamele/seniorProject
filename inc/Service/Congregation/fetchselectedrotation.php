<?php
    $selectedRotNum = $_POST['rotation_number'];

    //Get the selected rotation number from the select option menu
    $selectedRotNumInArray = array(
        "selected" => $selectedRotNum
    );

    echo json_encode($selectedRotNumInArray);