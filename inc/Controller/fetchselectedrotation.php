<?php
    $selectedRotNum = $_POST['rotation_number'];

    $selectedRotNumInArray = array(
        "selected" => $selectedRotNum
    );

    echo json_encode($selectedRotNumInArray);