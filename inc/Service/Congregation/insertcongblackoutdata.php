<?php
    require_once(__DIR__."./CongregationBlackout.class.php");
    $CongregationBlackout = new CongregationBlackout();

    $congBlackouts = $_POST['congBlackoutData'];
    $email = $_POST['email'];

    $insertResult = $CongregationBlackout->insertBlackout($congBlackouts,$email);
    if($insertResult) {
        $insertResult = array(
            "result" => $insertResult
        );
    }else {
        echo "Error!";
    }

    echo json_encode($insertResult);
