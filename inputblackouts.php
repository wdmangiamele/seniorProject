<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Business/Congregation/DateRange.class.php");

    $DateRange = new DateRange();

	$initialRotation = $DateRange->getMinimumRotationNumber();
?>

<?php
	if(isset($insertResult)) {
		echo $insertResult;
	}
?>
<!-- Blackout content-->
<div id="blackout-content">
    <div class="blackout-header">
        <h4 class="blackout-title">
            <a href="#" id="prev-btn" class="btn btn-info btn-sm">
                <span class="glyphicon glyphicon-chevron-left"></span> Prev
            </a>
            <?php echo "Rotation: <span id='rot-number'>".$initialRotation."</span> (Weeks are from Sunday to Saturday)"; ?>
            <a href="#" id="nxt-btn" class="btn btn-info btn-sm">
                Next <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </h4>
        <?php echo "<p>Entering blackouts for user: <span id='curr-user'>".$_SESSION['email']."</span></p>"; ?>
    </div>
    <hr align="left"/>
    <!--<form action="inputblackouts.php" method="post">-->
        <div class="blackout-body">
            <div class="blackout-checkboxes">
            </div>
        </div>
        <div class="blackout-footer">
            <button id="blackoutSubmit" type="submit" class="btn btn-primary" name="blackoutSubmit" data-toggle="modal" data-target="#input-data-submit">Enter Blackouts</button>
        </div>
    <!--</form>-->
</div>
<div id="calendar">

</div>
<div class="modal fade" id="input-data-submit" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" id="input-data-cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" id="input-data-save" class="btn btn-primary">Submit Blackouts</button>
            </div>
        </div>
    </div>
</div>

<?php require_once("./inc/bottom_layout.php"); ?>
