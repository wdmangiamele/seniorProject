<?php
    require_once(__DIR__."/../../Business/Congregation/Congregation.class.php");
    $Congregation = new Congregation();

    //Get all congregations
    $allCongregations = $Congregation->getCongregations();

    echo json_encode($allCongregations);