<?php
    require_once(__DIR__."/CongregationCoordinator.class.php");
    $CongregationCoordinator = new CongregationCoordinator();

    //Get all the congregation coordinator emails
    $emails = $CongregationCoordinator->getCoordinatorEmailAll();

    echo json_encode($emails);