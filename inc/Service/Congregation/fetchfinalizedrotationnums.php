<?php
    require_once(__DIR__."/LegacyHostBlackout.class.php");
    $LegacyHostBlackout  = new LegacyHostBlackout();

    $legacyRotationNums = $LegacyHostBlackout->getDistinctRotationNums();

    //Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($legacyRotationNums);