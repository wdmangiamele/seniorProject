<?php
    require_once(__DIR__."/../../Business/Congregation/RotationScheduleStatus.class.php");
    $RotationScheduleStatus = new RotationScheduleStatus();

    //Get all rotations that have not been "Scheduled" or "Finalized"
    $rotations = $RotationScheduleStatus->getNonScheduledFinalizedRotations();

    echo json_encode($rotations);