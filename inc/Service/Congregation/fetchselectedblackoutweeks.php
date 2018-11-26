<?php
    require_once(__DIR__.'/../../Business/Congregation/CongregationCoordinator.class.php');
    require_once(__DIR__.'/../../Business/Congregation/CongregationBlackout.class.php');

    $CongregationCoordinator = new CongregationCoordinator();
    $CongregationBlackout = new CongregationBlackout();

    $congEmail = $_GET['congEmail'];
    $rotNum = $_GET['rotation_number'];

    //Get user's congregation ID based on their email
    $congID = $CongregationCoordinator->getCongIDByEmail($congEmail);

    //Get their blackouts that they entered based on their ID and the rotation
    $blackouts = $CongregationBlackout->getCongBlackoutsByCongIDAndRotNum($congID, $rotNum);

    echo json_encode($blackouts);

