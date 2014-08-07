<?php
require('includes/config.php');

$_SESSION['theme'] = $theme;
//if (!isset($_SESSION['mode'])) {
	$_SESSION['mode'] = 'user';
//}
if(!isset($_SESSION['user'])) {
	$_SESSION['user'] = 'Guest';
}

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

    <style type="text/css">
    
	@media (min-width: 270px) {
		.blag-register-body-container {
			width: 95%;
		}
		#upass, #passlab, #upass2, #passlab2{
	    	float: left;
	    	width: 100%;
	    	position: relative;
	    }
	    .upass-val {
	    	float: left;
	    	position: relative;
	    	margin: 9px 0px 0px -20px;
	    	font-size: 15px;
	    	color: rgba(0,0,0,0);
	    }
	    .uname-val {
	    	float: right;
	    	position: relative;
	    	margin: -35px 6px 0px 0px;
	    	font-size: 15px;
	    	color: rgba(0,0,0,0);
	    }
	}
	@media (min-width: 750px) {
    	.blag-register-body-container {
			width: 30%;
		}
	    .form-halfwidth {
	    	width: 50%;
	    	height: auto;
	    	float: left;
	    	position: relative;
	    }
	    #upass, #passlab, #upass2, #passlab2{
	    	float: left;
	    	width: 98%;
	    	position: relative;
	    }
	    .upass-val {
	    	float: left;
	    	position: relative;
	    	margin: 9px 0px 0px -20px;
	    	font-size: 15px;
	    	color: rgba(0,0,0,0);
	    }
	    .uname-val {
	    	float: right;
	    	position: relative;
	    	margin: -35px 6px 0px 0px;
	    	font-size: 15px;
	    	color: rgba(0,0,0,0);
	    }
	}

	.agesel {
		width: 33%;
		margin: 0px;
		height: 28px;
		float: left;
	}
    </style>

  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

	<?php
		
		require('includes/user.php');

		function checkMode($type) {

			global $unamesub;
			global $title;

			if ($_SESSION['mode'] === 'admin') {

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

					<form action="register.php" method="post" name="register" id="register" onsubmit="">
				    <section class="blag-register-body-container">
				      <div class="blag-register-body">

				      		<p class="loginpage-title"><?php echo $title; ?></p>
							
							<label class="loginpage-content-title" for="uname"><i class="fa fa-user"></i> Username*</label>
						    <input class="loginpage-content" name="unamesub" type="text" id="uname" value="<?php if(isset($_POST['unamesub'])) { echo ($_POST['unamesub']);} ?>" placeholder=" Username" required onBlur="check_availability()">
						    <i class="fa fa-check-square uname-val" id="namevalid"></i>
						
						    <div class="form-halfwidth">
							<label class="loginpage-content-title" for="upass" id="passlab"><i class="fa fa-unlock-alt"></i> Password*</label>
						    <input class="loginpage-content" name="upasssub" type="password" id="upass" placeholder=" Password" required >
						    <i class="fa fa-check-square upass-val" id="passvalid"></i>
						    </div>
						    <div class="form-halfwidth">
						    <label class="loginpage-content-title" for="upass2" id="passlab2"><i class="fa fa-unlock-alt"></i> Repeat password*</label>
						    <input class="loginpage-content" name="upasssub2" type="password" id="upass2" placeholder=" Password" required >
						    <i class="fa fa-check-square upass-val" id="passvalid2"></i>
						    </div>

						    <label class="loginpage-content-title" for="uemail"><i class="fa fa-envelope"></i> Email*</label>
						    <input class="loginpage-content" name="uemailsub" type="text" id="uemail" value="<?php if(isset($_POST['uemailsub'])) {echo ($_POST['uemailsub']);}?>" placeholder=" Email address" required>

						    <label class="loginpage-content-title" for="monthselect"><i class="fa fa-certificate"></i> Birthdate*</label><br>
						    <select id="monthselect" class="agesel" name="datemonth" required>	
						    </select>
						    <select id="dayselect" class="agesel" name="dateday" required>   	
						    </select>
						    <select id="yearselect" class="agesel" name="dateyear" required>		    	
						    </select>

				        <button style="" type="submit" name="Register" class="btn btn-submit btn-register" onclick="return checkTrue()">Register</button>
				        <a href="login.php" class="reg" style="position: absolute; bottom: 10px; left: 10px">Login</a>

				       </div>
				       </section>
				  	</form>


				<?php
				//echo "pagemode = user<br> ";
			} else {
				//echo 'fail ';
			}
		}

		checkMode('init');

		$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
        if (mysqli_connect_errno()) {
        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

		if(isset($_POST['Register'])) {
			$unamesub = addslashes($_POST['unamesub']);
			$uemailsub = addslashes($_POST['uemailsub']);
			$uage = $_POST['dateyear'] . '-' . $_POST['datemonth'] . '-' . $_POST['dateday'];

			if ($_POST['upasssub'] != $_POST['upasssub2']) {
				echo '<script type="text/javascript">displayLoginError(\'error\', \'Passwords must match\');</script>';
			} else {

				$options = [
	                'cost' => 11,
	            ];

	            $upass = password_hash(addslashes($_POST['upasssub']), PASSWORD_BCRYPT, $options);
	            //$upass = SHA2($_POST['upass'], 512);
	            $default = 'not set';

				$passwordFromPost = $upass;

				//check to see if uname/email is taken

				$sql = "INSERT INTO Users (name, pass, email, disname, age, isAdmin, filterPref)
	                    VALUES ('$unamesub', 
	                    '$passwordFromPost',
	                    '$uemailsub',
	                    '$unamesub',
	                    '$uage',
	                    '0',
	                    '1')";

	            if (!mysqli_query($db,$sql)) {
	                die('Error: ' . mysqli_error($db));
	            }

				//header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/login.php');
				echo '<script type="text/javascript">location.href = "login.php";</script>';
			}	
		}
	?>

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
				      <input class="editpage-content" name="unamesub" type="text" id="uname" value="" placeholder=" Username"> 
				  </p>
				    <p>
				      <input class="editpage-content" name="upasssub" type="password" id="upass" placeholder=" Password"> 
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

	<script type="text/javascript">

	var uavail = false;
	var pavail = false;

	$('#upass, #upass2').on('input', function checkPass() {
		
		if ($('#upass').val().length >= 8) {
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

	function check_availability() {  

        var username = $('#uname').val(); 

        if(username.length >= 1)  {
  
	        $.post("dbquery.php", { username: username },  
	            function(result) {  
	                if (result == 1) {  
	                    $('#namevalid').css('color','#99c68e') //light green
						.removeClass('fa-exclamation-triangle')
						.addClass('fa-check-square');
						uavail = true; 
	                } else {  
	                    $('#namevalid').css('color','#e77471') //light red
						.removeClass('fa-check-square')
						.addClass('fa-exclamation-triangle');
						uavail = false;
	                }  
	        });  
	    }
	}  

	function checkTrue() {
		if (uavail && pavail) {
			return true;
			document.register.submit();
			location.href = 'login.php';
		} else {
			alert('Something is wrong with the info you gave');
			return false;
		}
	}

    var d = new Date(); //line 120
    var year = d.getFullYear();
    var yearName = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    for (var i = 1; i <= 12; i++) {
    	$('#monthselect').append('<option value=\'' + i + '\'>' + yearName[i-1] + '</option>');
    }
    for (var i = 1; i <= 31; i++) {
    	$('#dayselect').append('<option value=\'' + i + '\'>' + i + '</option>');
    }
    for (var i = 0; i < 115; i++) {
		$('#yearselect').append('<option value=\'' + (year - i) + '\'>' + (year - i) + '</option>');
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
