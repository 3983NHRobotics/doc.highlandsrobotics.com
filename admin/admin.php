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
    <title>Blag - Admin</title>

        <?php
    	require ('../includes/config.php');
    	echo '<link rel="stylesheet" href="../css/blag-light.css">';
    	echo '<link rel="stylesheet" href="../css/blag-' . $_SESSION['theme'] . '.css">';
    	
    	if ($usepace === 'true') {
    		echo '<link rel="stylesheet" href="../css/pace/pace-centerbar.css">';
    		echo '<script src="../js/pace/pace.min.js"></script>';
    	}
    ?>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">

	<link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Font Awesome -->
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<style type="text/css">
.tab-menu {
	height: auto;
		width: 20%;
		float: left;
		position: relative;
}
.tabs {
    display:inline-block;
}
    .tab-links:after {
        display:block;
        clear:both;
        content:'';
    }
 
    .tab-links li {
        width: 100%;
        float:left;
        list-style:none;
    }
 
        .tab-links a {
            display:inline-block;
            padding: 10px 30px;
            margin-bottom: 1px;
            width: 100%;
            border-radius:0px 5px 5px 0px;
            background:#999;
            font-size:16px;
            font-weight:600;
            color:#222;
            transition:all linear 0.15s;
        }
 
        .tab-links a:hover {
            background:#ccc;
            text-decoration:none;
        }
 
    li.active a {
        background:#4e8975;
        color:222;
    } 
    li.active a:hover {
    	background: #78866b;
    }
        .tab {
            display:none;
        }
 
        .tab.active {
            display:block;
        }
    .btn-footer-right {
    	float: right;
    	position: relative;
    	margin: 3px 3px 2px 0px;
    	padding: 3px 10px;
    }
    #grav_list > li, #grav_list_2 > li {
    	position: relative;
    	float: left;
    	margin: 10px;
    }
    ul#grav_list input[type='radio'], ul#grav_list_2 input[type='radio'] {
    	display: none;
    }
    ul#grav_list input[type='radio'] + label {
    	border: #fff 2px solid;
    	border-radius: 30px;
	}
	ul#grav_list_2 input[type='radio'] + label {
		border: rgba(0,0,0,.4) 2px solid;
	}
	ul#grav_list input[type='radio']:checked + label, ul#grav_list_2 input[type='radio']:checked + label {
	    border: #0f0 2px solid;
	}
	ul#grav_list input[type='radio'] + label > img, ul#grav_list_2 input[type='radio'] + label > img {
		border-radius: 30px;
	}
	</style>

<?php 
	if($localcode) {
    echo '<script src="../js/jquery.min.js"></script>';
	} else {
    echo '<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>';
	}
	?>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/blag.js"></script>

  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

