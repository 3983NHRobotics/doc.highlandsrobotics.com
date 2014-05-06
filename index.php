<?php
session_start();
include('includes/config.php');
$_SESSION['theme'] = $theme;
if (!isset($_SESSION['mode'])) {
	$_SESSION['mode'] = 'user';
}
if(!isset($_SESSION['user'])) {
	$_SESSION['user'] = 'Guest';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blag Test</title>
    <?php
    	include('/includes/paths.php');

    	if ($_SESSION['theme'] == 'light') {
    		echo '<link rel="stylesheet" href="css/blag-light.css">';
    	} else if ($_SESSION['theme'] == 'gray') {
    		echo '<link rel="stylesheet" href="css/blag-med.css">';
    	} else if ($_SESSION['theme'] == 'dark') {
    		echo '<link rel="stylesheet" href="css/blag-dark.css">';
    	} else { //load custom stylesheet
    		echo '<link rel="stylesheet" href="css/blag-custom.css">';
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
    <script src="js/blag_parser.js"></script>
  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

	<?php
		
		require('includes/user.php');

		function checkMode($type) {

			global $unamesub;

			if ($_SESSION['mode'] === 'admin') {

				?>
					<script type="text/javascript">
					$('.header').remove();
					$('.homebtn').mouseenter(function() {
						$(this).addClass('animated bounce');
					});
					</script>
					<div class="header-admin">
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
							<a href="/blag/admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
							<a href="/blag/edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
							<span class='msg-welcome'>Heyo, <?php echo $_SESSION['username']; ?>!</span>
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
				//echo "pagemode = user<br> ";
			} else {
				//echo 'fail ';
			}

			if ($type == 'login') {
				//echo 'type = login<br> ';
			} else if ($type == 'init') {
				//echo 'type = init<br> ';
			} else {

			}
		}

		checkMode('init');
		//echo '<br>Name: ' . $uname. " <br>PassSHA1: ".$upass;

		//display the greeting post
		echo '<div class="blag-body">
				<h2> '. $greeting .'</h2>
				<h4>' . $greetingContent . '</h4>
			  </div>';

		$json = file_get_contents("pages/posts.json");

		$jsonIterator = new RecursiveIteratorIterator(
	    new RecursiveArrayIterator(json_decode($json, TRUE)),
	    RecursiveIteratorIterator::SELF_FIRST);

	    if (!isset($_GET['p'])) {
	    	$pageiterator = 0;
	    	$pagenumber = 1;
	    }

		if (isset($_GET['p'])) {
			$pagenumber = (int)$_GET['p'];
			$pageiterator = (int)$_GET['p'];
		}

		foreach ($jsonIterator as $key => $val) {

			//if ($pageiterator > $pagenumber + (4) && $pageiterator < $pagenumber + (10 * 4)) {
			
			    if(is_array($val)) {
			        /*echo "</div><div class='blag-body'>
			        	  <h3>$key</h3><br>";*/
			    } else {
			    	if ($key == 'title') {
			    		echo "<div class='blag-body'><h3>$val</h3>";
			    	}
			    	if ($key == 'content') {
			    		echo "<p>$val</p>";
			    	}
			    	if ($key == 'date') {
			       		echo "<span class='timestamp'>$val</span><br></div>";
			    	}
			    }
			//}

			//$pageiterator++;
			//echo $pageiterator; //why is this all weird

		}

	?>

<!-- Modal -->
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
				      <input class="editpage-content" name="unamesub" type="text" id="uname" value="" placeholder="Username"> 
				  </p>
				    <p>
				      <input class="editpage-content" name="upasssub" type="password" id="upass" placeholder="Password"> 
				  </p>
				  <!-- <input type="submit" name="Login" value="Login"> -->
		      </div>
		      <div class="modal-footer">
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

 <!--    This thing is to make the ugly stuff into pretty stuff -->
    <script>//parseBlag();</script>
    <!-- Markdown parsy thing -->
    <!--<script src="http://strapdownjs.com/v/0.2/strapdown.js"></script>-->

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
