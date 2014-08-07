<?php
session_start();
require ('includes/config.php');
$_SESSION['theme'] = $theme;
if (!isset($_SESSION['mode'])) {
	$_SESSION['mode'] = 'user';
}
if(!isset($_SESSION['user'])) {
	$_SESSION['user'] = 'Guest';
}
if (!isset($_SESSION['filterpref'])) {
	$filterPref = 1;
}
//error_reporting(0);//remove for debug
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blag Test</title>
    <?php

    	echo '<link rel="stylesheet" href="css/blag-' . $_SESSION['theme'] . '.css">';
    	
    	if ($usepace === 'true') {
    		echo '<link rel="stylesheet" href="css/pace/pace-centerbar.css">';
    		echo '<script src="js/pace/pace.min.js"></script>';
    	}
    ?>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">

	<link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Font Awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	
<!--   <link rel="stylesheet" href="css/fancy-buttons.css"> -->

  <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/blag.js"></script>
<<<<<<< HEAD
=======
    <script src="js/jquery.smoothscroll.js"></script>
>>>>>>> cd44cb4e60785eb8c3b7183332dae3d57e7d4387
    <style type="text/css">

    </style>
  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

	<?php
		function checkMode($type) {

			global $unamesub;

			if ($_SESSION['mode'] === 'admin') {

				?>
					<script type="text/javascript">
					$('.header').remove();
					</script>
					<div class="header-admin">
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
							<a href="/blag/user.php?u=<?php echo $_SESSION['user']; ?>" class="btn btn-random"><i class="fa fa-user"></i></a>
							<a href="/blag/admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
							<a href="/blag/edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
							<span class='msg-welcome'>Heyo, <?php echo strtok($_SESSION['username'], ' '); ?>!</span>
						</span>
					</div>
				<?php
				//echo "pagemode = admin<br> ";
			} else if ($_SESSION['mode'] === 'user') {
				$_SESSION['user'] = 'Guest';

				?>
					<script type="text/javascript">
					$('.header-admin').remove();
					</script>
					<div class="header">
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" class="btn-unlock" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt"></i></a>
						</span>
					</div>
				<?php
				
			} else { //loggeduser
				?>

				<script type="text/javascript">
					$('.header').remove();
					</script>
					<div class="header-admin">
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
							<a href="/blag/user.php?u=<?php echo $_SESSION['user']; ?>" class="btn btn-random"><i class="fa fa-user"></i></a>
							<span class='msg-welcome'>Heyo, <?php echo strtok($_SESSION['username'], ' '); ?>!</span>
						</span>
					</div>

				<?php
			}
		}

		require('includes/user.php');

		$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
        if (mysqli_connect_errno()) {
	        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
	        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
        } else {

		checkMode('init');
		//echo '<br>Name: ' . $uname. " <br>PassSHA1: ".$upass;

		if (!empty($_GET['p'])) {
			$pagenumber = $_GET['p'];
		} else {
			$_GET['p'] = '1';
			$pagenumber = '1';
		}
		//display the greeting post
		if ($_GET['p'] == '1') {
			echo '<div class="blag-body">
					<h2 style="text-align:center"> '. $greeting .'</h2>
					<h4 style="text-align:center">' . $greetingContent . '</h4>
				  </div>';
		}

		$start_page = ($pagenumber - 1) * 10;
		$body = mysqli_query($db,"SELECT * FROM Posts ORDER BY PID DESC LIMIT $start_page,10"); //This works!

		date_default_timezone_set('America/New_York'); //set timezone
		try {
			$date1 = new DateTime($_SESSION['age']); //compare age from database with current time
			$date2 = new DateTime();
			$interval = $date1->diff($date2);
			//echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
		} catch (Exception $e){
			//do nothing :D
		}

		if ($interval->y >= 18 && $filterPref == 0) { //if age > 18, display unfiltered if filter is off
			while($row = mysqli_fetch_array($body)) {
			    echo '<div class="blag-body">
					<h3>' . $row['title'] . '</h3>
					<p>' . $row['content'] . '</p>
					<span class="timestamp">Posted by '. $row['creator'] . ' - ' . $row['timestamp'] . '</span>';
				if ($_SESSION['mode'] == 'admin') {
					echo '<div class="editdelete">';
					echo '<form action="dbquery.php" method="post" name="deletepost" id="delpost' . $row['PID'] . '">
					<input type="hidden" name="postid" value="' . $row["PID"] . '">
					<button type="submit" value="Delete" name="deletepost" class="editdelbtn" onClick="return confirm(\'Are you sure you want to delete this post?\')">Delete</button>
					</form>';
					echo '<form action="edit.php?action=edit" method="post" name="Entereditcontent" id="editpost' . $row['PID'] . '">
					<input type="hidden" name="postid" value="' . $row["PID"] . '">
					<button type="submit" value="Edit" name="Entereditcontent" class="editdelbtn">Edit</button>
					</form>';
					echo'</div>';
					//echo '<div class="editdelete"><a onClick="document.getElementById(\'editpost' . $row['PID'] . '\').submit()">Edit</a> <i class="fa fa-circle-o"></i> <a href="#" data-toggle="modal" data-target="#delModal" onClick="updateDelModal(' . $row['PID'] . ')">Delete</a></div>';
				  } else if ($_SESSION['mode'] == 'loggeduser') {
				  	//reply button type stuff goes here
				  }
				echo ' +</div>';
			}
		} else { //same stuff but filtered for 17-
			while($row = mysqli_fetch_array($body)) {
			    echo '<div class="blag-body">
					<h3>' . $row['title'] . '</h3>
					<p>' . $row['content'] . '</p>
					<span class="timestamp">Posted by '. $row['creator'] . ' - ' . $row['timestamp'] . '</span>';
				if ($_SESSION['mode'] == 'admin') {
					echo '<div class="editdelete">';
					echo '<form action="dbquery.php" method="post" name="deletepost" id="delpost' . $row['PID'] . '">
					<input type="hidden" name="postid" value="' . $row["PID"] . '">
					<button type="submit" value="Delete" name="deletepost" class="editdelbtn" onClick="return confirm(\'Are you sure you want to delete this post?\')">Delete</button>
					</form>';
					echo '<form action="edit.php?action=edit" method="post" name="Entereditcontent" id="editpost' . $row['PID'] . '">
					<input type="hidden" name="postid" value="' . $row["PID"] . '">
					<button type="submit" value="Edit" name="Entereditcontent" class="editdelbtn">Edit</button>
					</form>';
					echo'</div>';
					//echo '<div class="editdelete"><a onClick="document.getElementById(\'editpost' . $row['PID'] . '\').submit()">Edit</a> <i class="fa fa-circle-o"></i> <a href="#" data-toggle="modal" data-target="#delModal" onClick="updateDelModal(' . $row['PID'] . ')">Delete</a></div>';
				  } else if ($_SESSION['mode'] == 'loggeduser') {
				  	//reply button type stuff goes here
				  }
				echo '</div>';
			}
		}

		$pages = mysqli_query($db, "SELECT COUNT(*) FROM Posts");
		$row = mysqli_fetch_row($pages);
		$total_things = $row[0];
		$total_pages = ceil($total_things / 10); //gets the number of pages for pagination
	}
	?>
	<div class="footer">
		<div class="pagn" style="float:left;margin-top:3px">&copy; 2014 Theodore Kluge</div>
		<div class="pagn">
			Pages: 
			<?php
				for ($i = 1; $i <= $total_pages; $i++) { 
	            	echo "<a class='pagnbtn' href='" . dirname($_SERVER['SCRIPT_NAME']) . "/?p=" . $i . "'>" . $i . "</a> "; 
				}
			?>
		</div>
	</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		  <form action="login.php" method="post" name="login" id="login" onsubmit="">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-unlock"></i> Login</h4>
		      </div>
		      <div class="modal-body">
				    <p>
				      <input class="editpage-content" name="unamesub" type="text" id="uname" value="" placeholder="Username"> 
				  </p>
				    <p>
				      <input class="editpage-content" name="upasssub" type="password" id="upass" placeholder="Password"> 
				  </p>
				  <!-- <input type="submit" name="Login" value="Login"> -->
		      </div>
		      <div class="modal-footer">
		        <a href="register.php" class="reg" style="float: left">Sign up</a>
		        <button type="submit" name="Login" class="btn btn-submit" onclick="document.login.submit();">Unlock</button>
		        <!-- <input type="submit" name="login" value="Login"> -->
		      </div>
		  </form>
    </div>
  </div>
