<?php
	session_start();
    if(!isset($_SESSION['userID']) || !isset($_SESSION['role'])) {
        $_SESSION['appErrMsg'] = 'Login Error';
        header('Location: error.php');
    }

    require_once("./inc/top_layout.php");
	require_once(__DIR__."/inc/Business/Congregation/CongregationCoordinator.class.php");

	$CongregationCoordinator = new CongregationCoordinator();
?>
	<div id="congregation-coordinators">
		<h4>Congregation Coordinators</h4>
		<p>Email Congregations: <img src='img/plain-email.svg' id='coord-email-icon' data-toggle="modal" data-target="#email-cong-modal"/></p>
        <?php
			//Gets the data for all host congregation coordinators in MySQL
			$CongregationCoordinator->getCongregationCoordinators();
        ?>
	</div>
	<div class="modal fade" id="email-cong-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modalLabel">Send Email</h4>
				</div>
				<div class="modal-body">
                    <div class="form-group">
                        <label>Email:</label>
                        <select name="cong-coor-email" id="email-input-field">
                            <option selected disabled>Select a coordinator email</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject:</label>
                        <input type="text" id="email-subject-field" placeholder="Enter email subject"/>
                    </div>
                    <div class="form-group">
                        <label>Message:</label>
                        <textarea name="editor1" id="editor1" rows="10" cols="80"></textarea>
                        <script>
                            // Replace the <textarea id="editor1"> with a CKEditor
                            // instance, using default configuration.
                            CKEDITOR.replace( 'editor1' );
                        </script>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" id="email-cong-cancel" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					<button type="button" id="email-cong-save" class="btn btn-primary">Send email</button>
				</div>
			</div>
		</div>
	</div>

<?php require_once("./inc/bottom_layout.php"); ?>
