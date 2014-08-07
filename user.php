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
error_reporting(0);//remove for debug
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
    <script src="js/jquery.smoothscroll.js"></script>
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
        $user = $_GET['u'];
        $userq = mysqli_query($db,"SELECT * FROM Users WHERE name='$user'");
		$row = mysqli_fetch_array($userq);
		$username = $row['disname'];

		checkMode('init');

		date_default_timezone_set('America/New_York'); //set timezone
		try {
			$date1 = new DateTime($_SESSION['age']); //compare age from database with current time
			$date2 = new DateTime();
			$interval = $date1->diff($date2);
			//echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
		} catch (Exception $e){
			//do nothing :D
		}

		$email = $row['email'];
		$bio = $row['bio'];
		$size = 300;
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size . "&d=retro";
	}
	?>

	<section class="userinfo">
	<img class="userinfo-userpic" src="<?php echo $grav_url; ?>" alt="" />
		<section class="userinfo-right">
			<h3 class="userinfo-username">
			<?php if($_SESSION['user'] === $user) { ?>
				
				<p><input class="userinfo-username-edit userinfo-editable"  type="text" name="username" id="username" placeholder="Set your display name" value="<?php echo $username ?>" onFocus="recordName()" onBlur="updateName()"></p>

			<?php 
				} else {
					echo $username;
				} 
			?>
			</h3>
			<?php if($user != $username) { ?>
			<h4 class="userinfo-username"><i><?php echo $user ?></i></h4>
			
			<?php }
				if ($_SESSION['user'] === $user) { ?>
				<p><textarea class="userinfo-edit userinfo-editable" name="content" id="bio" placeholder="Write a little about yourself!" onFocus="recordBio()" onBlur="updateBio()"><?php echo $bio ?></textarea></p>
			<?php 
			} else { 
				echo '<p class="userinfo-bio">';
				echo $bio;
				echo '</p>';
			 }
			?>

			<i class="fa fa-gear fa-spin userinfo-working"></i>
			
		</section>
	</section>


	<div class="footer" style="bottom:0px;position:absolute;">
		<div class="pagn" style="float:left;margin-top:3px">&copy; 2014 Theodore Kluge</div>
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

	<form action="login.php" method="post" name="logout" id="logout">
		<input type="hidden" value="logout">
	</form>

	<script type="text/javascript">
	var currentName;
	var currentBio;

	$('textarea#bio').css('width', $('input.userinfo-username-edit').css('width'));
	$(window).resize(function() {
		$('textarea#bio').css('width', $('input.userinfo-username-edit').css('width'));
	});

	function updateDelModal(pid) {
		//$('#delSubBtn').attr('onClick', '$("#delpost' + pid + '").submit()');
		//$('#delSubBtn').attr('onClick', 'document.getElementById("delpost' + pid + '").submit(); console.log(\'boop\')');
		$('#delSubBtn').attr('onClick', 'document.getElementById("delpost' + pid + '").submit()');
		$('#delModal').modal();
	}

	function recordName() {
		currentName = $('input.userinfo-username-edit').val();
		console.log("recorded name " + currentName);
	}
	function recordBio() {
		currentBio = $('textarea#bio').val();
		console.log("recorded bio " + currentBio);
	}

	function updateName() {
		$('.userinfo-working').css('visibility','visible');
		var udname = $('input.userinfo-username-edit').val();
		if (udname != currentName) {
			$.post("dbquery.php", { udname: udname }, function(result){
		 		if (result == 1) {
		 			console.log('username update successful');
		 		} else {
		 			console.log('username update failed');
		 		}
		 		$('.userinfo-working').css('visibility','hidden');
	 		});  
		} else {
			console.log("name not changed");
	 		$('.userinfo-working').css('visibility','hidden');	
		}
	}

	function updateBio() {
		$('.userinfo-working').css('visibility','visible');
		var udbio = $('textarea#bio').val();
		if (udbio != currentBio) {
			$.post("dbquery.php", { udbio: udbio }, function(result){
		 		if (result == 1) {
		 			console.log('bio update successful');
		 		} else {
		 			console.log('bio update failed');
		 		}
		 		$('.userinfo-working').css('visibility','hidden');
	 		});  
		} else {
			console.log("bio " + currentBio + " not changed");
			$('.userinfo-working').css('visibility','hidden');
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
