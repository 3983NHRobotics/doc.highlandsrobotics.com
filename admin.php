<!DOCTYPE html>
<?php

session_start();

?>
<html lang="en">
  <head>
    <title>Blag Test - Admin</title>

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

    <?php
    	include_once('/includes/paths.php');
    ?>
  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

<div class="header-admin">
	<span class="header-content">
		<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
		<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
		<a href="/blag/admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
		<a href="/blag/edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
		<span class='msg-welcome'>Heyo, <?php echo $_SESSION['user']; ?>!</span>
	</span>
</div>	


	<?php
		//Read from the pages file to get contents because an efficient database is too efficient.

	if ($_SESSION['mode'] === 'admin') {

		$file = 'pages/posts.json';

		$json = file_get_contents($file);

		$jsonIterator = new RecursiveIteratorIterator(
	    new RecursiveArrayIterator(json_decode($json, TRUE)),
	    RecursiveIteratorIterator::SELF_FIRST);

				echo '<ul class="posts-container-admin">';
		foreach ($jsonIterator as $key => $val) {
		    if(is_array($val)) {
		        echo "</li><li class='blag-body-admin'>
		        	  <h4>
		        	    $key 
		        	  	<form action='' method='post' name='delete' id='delete'>

						<span style='font-size: 10px !important'>Enter the post name to delete.</span> <input name='title' id='title' type='text'>

						<button type='submit' name='submit' class='btn-delete fa fa-times-circle' style='float: right; font-size: 20px'></button>
						</form>
		        	  
		        	  </h4><br>";
		    } else {
		        echo "$val<br>";
		    }
		}
			echo '</ul>';

		if(isset($_POST["delete"])) {

			echo "</li><li class='blag-body-admin'>" . $title or exit ("FAIL");
			$title = $_POST['title'];
			$array = json_decode($json, TRUE);

			foreach ($array as $key => $val) {
			    if ($key == $title) {
			        unset($array[$key]);
			    }
			}

			file_put_contents($file, json_encode($array));

		} ?>

<!-- <div class="main-container-admin">

</div> -->

	<form action="login.php" method="post" name="logout" id="logout">
	<input type="hidden" value="logout">
	</form>

		<?php
	} else { ?>

	GOWAI

	<?php

	}

		//unset($jsonIterator['Test']);
		//file_put_contents($file, json_encode($jsonIterator));
	?>


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
