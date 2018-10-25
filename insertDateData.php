<?php
	session_start();
	require_once("./inc/top_layout.php");
    require_once(__DIR__."/inc/Controller/DateRange.class.php");

    $DateRange = new DateRange();

	if(isset($_SESSION['data-msg'])) {
		//Set the data message from the session variable as a new local variable
		$dataMsg = $_SESSION['data-msg'];

		//Remove the value of the session variable
		unset($_SESSION['data-msg']);
	}

	//If the data submit button was pressed, verify the data inputted
	if(isset($_POST['data-submit'])) {
		//Check if the start date is in the correct format
		if($DateRange->validateDate($_POST['start-date'], 'Y-m-d')) {
			//Check if the number of years wanted is at least 1
			if($_POST['num-years-wanted'] > 0) {
				//Check if the start year is in the correct format
				if($DateRange->validateDate($_POST['start-year'], 'Y')) {
					//Check if the next rotation number is greater than 0
					if($_POST['nxt-rotation-num'] >= 0) { //Needs to add check for being greater than most recent rotation completed
						$_SESSION['data-msg'] = $DateRange->insertDateRange($_POST['start-date'], $_POST['num-years-wanted'],
																$_POST['start-year'], $_POST['nxt-rotation-num']);
						if($_SESSION['data-msg']) {
							$_SESSION['data-msg'] = "<div class='alert alert-success'>
												<strong>Success!</strong> Data inserted!
											</div>";
							header("Location: insertDateData.php");
						}else {
							$_SESSION['data-msg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Data insert failed!
											</div>";
							header("Location: insertDateData.php");
						}
						header("Location: insertDateData.php");
					}else {
						$_SESSION['data-msg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Next rotation number must be greater than 0!
											</div>";
						header("Location: insertDateData.php");
					}
				}else {
					$_SESSION['data-msg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Start year must be in YYYY format!
											</div>";
					header("Location: insertDateData.php");
				}
			}else {
				$_SESSION['data-msg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong> Number of years wanted needs to be greater than 0!
											</div>";
				header("Location: insertDateData.php");
			}
		}else {
			$_SESSION['data-msg'] = "<div class='alert alert-danger'>
												<strong>Error!</strong>Start date was not entered as YYYY-MM-DD format!
											</div>";
			header("Location: insertDateData.php");
		}
	}

?>
	<?php
		//If there's a data message, display it here
		if(isset($dataMsg)) {
			echo $dataMsg;
		}
	?>

	<form method="post" id="insert-date-form" action="insertDateData.php">
		<div class="form-group row">
			<label for="start-date" class="col-sm-2 col-form-label">Start Date of Next Rotation</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="start-date" name="start-date" placeholder="E.g) 2018-01-01" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="num-years-wanted" class="col-sm-2 col-form-label">Number of Years Wanted</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="num-years-wanted" name="num-years-wanted" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="start-year" class="col-sm-2 col-form-label">Start Year of Next Rotation</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="start-year" name="start-year" placeholder="E.g) 2018" required>
			</div>
		</div>
		<div class="form-group row">
			<label for="nxt-rotation-num" class="col-sm-2 col-form-label">Number of the Next Rotation</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="nxt-rotation-num" name="nxt-rotation-num" placeholder="E.g) 53 (The '53rd' rotation)" required>
			</div>
		</div>
		<div id="submit-button">
			<button id="data-submit" name="data-submit" type="submit" class="btn btn-primary">Insert Data</button>
		</div>
	</form>

<?php require_once("./inc/bottom_layout.php"); ?>
