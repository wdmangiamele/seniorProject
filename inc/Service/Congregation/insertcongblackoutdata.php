<?php
    /* file to insert congregation blackouts to congregation_blackout table
     * */
    require_once(__DIR__."./../../Business/Congregation/CongregationBlackout.class.php");
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