</div>

<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-warning"></i> Confirm deletion</h4>
		      </div>
		      <div class="modal-body">
				    <p>
				      Are you sure you want to delete this post? 
				  </p>
		      </div>
		      <div class="modal-footer">
		        <button id="delSubBtn" data-dismiss="modal" value="Delete" name="deletepost" class="btn btn-submit" onClick="document.getElementById('delpost').submit()">Delete</button>
		      </div>
    </div>
  </div>
</div>

	<form action="login.php" method="post" name="logout" id="logout">
		<input type="hidden" value="logout">
	</form>

	<script type="text/javascript">
	function updateDelModal(pid) {
		//$('#delSubBtn').attr('onClick', '$("#delpost' + pid + '").submit()');
		//$('#delSubBtn').attr('onClick', 'document.getElementById("delpost' + pid + '").submit(); console.log(\'boop\')');
		$('#delSubBtn').attr('onClick', 'document.getElementById("delpost' + pid + '").submit()');
		$('#delModal').modal();
	}

	function confirmDelete() {
		if(confirm("Are you sure you want to delete this post?")) {
			return true;
		} else {
			return false;
		}
		
	}
	</script>

	<!--<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-48081162-1', 'villa7.github.io');
	  ga('send', 'pageview');

	</script> -->

</body>
</html>
