<!DOCTYPE html>
<html lang="en">
<head>
    <title>RAIN Scheduler</title>

    <!-- Minified Bootstrap v3.7.7 CSS -->
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">

    <!-- Minified Font Awesome CSS -->
    <link rel="stylesheet" type="text/css" href="./css/font-awesome.min.css">

    <!-- Minified FullCalendar CSS -->
    <link rel="stylesheet" type="text/css" href="./css/fullcalendar.min.css">
	   
	<!-- Main custom CSS -->
    <link rel="stylesheet" type="text/css" href="./css/contact.css">
    
	<!-- Main custom CSS -->
    <link rel="stylesheet" type="text/css" href="./css/styles.css">

    <!-- Minified ckeditor JS -->
    <script type="text/javascript" src="./js/ckeditor/ckeditor.js"></script>
</head>
<body>
	<div id="title-section">
        <div id="logo">
            <div id="logo-img">
                <img src="./img/RAIHNlogo.PNG"  alt="RAIHN logo"/>
            </div>
            <div id="logo-text">
                <div id="logo-text-pt1">
                    <h1>RAIHN</h1>
                </div>
                <div id="logo-text-pt2">
                    <span id="scheduler-font">Scheduler App</span>
                </div>
            </div>
        </div>
	</div>
	<nav class="navbar navbar-default custom-bg">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	  	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    	<ul class="nav navbar-nav">
		      	<?php /* if logged in */ if(isset($_SESSION['email'])): ?>
		      		<?php /* if logged in as bus driver or admin */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Bus Driver" || $_SESSION['role'] == "Bus Driver Admin" || $_SESSION['role'] == "Admin")): ?>
				      	<li class="nav-item">
				        	<a class="nav-link" href="./busdriverroster.php">Bus Driver Roster</a>
				      	</li>
			      	<?php endif; ?>
					<?php /* if logged in as a bus driver */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Bus Driver")): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./inputBusBlackouts.php">Input Blackouts</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Finalized Schedules<b class="caret"></b>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="nav-link" href="./finalizedschedules.php">Congregation Schedules</a>
							<a class="nav-link" href="./finalBusSchedule.php">Bus Schedules</a>
                        </div>
                    </li>

			      	<?php /* if logged in as congregation or admin */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Congregation" || $_SESSION['role'] == "Congregation Admin" || $_SESSION['role'] == "Admin" )): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Congregation Rosters<b class="caret"></b>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="nav-link" href="./congregationroster.php">Host Congregation Roster</a>
                                <a class="nav-link" href="./congregationcoordinators.php">Congregation Coordinators</a>
                            </div>
                        </li>
			      	<?php endif; ?>
                    <?php /* if logged in as congregation or admin */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Congregation" || $_SESSION['role'] == "Admin")): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./inputblackouts.php">Input Blackouts</a>
                        </li>
                    <?php endif; ?>
                    <?php /* if logged in as congregation admin or admin */ if(isset($_SESSION['role']) && ($_SESSION['role'] == "Congregation Admin" || $_SESSION['role'] == "Admin")): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Admin Tools<b class="caret"></b>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="nav-link" href="./adminCongSchedule.php">Scheduled Rotations</a>
                                <a class="nav-link" href="./enteredblackoutsCongregation.php">Blackouts Entered</a>
                            </div>
                        </li>
                    <?php endif; ?>
			      	<?php /* if logged in as admin */ if(isset($_SESSION['role']) && $_SESSION['role'] == "Admin"): ?>
			      		<li class="nav-item">
				        	<a class="nav-link" href="./insertDateData.php">Insert Date Data</a>
				      	</li>
			      	<?php endif; ?>
			      	<li class="nav-item">
			        	<a class="nav-link" href="./setuppassword.php">Change Password</a>
			      	</li>
			      	<li class="nav-item">
			        	<a class="nav-link" href="./logout.php">Logout</a>
			      	</li>
		      	<?php else: ?>
		      	<li class="nav-item">
		        	<a class="nav-link" href="./index.php">Login</a>
		      	</li>
		      	<?php endif; ?>
			</ul>
	  	</div>
	</nav>
