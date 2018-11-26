<?php
    require_once(__DIR__."/../../Business/Congregation/LegacyHostBlackout.class.php");
    $LegacyHostBlackout  = new LegacyHostBlackout();

    //Get all rotations from legacy_host_blackout table
    $legacyRotationNums = $LegacyHostBlackout->getDistinctRotationNums();

    //Return MySQL data as JSON encoded data to the app.js file
    echo json_encode($legacyRotationNums);