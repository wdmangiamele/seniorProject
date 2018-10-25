<?php
    require_once(__DIR__."/DateRange.class.php");
    $DateRange = new DateRange();

    $dateRanges = $DateRange->getDistinctRotationNums();

    echo json_encode($dateRanges);