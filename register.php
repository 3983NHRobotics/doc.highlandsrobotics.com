<?php
require('includes/config.php');
require('includes/password_newfunctions.php');

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
    <title><?php echo $siteTitle ?> - Register</title>
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
	select {
		background: rgba(0,0,0,.15);
		border: #000 1px solid;
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

					<form action="register.php" method="post" name="register" id="register" onsubmit="">
				    <section class="blag-register-body-container">
				      <div class="blag-register-body">

				      		<p class="loginpage-title"><?php echo $title; ?></p>
							
							<label class="loginpage-content-title" for="uname"><i class="fa fa-user"></i> Username*</label>
						    <input class="loginpage-content" name="unamesub" type="text" id="uname" value="<?php if(isset($_POST['unamesub'])) { echo ($_POST['unamesub']);} ?>" placeholder="Username" required onBlur="check_availability()">
						    <i class="fa fa-check-square uname-val" id="namevalid"></i>
						
						    <div class="form-halfwidth">
							<label class="loginpage-content-title" for="upass" id="passlab"><i class="fa fa-unlock-alt"></i> Password*</label>
						    <input class="loginpage-content" name="upasssub" type="password" id="upass" placeholder="Password" required >
						    <i class="fa fa-check-square upass-val" id="passvalid"></i>
						    </div>
						    <div class="form-halfwidth">
						    <label class="loginpage-content-title" for="upass2" id="passlab2"><i class="fa fa-unlock-alt"></i> Repeat password*</label>
						    <input class="loginpage-content" name="upasssub2" type="password" id="upass2" placeholder="Password" required >
						    <i class="fa fa-check-square upass-val" id="passvalid2"></i>
						    </div>

						    <label class="loginpage-content-title" for="uemail"><i class="fa fa-envelope"></i> Email*</label>
						    <input class="loginpage-content" name="uemailsub" type="text" id="uemail" value="<?php if(isset($_POST['uemailsub'])) {echo ($_POST['uemailsub']);}?>" placeholder="Email address" required>

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

        function isEmail($email) { //email verification madness
			return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
		}

		if(isset($_POST['Register'])) {
			$unamesub = addslashes(strip_tags_attributes($_POST['unamesub']));
			$uemailsub = addslashes(strip_tags_attributes($_POST['uemailsub']));
			$uage = $_POST['dateyear'] . '-' . $_POST['datemonth'] . '-' . $_POST['dateday'];

			if ($_POST['upasssub'] != $_POST['upasssub2']) {
				echo '<script type="text/javascript">displayLoginError(\'error\', \'Passwords must match\');</script>';
			} else if (preg_match('/\s/',$unamesub)) {
				echo '<script type="text/javascript">displayLoginError(\'error\', \'Username cannot have spaces\');</script>';
			} else {

				$options = [
	                'cost' => 11,
	            ];

	            $upass = password_hash(addslashes(strip_tags_attributes($_POST['upasssub'])), PASSWORD_BCRYPT, $options);
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
