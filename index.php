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
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$starttime = $time;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $siteTitle ?></title>
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

    	if($localcode) {
    echo '<script src="js/jquery.min.js"></script>';
	} else {
    echo '<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>';
	}
    ?>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">

    <style type="text/css">
    	.maturecontent-wrapper {
    		width: 100%;
    	}
    	.maturecontent-hidden {
    		display: none;
    	}
    	p.maturecontent-warning {
    		text-align: center;
    		margin-left: auto;
    		margin-right: auto;
    		position: relative;
    	}
    	button.maturecontent-warning {
    		height: 40px;
    		position: relative;
    		border: #aaa 1px solid;
    	}
    </style>
  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>
	<div id="wrapper">

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
							<a href="index.php" class="btn homebtn"><i class="fa fa-home"></i></a>
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

		checkMode('init');

		if (!empty($_GET['p'])) {
			$pagenumber = mysqli_real_escape_string($db, $_GET['p']);
		} else {
			$_GET['p'] = '1';
			$pagenumber = '1';
		}
		//display the greeting post
		if ($_GET['p'] == '1') {
			echo '<div class="blag-body">
					<h2 style="text-align:center"> '. $greeting .'</h2>
					<h4 style="text-align:center">' . $greetingContent . '</h4>
				  </div>';
		}

		//$postsPerPage = 20;
		$start_page = ($pagenumber - 1) * $postsPerPage;
		$body = mysqli_query($db,"SELECT * FROM Posts ORDER BY PID DESC LIMIT $start_page,$postsPerPage"); //This works!

		date_default_timezone_set('America/New_York'); //set timezone
		try {
			$date1 = new DateTime($_SESSION['age']); //compare age from database with current time
			$date2 = new DateTime();
			$interval = $date1->diff($date2);
			$age = $interval->y;
			//echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
		} catch (Exception $e){
			//do nothing :D
		}

		while($row = mysqli_fetch_array($body)) {
		    echo '<div class="blag-body">';
			echo '<u><a href="post.php?reply_to=' . $row['PID'] . '"><h3>' . $row['title'] . '</h3></a></u>';
			/*if ($row['isNSFW'] == 1) {
				//echo 'isNSFW = true;<br>';
				if ($_SESSION['mode'] == 'user') { //hide post
					echo '<p class="maturecontent-warning">please <a href="" data-toggle="modal" data-target="#myModal">log in</a> to view this post</p>';
				} else if ($age < 18) { //hide post
					echo '<p class="maturecontent-warning">This has been tagged as NSFW</p>';
				} else if ($age >= 18 && $filterPref == 1) { //show button
					echo '<p class="maturecontent-warning">Mature content filter is on.</p>
							<p class="maturecontent-warning"><button class="btn btn-submit maturecontent-warning" onClick="showHidden('.$row['PID'].')">Show anyway</button></p>';
					echo '<div class="maturecontent-hidden" id="maturecontent-hidden-' . $row['PID'] . '">' . $row['content'] . '</div>';
				} else if ($age >= 18 && $filterPref == 0) { //show post
					echo '<p>' . $row['content'] . '</p>';
				}
			} else {*/
				echo '<p>' . $row['content'] . '</p>';
			//}

			echo '<span class="timestamp">Posted by '. $row['creator'] . ' - ' . $row['timestamp'] . '</span>';

			if ($_SESSION['mode'] == 'admin') {
				echo '<div class="editdelete">';
				echo '<form action="dbquery.php" method="post" name="deletepost" id="delpost' . $row['PID'] . '">
				<input type="hidden" name="postid" value="' . $row["PID"] . '">
				<button type="submit" value="Delete" name="deletepost" class="editdelbtn" onClick="return confirm(\'Are you sure you want to delete this post?\')">Delete</button>
				</form>';
				echo '<form action="edit.php?action=edit&return_to=' . $_GET["p"] . '" method="post" name="Entereditcontent" id="editpost' . $row['PID'] . '">
				<input type="hidden" name="postid" value="' . $row["PID"] . '">
				<button type="submit" value="Edit" name="Entereditcontent" class="editdelbtn">Edit</button>
				</form>';
				echo'</div>';
			} else if ($_SESSION['mode'] == 'loggeduser') {
			  	//reply button type stuff goes here
			}

			echo '</div>';
		}

		$pages = mysqli_query($db, "SELECT COUNT(*) FROM Posts");
		$row = mysqli_fetch_row($pages);
		$total_things = $row[0];
		$total_pages = ceil($total_things / $postsPerPage); //gets the number of pages for pagination
	}
	?>
	<div class="footer">
		<div class="pagn" style="float:left">Made with <span class="pink">&#9829;</span> by Theodore Kluge</div>
		<div class="pagn">
			Pages: 
			<?php
				for ($i = 1; $i <= $total_pages; $i++) { 
	            	echo "<a class='pagnbtn' href='" . dirname($_SERVER['SCRIPT_NAME']) . "/?p=" . $i . "'>" . $i . "</a> "; 
				}
			?>
		</div>
	</div>
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

<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-warning"></i> Confirm deletion</h4>
		      </div>
		      <div class="modal-body">
				    <p>
				      Are you sure you want to delete this post? 
				  </p>
		      </div>
		      <div class="modal-footer">
		        <button id="delSubBtn" data-dismiss="modal" value="Delete" name="deletepost" class="btn btn-submit" onClick="document.getElementById('delpost').submit()">Delete</button>
		      </div>
    </div>
  </div>
</div>

	<form action="login.php" method="post" name="logout" id="logout">
		<input type="hidden" value="logout">
	</form>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.smoothscroll.js"></script>
    <script src="js/blag.js"></script>
	<!--<script src="js/jquery.stellar.js"></script>-->
	<script type="text/javascript">
	var docHeight = $(document).height();
	var image_url = $('body').css('background-image'), image;

	image_url = image_url.match(/^url\("?(.+?)"?\)$/);

	if (image_url[1]) {
	    image_url = image_url[1];
	    image = new Image();

	    $(image).load(function () {
	        console.log(image.width + 'x' + image.height);
	        console.log("docHeight: " + docHeight);
			var dsbr = (image.height / docHeight) / 10;
			console.log("dsbr: " + dsbr);
			$('body').attr('data-stellar-background-ratio', dsbr);
			$.stellar();
	    });

	    image.src = image_url;
	}

	function updateDelModal(pid) {
		//$('#delSubBtn').attr('onClick', '$("#delpost' + pid + '").submit()');
		//$('#delSubBtn').attr('onClick', 'document.getElementById("delpost' + pid + '").submit(); console.log(\'boop\')');
		$('#delSubBtn').attr('onClick', 'document.getElementById("delpost' + pid + '").submit()');
		$('#delModal').modal();
	}

	function confirmDelete() {
		if(confirm("Are you sure you want to delete this post?")) {
			return true;
		} else {
			return false;
		}
		
	}
	function showHidden(pid) {
		$('#maturecontent-hidden-' + pid).css('display', 'block');
		$('.maturecontent-warning').css('display','none');
	}

	$('pre code').each(function(i, block) {
          hljs.highlightBlock(block);
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
	<?php
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finishtime = $time;
	$total_time = round(($finishtime - $starttime), 4);
	if ($showpageloadtime) {
		echo '<script type="text/javascript">$(".footer").append("page generated in ' . $total_time . ' seconds.");</script>';
	}
	?>

</body>
</html>
