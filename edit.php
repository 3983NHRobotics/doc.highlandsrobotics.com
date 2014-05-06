<?php
session_start();

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
    <title>Blag Test - Edit</title>
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

	<?php
		if($_SESSION['mode'] === 'admin') {
			if(isset($_POST["Submit"])) {
				$title = $_POST["title"];
				$content_1 = $_POST["content"];
				//$type = $_POST["posttype"];
				$postdate = date("m/d/Y") . ' at ' . date('h:i:s a');
				//$content_2 = "JSON file edit test";

				if ($title == '') {
					echo "<script type='text/javascript'>displayLoginError('error', 'Missing title')</script>";
				} else if ($content_1 == '') {
					echo "<script type='text/javascript'>displayLoginError('error', 'Missing content')</script>";
				} else {
				
				$file = "pages/posts.json";

				$json = json_decode(file_get_contents($file), true) or exit ("FAILED");
				//$arrayToAdd[$title] = array("content" => $content_1, "date" => $postdate);
				$arrayToAdd = array("title" => $title, "content" => $content_1, "date" => $postdate);
				array_unshift($json, $arrayToAdd);
				//array_merge($arrayToAdd[$title], $json);
				//$json = $arrayToAdd[$title] + $json;
				//echo json_encode($json);

				file_put_contents($file, json_encode($json));

				header('Location: ' . dirname($_SERVER['REQUEST_URI']));

				}
			} ?>

<div class="header-admin">
	<span class="header-content">
		<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
		<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
		<a href="/blag/admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
		<a href="/blag/edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
		<span class='msg-welcome'>Heyo, <?php echo $_SESSION['username']; ?>!</span>
	</span>
</div>			

<div class="blag-body">

		<form action="" method="post" name="submit" id="submit">

		<p><input class="editpage-content" name="title" id="title" type="text" placeholder="Title">

		<p><textarea class="editpage-content" name="content" id="content" rows="6" cols="60" placeholder="Write stuffs here"></textarea>

		<!--<p><span>Post type:</span>
		<select name="posttype">
		  <option value="posted">Posted</option>
		  <option value="private">Private</option>
		  <option value="draft">Draft</option>
		</select>-->

		<p><input class="btn btn-submit" type="submit" name="Submit" value="Post">

		</form>

	</div>

	<form action="login.php" method="post" name="logout" id="logout">
		<input type="hidden" value="logout">
	</form>

	<?php

	require ('/includes/config.php');

	if ($usetinymce) { ?>
	<!-- <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script> -->
	<script type="text/javascript" src="<?php echo dirname($_SERVER['REQUEST_URI']); ?>/includes/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	        tinymce.init({
	        	selector:'textarea#content',
	        	plugins: [
	        		"autolink lists link image preview anchor",
	        		"searchreplace code fullscreen",
	        		"media table paste contextmenu"
	        	],
	        	toolbar: "undo redo | cold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	        });
	</script>
	<?php } ?>

			<?php

		} else {
			//return to index page if user not logged in
			header('Location: ' . dirname($_SERVER['REQUEST_URI']));
			die();
		} 

		?>
	

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
