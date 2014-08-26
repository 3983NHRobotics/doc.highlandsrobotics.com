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
if (!isset($_SESSION['filterPref'])) {
	$filterPref = 1;
} else {
	$filterPref = $_SESSION['filterPref'];
}
//error_reporting(0);//remove for debug
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>The Blag</title>
    <?php
    	echo '<link rel="stylesheet" href="css/blag-light.css">';
    	if ($usecustombg == "true") {
				echo '<style type="text/css">body{background: url("' . $custombg . '")}</style>';
			}
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
	<?php 
	if($localcode) {
    echo '<script src="js/jquery.min.js"></script>';
	} else {
    echo '<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>';
	}
	?>
    <script src="js/holder.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/blag.js"></script>
    <script src="js/jquery.smoothscroll.js"></script>
    <style type="text/css">
	    .userinfo-config {
	    	background: rgba(0,0,0,0);
	    	position: relative;
	    	margin: 20px 0px 20px 20px;
	    }
	    .userinfo-config-content {
	    	width: 70%;
	    	margin-bottom: 10px;
	    	background: rgba(0,0,0,.05);
	    	border: #444 1px solid;
	    	padding-left: 5px;
	    }
	    .userinfo-config-submit {
	    	background: rgba(0,0,0,.05);
	    	border: #444 1px solid;
	    	margin-left: 5px;
	    	padding-left: 15px;
	    	padding-right: 15px;
	    	-webkit-transition: all .2s ease-in-out;
          transition: all .2s ease-in-out;
	    }
	    .userinfo-config-submit:hover {
	    	background: rgba(0,0,0,.4);
	    	color: #c04000;
	    }
	     .upass-val {
	    	float: right;
	    	position: absolute;
	    	margin: 5px 0px 0px -23px;
	    	font-size: 15px;
	    	color: rgba(0,0,0,0);
	    }
	    .mce-tinymce {
	    	margin-left: 20px !important;
	    }
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
							<a href="index.php" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
							<a href="user.php?u=<?php echo $_SESSION['user']; ?>" class="btn btn-random"><i class="fa fa-user"></i></a>
							<a href="admin/admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
							<a href="edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
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
							<a href="index.php" class="btn homebtn"><i class="fa fa-home"></i></a>
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
							<a href="user.php?u=<?php echo $_SESSION['user']; ?>" class="btn btn-random"><i class="fa fa-user"></i></a>
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
        $user = mysqli_real_escape_string($db, $_GET['u']);
        $userq = mysqli_query($db,"SELECT * FROM Users WHERE name='$user'");
		$row = mysqli_fetch_array($userq);
		$username = $row['disname'];
		if($username == null) {
			$username = 'User does not exist.';
		}

		checkMode('init');
		
		date_default_timezone_set('America/New_York'); //set timezone
		try {
			$date1 = new DateTime($_SESSION['age']); //compare age from database with current time
			$date2 = new DateTime();
			$age = $date1->diff($date2);
			//echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
		} catch (Exception $e){
			//do nothing :D
		}

		$email = $row['email'];
		$bio = $row['bio'];
		$size = 300;
		if ($grav_default === 'custom') {
			$grav_default = $grav_custom;
		}
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . $grav_default . "&s=" . $size . "&r=" . $grav_rating;
		}
	?>

	<section class="userinfo">
	<?php 
	if ($localcode) {
		echo '<img class="userinfo-userpic" data-src="holder.js/177x177" alt="" />';
	} else {
	echo '<a href="https://en.gravatar.com/emails/" target="_blank"><img class="userinfo-userpic" src="' . $grav_url . '" alt="" /></a>';
	}
	?>
	
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
			<h4 class="userinfo-username"><i><?php echo stripslashes($user) ?></i></h4>
			
			<?php }
				if ($_SESSION['user'] === $user) { ?>
				<style type="text/css">
					.userinfo {
						/*height: 600px;*/
						height: 450px;
					}
				</style>
				<p><textarea rows="4" class="userinfo-edit userinfo-editable" name="content" id="bio" placeholder="Write a little about yourself! You can use some html tags too :D" onFocus="recordBio()" onBlur="updateBio()"><?php echo $bio ?></textarea></p>

		<section class="userinfo-config">
							<label class="loginpage-content-title userinfo-config-title" for="upass" id="passlab"><i class="fa fa-unlock-alt"></i> Change password</label><br>
						    <input class="userinfo-config-content" name="upasssub" type="password" id="upass" placeholder="Password" required >
						    <i class="fa fa-check-square upass-val" id="passvalid"></i><br>

						    <label class="loginpage-content-title userinfo-config-title" for="upass2" id="passlab2"><i class="fa fa-unlock-alt"></i> Repeat new password</label><br>
						    <input class="userinfo-config-content" name="upasssub2" type="password" id="upass2" placeholder="Password" required >
						    <i class="fa fa-check-square upass-val" id="passvalid2"></i>
						    <button class="userinfo-config-submit" onclick="submitPass()">Submit</button><br>

						    <label class="loginpage-content-title userinfo-config-title" for="uemail"><i class="fa fa-envelope"></i> Change email</label><br>
						    <input class="userinfo-config-content" name="uemailsub" type="text" id="uemail" value="<?php echo $email; ?>" placeholder="Email address" required>
						    <button class="userinfo-config-submit" onclick="submitEmail()">Submit</button>
		</section>
			<?php 
			} else { 
				echo '<p class="userinfo-bio">';
				echo $bio;
				echo '</p>';
				echo '<style type="text/css">.footer{position: absolute}</style>';
			 }
			?>

			<i class="fa fa-gear fa-spin userinfo-working"></i>
			<span class="userinfo-footer">A relog is required for changes to take effect.</span>
			
		</section>
	</section>


	<div class="footer" style="bottom:0px; position: absolute">
		<div class="pagn" style="float:left">&copy; 2014 Theodore Kluge</div>
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

	<?php if ($usetinymce === 'true') { ?>
	<!-- <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script> -->
	<!--<script type="text/javascript" src="includes/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	        tinymce.init({
	        	selector:'textarea#bio',
	        	plugins: [
	        		"autolink lists link image preview",
	        		"searchreplace code fullscreen",
	        		"media table paste contextmenu"
	        	],
	        	toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	        });

			setTimeout(function() {
				tinymce.get('bio').dom.loadCSS('css/blag-light-tinymce.css');
			}, 500); //delay while tinymce loads
	</script>-->
	<?php } ?>

	<script type="text/javascript">
	var currentName;
	var currentBio;
	var currentEmail;
	var pavail = false;

	$('textarea#bio').css('width', $('input.userinfo-username-edit').css('width'));
	$(window).resize(function() {
		$('textarea#bio').css('width', $('input.userinfo-username-edit').css('width'));
	});


	function recordName() {
		$('.userinfo-working').css('visibility','visible');
		$('input.userinfo-username-edit').css('background','#f3e5ab');
		currentName = $('input.userinfo-username-edit').val();
		console.log("recorded name " + currentName);
	}
	function recordBio() {
		$('.userinfo-working').css('visibility','visible');
		$('textarea#bio').css('background','#f3e5ab');
		currentBio = $('textarea#bio').val();
		console.log("recorded bio " + currentBio);
	}
	function recordEmail() {
		currentEmail = $('input#uemail').val();
		console.log("recorded email: " + currentEmail);
	}
	recordEmail();
	function updateName() {
		//$('.userinfo-working').css('visibility','visible');
		var udname = $('input.userinfo-username-edit').val();
		if (udname != currentName) {
			$.post("dbquery.php", { udname: udname }, function(result){
		 		if (result == 1) {
		 			console.log('username update successful');
		 			$('.userinfo-footer').css('visibility','visible');
		 		} else {
		 			console.log('username update failed');
		 		}
		 		$('.userinfo-working').css('visibility','hidden');
	 		});  
		} else {
			console.log("name not changed");
	 		$('.userinfo-working').css('visibility','hidden');	
		}
		$('input.userinfo-username-edit').css('background','rgba(0,0,0,0)');
	}

	function updateBio() {
		//$('.userinfo-working').css('visibility','visible');
		var udbio = $('textarea#bio').val();
		if (udbio != currentBio) {
			$.post("dbquery.php", { udbio: udbio }, function(result){
		 		if (result == 1) {
		 			console.log('bio update successful');
		 			//$('.userinfo-footer').css('visibility','visible');
		 		} else {
		 			console.log('bio update failed');
		 		}
		 		$('.userinfo-working').css('visibility','hidden');
	 		});  
		} else {
			console.log("bio " + currentBio + " not changed");
			$('.userinfo-working').css('visibility','hidden');
		}
		$('textarea#bio').css('background','rgba(0,0,0,0)');
	}
	$('#upass, #upass2').on('input', function checkPass() {
		
		if ($('#upass').val().length >= 5) {
			$('#passvalid').css('color','#99c68e') //light green
					.removeClass('fa-exclamation-triangle')
					.addClass('fa-check-square');
			if ($('#upass').val() == $('#upass2').val()) {
				$('#passvalid, #passvalid2').css('color','#99c68e') //light green
					.removeClass('fa-exclamation-triangle')
					.addClass('fa-check-square'); 
				$('#errordiv').css('visibility','none');
				pavail = true;
			} else {
				$('#passvalid2').css('color','#e77471') //light red
					.removeClass('fa-check-square')
					.addClass('fa-exclamation-triangle');
				displayLoginError('error','passwords do not match' );
				pavail = false;
			}
		} else {
			$('#passvalid').css('color','#e77471') //light red
					.removeClass('fa-check-square')
					.addClass('fa-exclamation-triangle');
			displayLoginError('error', 'password must be at least 8 characters');
		}
	});

	function submitPass() {
		var newpass = $('#upass').val();
		var newpassconf = $('#upass2').val();
		if (pavail) {
			$('.userinfo-working').css('visibility','visible');
			$.post("dbquery.php", { newpass: newpass, newpassconf: newpassconf }, function(result){
		 		if (result == 1) {
		 			console.log('pass update successful');
		 			displayLoginError('error', 'Updated password');
		 			$('#upass').val('');
		 			$('#upass2').val('');
		 			$('.userinfo-working').css('visibility','hidden');
		 		} else {
		 			console.log('pass update failed');
		 			$('.userinfo-working').css('visibility','hidden');
		 		}
	 		});  
		} else {
			displayLoginError('error', 'Password fields are invalid');
		}
	}
	function submitEmail() {
		var newemail = $('#uemail').val();
		$('.userinfo-working').css('visibility','visible');
		if (currentEmail != newemail) {
			$.post("dbquery.php", { newemail: newemail }, function(result){
			 		if (result == 1) {
			 			console.log('email update successful');
			 			displayLoginError('error', 'Updated email');
			 			$('.userinfo-working').css('visibility','hidden');
			 		} else {
			 			console.log('email update failed');
			 			$('.userinfo-working').css('visibility','hidden');
			 		}
		 		});  
		} else {
			displayLoginError('error', 'Email not changed');
			$('.userinfo-working').css('visibility','hidden');
		}
		recordEmail();
	}

	$('textarea#bio').keydown(function (e){
	    if(e.keyCode == 13){
	        $('textarea#bio').val($('textarea#bio').val() + '<br>');
	    }
	});
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
