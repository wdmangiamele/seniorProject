<?php
    require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
    $RotationScheduleStatus = new RotationScheduleStatus();

    $rotations = $RotationScheduleStatus->getNonScheduledFinalizedRotations();

    echo json_encode($rotations);