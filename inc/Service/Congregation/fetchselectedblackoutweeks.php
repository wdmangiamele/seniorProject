<?php
    require_once(__DIR__.'/../../Business/Congregation/CongregationCoordinator.class.php');
    require_once(__DIR__.'/../../Business/Congregation/CongregationBlackout.class.php');

    $CongregationCoordinator = new CongregationCoordinator();
    $CongregationBlackout = new CongregationBlackout();

    $congEmail = $_GET['congEmail'];
    $rotNum = $_GET['rotation_number'];

    $congID = $CongregationCoordinator->getCongIDByEmail($congEmail);

    $blackouts = $CongregationBlackout->getCongBlackoutsByCongIDAndRotNum($congID, $rotNum);

    echo json_encode($blackouts);

