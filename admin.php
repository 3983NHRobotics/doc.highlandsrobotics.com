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
    <title>Blag Test - Admin</title>

        <?php
    	include('/includes/paths.php');
    	require ('/includes/config.php');

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
		<span class='msg-welcome'>Heyo, <?php echo $_SESSION['username']; ?>!</span>
	</span>
</div>	


	<?php
		//Read from the pages file to get contents because an efficient database is too efficient.

	if ($_SESSION['mode'] === 'admin') {

		/*
		$file = 'pages/posts.json';

		$json = file_get_contents($file);

		$jsonIterator = new RecursiveIteratorIterator(
	    new RecursiveArrayIterator(json_decode($json, TRUE)),
	    RecursiveIteratorIterator::SELF_FIRST);

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

		} 
		*/

		if(isset($_POST['savesettings'])) {

			if(!isset($_POST['usetinymce'])) {
				$_POST['usetinymce'] = false;
			}
			if(!isset($_POST['usepace'])) {
				$_POST['usepace'] = false;
			}

			$settings = '<?php
			$theme = "' . $_POST['theme'] . '";
			$title = "' . $_POST['sitename'] . '";
			$greeting = "' . $_POST['sitegreeting'] . '";
			$greetingContent = "' . $_POST['sitegreeting-content'] . '";
			$usetinymce = "' . $_POST['usetinymce'] . '";
			$usepace = "' . $_POST['usepace'] . '";
			?>';

			$fp = fopen("includes/config.php", "w");
        	fwrite($fp, $settings);
        	fclose($fp);

        	$_SESSION['theme'] = $_POST['theme'];

        	header('Location: ' . $_SERVER['REQUEST_URI']);
		}

		require('includes/config.php');

		?>

<div class="main-container-admin">
	<form action="" method="post" name="savesettings" id="savesettings">
<!-- Site greeting, name and stuff like that -->
		<div class="admin-control admin-control-style">
		<div class="ac-title">Titles:</div>

		<label class="acc-title">Site Name:</label>
		<input class="acc-content" name="sitename" id="sitename" type="text" value="<?php echo $title; ?>">

		</div>
		<div class="admin-control admin-control-sitedata">
		<div class="ac-title">Greeting post:</div>

		<label class="acc-title">Site greeting:</label>
		<input class="acc-content" name="sitegreeting" id="sitegreeting" type="text" value="<?php echo $greeting; ?>">
		<label class="acc-title">Site greeting content:</label>
		<textarea style="width: 95%" class="acc-content" name="sitegreeting-content" id="sitegreeting-content" type="text"><?php echo $greetingContent; ?></textarea>

		</div>
		<div class="admin-control admin-control-settings-color">
		<div class="ac-title">Color settings:</div>
<!-- Stylesheet settings -->
		<ul style="margin-left: -25px;">
		<li><input class="acc-radio" type="radio" name="theme" value="light" size="17" <?php echo ($theme=='light')?'checked':'' ?>><span class="acc-content">Light stylesheet</span>
		<li><input class="acc-radio" type="radio" name="theme" value="gray" size="17" <?php echo ($theme=='gray')?'checked':'' ?>><span class="acc-content">Gray stylesheet</span>
		<li><input class="acc-radio" type="radio" name="theme" value="dark" size="17" <?php echo ($theme=='dark')?'checked':'' ?>><span class="acc-content">Dark stylesheet</span>
		<li><input class="acc-radio" type="radio" name="theme" value="custom" size="17" <?php echo ($theme=='custom')?'checked':'' ?>><span class="acc-content">Custom stylesheet</span>
		</ul>

		</div>

		<div class="admin-control admin-control-settings-includes">
		<div class="ac-title">Main settings:</div>
<!-- Main site settings -->
		<ul style="margin-left: -25px;">
		<li><input class="acc-checkbox" type="checkbox" name="usetinymce" value="true" size="17" <?php echo ($usetinymce==true)?'checked':'' ?>><span class="acc-content">Use TinyMCE for edit page?</span>
		<li><input class="acc-checkbox" type="checkbox" name="usepace" value="true" size="17" <?php echo ($usepace==true)?'checked':'' ?>><span class="acc-content">Use PACE for pageload animations?</span>
		</ul>

		</div>
<!-- Site reset -->
		<div class="admin-control admin-control-reset">
		<div class="ac-title">Site Reset</div>
		</div>
<!-- Save settings -->
		<div class="admin-control admin-control-save">
		<div class="ac-title">Save settings</div>
		<button type="submit" name="savesettings" class="btn btn-submit" style="margin-left: 10px">Save</button>
		</div>
	</form>
</div>

	<form action="login.php" method="post" name="logout" id="logout">
	<input type="hidden" value="logout">
	</form>

		<?php
	} else { 
		//If user is not logged in and is not admin
		header('Location: ' . dirname($_SERVER['REQUEST_URI']));
		die();

	}
	?>

</body>
</html>
