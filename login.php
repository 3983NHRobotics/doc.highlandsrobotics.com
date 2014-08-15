<?php
session_start();

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
    	require ('/includes/config.php');
    	echo '<link rel="stylesheet" href="../css/blag-light.css">';
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

  	<?php 
	if($localcode) {
    echo '<script src="js/jquery.min.js"></script>';
	} else {
    echo '<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>';
	}
	?>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/blag.js"></script>
    <script src="js/blag_parser.js"></script>
    <style type="text/css">

	    .uname-val {
	    	float: right;
	    	position: relative;
	    	margin: -35px 6px 0px 0px;
	    	font-size: 15px;
	    	color: rgba(0,0,0,0);
	    }

    </style>

  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

	<?php
		
		require('includes/user.php');

		function strip_tags_attributes( $str, 
		    $allowedTags = array('<a>','<b>','<blockquote>','<br>','<cite>','<code>','<del>','<div>','<em>','<ul>','<ol>','<li>','<dl>','<dt>','<dd>','<img>','<video>','<iframe>','<ins>','<u>','<q>','<h3>','<h4>','<h5>','<h6>','<samp>','<strong>','<sub>','<sup>','<p>','<table>','<tr>','<td>','<th>','<pre>','<span>'), 
		    $disabledEvents = array('onclick','ondblclick','onkeydown','onkeypress','onkeyup','onload','onmousedown','onmousemove','onmouseout','onmouseover','onmouseup','onunload') )
		{       
		    if( empty($disabledEvents) ) {
		        return strip_tags($str, implode('', $allowedTags));
		    }
		    return preg_replace('/<(.*?)>/ies', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $disabledEvents) . ")=[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($str, implode('', $allowedTags)));
		}

		function checkMode($type) {

			global $unamesub;
			global $title;

			if ($_SESSION['mode'] === 'admin') {

				?>
				<!--WILL NEVER BE SEEN -->
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
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" class="btn-unlock" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt"></i></a>
						</span>
					</div>

					<form action="login.php" method="post" name="login" id="login" onsubmit="">
				        
				      <div class="blag-login-body">

				      		<p class="loginpage-title"><?php echo $title; ?></p>
							
							<label class="loginpage-content-title" for="uname"><i class="fa fa-user"></i> Username</label>
						    <input class="loginpage-content" name="unamesub" type="text" id="uname" value="" placeholder="Username" onBlur="check_availability()">
						    <i class="fa fa-check-square uname-val" id="namevalid"></i>
						
							<label class="loginpage-content-title" for="upass"><i class="fa fa-unlock-alt"></i> Password</label>
						    <input class="loginpage-content" name="upasssub" type="password" id="upass" placeholder="Password">
						    <i class="fa fa-check-square uname-val" id="passvalid"></i>

				        <button type="submit" name="Login" class="btn btn-submit" onclick="document.login.submit();">Unlock</button>
				        <a href="register.php" class="reg" style="position: absolute; bottom: 10px; left: 10px">Sign up</a>

				       </div>
				  	</form>


				<?php
				//echo "pagemode = user<br> ";
			} else {
				//echo 'fail ';
			}
		}

		checkMode('init');
		//echo '<br>Name: ' . $uname. " <br>PassSHA1: ".$upass;

		$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
        if (mysqli_connect_errno()) {
        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
        }

		if(isset($_POST['Login'])) {
			$unamesub = addslashes(strip_tags_attributes($_POST['unamesub']));
			$upassSHA = addslashes(strip_tags_attributes($_POST['upasssub']));

			$user = mysqli_query($db,"SELECT * FROM Users WHERE name='$unamesub'");
			$row = mysqli_fetch_array($user);

			$passwordFromPost = $_POST['upasssub'];
			$hashedPasswordFromDB = $row['pass'];
			$mode = $row['isAdmin'];
			//echo "<script type='text/javascript'>console.log('" . $mode . "');</script>";

			if (password_verify($passwordFromPost, $hashedPasswordFromDB)) {
				echo "<script type='text/javascript'>$('#passvalid').css('color','#99c68e') //light green
						.removeClass('fa-exclamation-triangle')
						.addClass('fa-check-square');</script>";
				if($mode == 1) {
			    	$_SESSION['mode'] = 'admin';
				} else {
					$_SESSION['mode'] = 'loggeduser';
				}
				$_SESSION['user'] = $unamesub;
				$_SESSION['username'] = $row['disname'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['age'] = $row['age'];
				$_SESSION['filterPref'] = $row['filterPref'];
				checkMode('login');
				//sleep(1); //pointless
				header('Location: ' . dirname($_SERVER['PHP_SELF']));
				die();
			} else {
			    echo "<script type='text/javascript'>displayLoginError('error', 'Incorrect password')</script>";
			    echo "<script type='text/javascript'>$('#passvalid').css('color','#e77471') //light red
						.removeClass('fa-check-square')
						.addClass('fa-exclamation-triangle');</script>";
			}
		}

		if(isset($_POST['Logout'])) {
			$_SESSION['user'] = 'Guest';
			$_SESSION['username'] = 'Guest';
			$_SESSION['mode'] = 'user';
			checkMode('logout');
			//header('Location: index.php');
			//echo 'Test';
			session_unset();
		}

	?>

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
	function check_availability() {  

        var username = $('#uname').val(); 

        if(username.length >= 1)  {
  
	        $.post("dbquery.php", { username: username },  
	            function(result) {  
	                if (result == 0) {  
	                    $('#namevalid').css('color','#99c68e') //light green
						.removeClass('fa-exclamation-triangle')
						.addClass('fa-check-square'); 
	                } else {  
	                    $('#namevalid').css('color','#e77471') //light red
						.removeClass('fa-check-square')
						.addClass('fa-exclamation-triangle');
	                }  
	        });  
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