<div class="header-admin" style="margin-bottom:5px">
						<span class="header-content">
							<a href="../index.php" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
							<a href="../user.php?u=<?php echo $_SESSION['user']; ?>" class="btn btn-random"><i class="fa fa-user"></i></a>
							<a href="admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
							<a href="../edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
							<span class='msg-welcome'>Heyo, <?php echo strtok($_SESSION['username'], ' '); ?>!</span>
						</span>
					</div>

	<?php
		//Read from the pages file to get contents because an efficient database is too efficient.

	if ($_SESSION['mode'] === 'admin') {

		if(isset($_POST['savesettings'])) {

			if(!isset($_POST['usetinymce'])) {
				$_POST['usetinymce'] = 'false';
			}
			if(!isset($_POST['usepace'])) {
				$_POST['usepace'] = 'false';
			}

			$settings = '<?php
			$theme = "' . $_POST['theme'] . '";
			$title = "' . addslashes($_POST['sitename']) . '";
			$greeting = "' . addslashes($_POST['sitegreeting']) . '";
			$greetingContent = "' . addslashes($_POST['sitegreeting-content']) . '";
			$usetinymce = "' . $_POST['usetinymce'] . '";
			$usepace = "' . $_POST['usepace'] . '";
			$localcode = "' . $_POST['uselocalcode'] . '";
			$grav_default = "' . $_POST['grav_default'] . '";
			$grav_rating = "' . $_POST['grav_rating'] . '";
			';

			$fp = fopen("../includes/config.php", "w");
        	fwrite($fp, $settings);
        	fclose($fp);

        	$_SESSION['theme'] = $_POST['theme'];

        	header('Location: ' . $_SERVER['REQUEST_URI']);
		}

		require('../includes/config.php');

		?>
<section class="tabs tab-menu">
	<ul class="tab-links">
		<li class=""><a href="" onclick="$('#savesettingsbutton').click()">Save settings</a></li>
		<li class="active"><a href="#tab-titles">Titles</a></li>
		<li class=""><a href="#tab-colors">Colors</a></li>
		<li class=""><a href="#tab-includes">Includes</a></li>
		<li class=""><a href="#tab-display">Display</a></li>
		<li class=""><a href="#tab-gravatar">Gravatar</a></li>
		<li class=""><a href="#tab-users">User list</a></li>
	</ul>
</section>
<div class="main-container-admin tabs">

	<form action="" class="tab-content" method="post" name="savesettings" id="savesettings">
<!-- Site greeting, name and stuff like that -->
		<div class="admin-control admin-control-style tab active" id="tab-titles">
		<div class="ac-title">Titles:</div>

		<label class="acc-title">Site Name:</label>
		<input class="acc-content" name="sitename" id="sitename" type="text" value="<?php echo $title; ?>">

		<label class="acc-title">Site greeting:</label>
		<input class="acc-content" name="sitegreeting" id="sitegreeting" type="text" value="<?php echo $greeting; ?>">
		<label class="acc-title">Site greeting content:</label>
		<textarea style="width: 95%" class="acc-content" name="sitegreeting-content" id="sitegreeting-content" type="text"><?php echo $greetingContent; ?></textarea>

		</div>
		<div class="admin-control admin-control-settings-color tab" id="tab-colors">
		<div class="ac-title">Color settings:</div>
<!-- Stylesheet settings -->
		<ul class="acc-options-list">
		<li><input class="acc-radio" type="radio" name="theme" value="light" size="17" <?php echo ($theme=='light')?'checked':'' ?>><span class="acc-content">Light stylesheet</span>
		<li><input class="acc-radio" type="radio" name="theme" value="fancy" size="17" <?php echo ($theme=='fancy')?'checked':'' ?>><span class="acc-content">Fancy stylesheet</span>
		<li><input class="acc-radio" type="radio" name="theme" value="dark" size="17" <?php echo ($theme=='dark')?'checked':'' ?>><span class="acc-content">Dark stylesheet</span>
		<li><input class="acc-radio" type="radio" name="theme" value="custom" size="17" <?php echo ($theme=='custom')?'checked':'' ?>><span class="acc-content">Custom stylesheet</span>
		</ul>

		</div>

<!-- Main site settings -->
		<div class="admin-control admin-control-settings-includes tab" id="tab-includes">
		<div class="ac-title">Include-y stuff:</div>
		<ul class="acc-options-list">
		<li><input class="acc-checkbox" type="checkbox" name="usetinymce" value="true" size="17" <?php echo ($usetinymce=='true')?'checked':'' ?>><span class="acc-content">Use TinyMCE for edit page?</span>
		<li><input class="acc-checkbox" type="checkbox" name="usepace" value="true" size="17" <?php echo ($usepace=='true')?'checked':'' ?>><span class="acc-content">Use PACE for pageload animations?</span>
		<li><input class="acc-checkbox" type="checkbox" name="uselocalcode" value="true" size="17" <?php echo ($localcode=='true')?'checked':'' ?>><span class="acc-content">Use only local files?</span>
		</ul>

		</div>
<!-- Site reset -->
		<div class="admin-control admin-control-reset tab" id="tab-display">
		<div class="ac-title">Display settings:</div>
		<ul class="acc-options-list">
		<li><input class="acc-checkbox" type="checkbox" value="true" size="17"><span class="acc-content">Display stuff?</span>
		</ul>
		</div>
<!-- Gravatar stuff -->
		<div class="admin-control admin-control-gravatar tab" id="tab-gravatar">
		<div class="ac-title">Gravatar settings:</div>
		<span class="acc-title">Default gravatar</span>
		<ul class="acc-options-list" id="grav_list">
		<!-- <li><input class="acc-radio" type="radio" name="grav_default" value="404" size="17" <?php echo ($grav_default=='404')?'checked':'' ?>><label class="acc-content"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=404&f=y" /></label> -->
		<li><input class="acc-radio" type="radio" name="grav_default" value="mm" id="mm" size="17" <?php echo ($grav_default=='mm')?'checked':'' ?>><label class="" for="mm"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&f=y"/></label>
		<li><input class="acc-radio" type="radio" name="grav_default" value="identicon" id="identicon" size="17" <?php echo ($grav_default=='identicon')?'checked':'' ?>><label class="" for="identicon"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=identicon&f=y"/></label>
		<li><input class="acc-radio" type="radio" name="grav_default" value="monsterid" id="monsterid" size="17" <?php echo ($grav_default=='monsterid')?'checked':'' ?>><label class="" for="monsterid"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=monsterid&f=y"/></label>
		<li><input class="acc-radio" type="radio" name="grav_default" value="wavatar" id="wavatar" size="17" <?php echo ($grav_default=='wavatar')?'checked':'' ?>><label class="" for="wavatar"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=wavatar&f=y"/></label>
		<li><input class="acc-radio" type="radio" name="grav_default" value="retro" id="retro" size="17" <?php echo ($grav_default=='retro')?'checked':'' ?>><label class="" for="retro"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=retro&f=y"/></label>
		<li><input class="acc-radio" type="radio" name="grav_default" value="custom" id="custom" size="17" <?php echo ($grav_default=='custom')?'checked':'' ?>><label class="">Custom:</label>
		<input type="text" name="grav_custom_url" value="" placeholder="Enter image url:" />
		</ul>
		<br><br><br><br><br><br> <!-- SO UGLY -->
		<span class="acc-title">Allowed gravatars</span>
		<ul class="acc-options-list" id="grav_list_2">
		<li><input class="acc-radio" type="radio" name="grav_rating" value="g" id="g" size="17" <?php echo ($grav_rating=='g')?'checked':'' ?>><label class="" for="g"><img src="http://s.gravatar.com/images/gravatars/ratings/0.gif?121" /></label>
		<li><input class="acc-radio" type="radio" name="grav_rating" value="pg" id="pg" size="17" <?php echo ($grav_rating=='pg')?'checked':'' ?>><label class="" for="pg"><img src="http://s.gravatar.com/images/gravatars/ratings/1.gif?121" /></label>
		<li><input class="acc-radio" type="radio" name="grav_rating" value="r" id="r" size="17" <?php echo ($grav_rating=='r')?'checked':'' ?>><label class="" for="r"><img src="http://s.gravatar.com/images/gravatars/ratings/2.gif?121" /></label>
		<li><input class="acc-radio" type="radio" name="grav_rating" value="x" id="x" size="17" <?php echo ($grav_rating=='x')?'checked':'' ?>><label class="" for="x"><img src="http://s.gravatar.com/images/gravatars/ratings/3.gif?121" /></label>
		</ul>
		</ul>
		</div>
	<div class="admin-control admin-control-users tab" id="tab-users" style="padding: 0px;">
	<iframe src="userlist.php" style="width: 100%; height: 100%; margin: 0px; border:none;"></iframe>
	</div>
<!-- Save settings -->

		<button type="submit" id="savesettingsbutton" name="savesettings" class="btn btn-submit" style="display:none"></button>
	</form>
</div>

<!-- <div class="footer" style="bottom:0px;position:absolute">
		<div class="pagn" style="float:left;margin-top:3px">&copy; 2014 Theodore Kluge</div>
		<button class="btn btn-submit btn-footer-right" style="" onclick="$('#savesettingsbutton').click()">Save</button>
	</div>
 -->
	<form action="../login.php" method="post" name="logout" id="logout">
	<input type="hidden" value="logout">
	</form>

		<?php
	} else { 
		//If user is not logged in and is not admin
		header('Location: ' . dirname($_SERVER['REQUEST_URI']));
		die();

	}
	?>

	<script type="text/javascript">
		$(document).ready(function() {
    	$('.tabs .tab-links a').on('click', function(e)  {
    		console.log('click!');
        var currentAttrValue = $(this).attr('href');

        // Show/Hide Tabs
        $('.tabs ' + currentAttrValue).show().siblings().hide();

        // Change/remove current tab to active
        $(this).parent('li').addClass('active').siblings().removeClass('active');

        e.preventDefault();
    });
});
	</script>

</body>
</html>
