<?php
    require_once(__DIR__."/Congregation.class.php");
    $Congregation = new Congregation();

    $allCongregations = $Congregation->getCongregations();

    echo json_encode($allCongregations);