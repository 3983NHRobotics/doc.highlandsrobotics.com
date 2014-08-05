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
				        
				      <div class="blag-register-body">

				      		<p class="loginpage-title"><?php echo $title; ?></p>
							
							<label class="loginpage-content-title" for="uname"><i class="fa fa-user"></i> Username*</label>
						    <input class="loginpage-content" name="unamesub" type="text" id="uname" value="<?php if(isset($_POST['unamesub'])) { echo addslashes($_POST['unamesub']);} ?>" placeholder=" Username" required> 
						
							<label class="loginpage-content-title" for="upass"><i class="fa fa-unlock-alt"></i> Password*</label>
						    <input class="loginpage-content" name="upasssub" type="password" id="upass" placeholder=" Password" required>

						    <label class="loginpage-content-title" for="uemail"><i class="fa fa-envelope"></i> Email*</label>
						    <input class="loginpage-content" name="uemailsub" type="text" id="uemail" value="<?php if(isset($_POST['uemailsub'])) {echo addslashes($_POST['uemailsub']);}?>" placeholder=" Email address" required> 

						    <label class="loginpage-content-title" for="uage"><i class="fa fa-certificate"></i> Birthdate*</label>
						    <input class="loginpage-content" name="uagesub" type="text" id="uage" placeholder=" YYYY-mm-dd" required>
						    <!-- replace text form with date dropdowns -->

				        <button style="" type="submit" name="Register" class="btn btn-submit btn-register" onclick="document.register.submit();">Register</button>

				       </div>
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
        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
        }

		if(isset($_POST['Register'])) {
			$unamesub = addslashes($_POST['unamesub']);
			$uemailsub = addslashes($_POST['uemailsub']);
			$uage = $_POST['uagesub'];

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
                    '0')";

            if (!mysqli_query($db,$sql)) {
                die('Error: ' . mysqli_error($db));
            }

			header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/login.php');
				
		}
	?>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		  <form action="login.php" method="post" name="login" id="login" onsubmit="">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-unlock"></i> Site unlock</h4>
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
