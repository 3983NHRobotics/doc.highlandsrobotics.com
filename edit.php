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
    <title>The Blag - Edit</title>
        <?php
    	require ('/includes/config.php');
    	require('/includes/user.php');
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
	<link rel="stylesheet" href="css/font-awesome.min.css">

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
    input#title {
    	font-size: 30px;
    }
    input[type='radio'] {
    	display: none;
    }
    input[type='radio'] + label {
    	border: rgba(0,0,0,.4) 1px solid;
    	border-radius: 33px;
    	background: rgba(0,0,0,.2);
    	transition: all .2s ease;
    	-webkit-transition: all .3s ease;
    	margin: 0px 5px 0px 5px;
    	padding: 5px;
    	width: 50px;
    	text-align: center;
	}
	input[type='radio']:checked + label {
		background: #d4a017;
		color: #c04000;
	}
    </style>
  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

	<?php
	#   Sanitizer function - removes forbidden tags, including script tags
		function strip_tags_attributes( $str, 
		    $allowedTags = array('<a>','<b>','<blockquote>','<br>','<cite>','<code>','<del>','<div>','<em>','<ul>','<ol>','<li>','<dl>','<dt>','<dd>','<img>','<video>','<iframe>','<ins>','<u>','<q>','<h3>','<h4>','<h5>','<h6>','<samp>','<strong>','<sub>','<sup>','<p>','<table>','<tr>','<td>','<th>','<pre>','<span>'), 
		    $disabledEvents = array('onclick','ondblclick','onkeydown','onkeypress','onkeyup','onload','onmousedown','onmousemove','onmouseout','onmouseover','onmouseup','onunload') )
		{       
		    if( empty($disabledEvents) ) {
		        return strip_tags($str, implode('', $allowedTags));
		    }
		    return preg_replace('/<(.*?)>/ies', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $disabledEvents) . ")=[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($str, implode('', $allowedTags)));
		}

		if($_SESSION['mode'] === 'admin') {
			$postid = 0;

			$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
		        if (mysqli_connect_errno()) {
		        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
		        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
		        }

			if(isset($_POST["Submit"])) {
				//Change this so that apostraphes and stuff can be used
				$title = addslashes(strip_tags_attributes($_POST["title"]));
				$content = addslashes(strip_tags_attributes($_POST["content"]));
				$creator = '<a href="user.php?u=' . $_SESSION['user'] . '">' . $_SESSION['username'] . '</a>';
				date_default_timezone_set('America/New_York');
				$timestamp = date("m/d/Y") . ' at ' . date("h:i:s a");
				$tags = addslashes(strip_tags_attributes($_POST['tags']));
				$isNSFW = $_POST['nsfwcheck'];

		        $sql = "INSERT INTO Posts (title, content, creator, timestamp, tags, isNSFW)
                    VALUES ('$title', 
                    '$content', 
                    '$creator',
                    '$timestamp',
                    '$tags',
                    '$isNSFW')";

	            if (!mysqli_query($db,$sql)) {
	                die('Error: ' . mysqli_error($db));
	            }

	            mysqli_close($db);

				header('Location: ' . dirname($_SERVER['REQUEST_URI']));

			}

			if(isset($_POST['Edit'])) {
				if(isset($_POST['type'])) {
					if($_POST['type'] === 'reply') {
						//bla
					}
				} else {
				$newtitle = addslashes(strip_tags_attributes($_POST['title']));
				$newtags = addslashes(strip_tags_attributes($_POST['tags']));
				$isNSFW = $_POST['nsfwcheck'];
				}

				$newcontent = addslashes(strip_tags_attributes($_POST['content']));
				$postid = $_POST['postid'];
				$return_to = $_GET['return_to'];

				$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
				if (mysqli_connect_errno()) {
		        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
		        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
		        }

		        if(!isset($_POST['type'])) {
		        	$sql = "UPDATE Posts SET title='$newtitle', content='$newcontent', tags='$newtags', isNSFW='$isNSFW' WHERE PID=$postid";
		        	$header = dirname($_SERVER['REQUEST_URI']) . '/index.php?p=' . $return_to;
		        } else {
		        	$sql = "UPDATE Replies SET content='$newcontent' WHERE PID=$postid";
		        	$header = dirname($_SERVER['REQUEST_URI']) . '/post.php?reply_to=' . $return_to;
		        }

		        if (!mysqli_query($db,$sql)) {
	                die('Error: ' . mysqli_error($db));
	            }

		        mysqli_close($db);
		        header('Location: ' . $header);
			}
			
		?>

<div class="header-admin">
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" type="submit" name="Logout" class="btn-lock" onclick="document.logout.submit();"><i class="fa fa-lock"></i></a>
							<a href="/blag/user.php?u=<?php echo $_SESSION['user']; ?>" class="btn btn-random"><i class="fa fa-user"></i></a>
							<a href="/blag/admin/admin.php" class="btn btn-random"><i class="fa fa-dashboard"></i></a>
							<a href="/blag/edit.php" class="btn btn-random"><i class="fa fa-pencil"></i></a>
							<span class='msg-welcome'>Heyo, <?php echo strtok($_SESSION['username'], ' '); ?>!</span>
						</span>
					</div>	

<div class="blag-body">
<?php 

	// if($_SESSION['mode'] === 'admin') {
		//if(empty($_POST['Submit'])) {
		if(!isset($_GET['action'])) { ?>
			<form action="" method="post" name="submit" id="submit">

			<p><input class="editpage-content" name="title" id="title" type="text" placeholder="Title">

			<p><textarea class="editpage-content" name="content" id="content" rows="18" cols="60" placeholder="Write stuffs here"></textarea>
			Tags and stuff:
			<p><input class="editpage-content" type="text" placeholder="Separate tags with spaces" value="" name="tags">

			<p><span class="">Tag for mature content?</span>
			<input class="" type="radio" value="1" name="nsfwcheck" id="cb1"><label for="cb1">Yes</label>
			<input class="" type="radio" value="0" name="nsfwcheck" id="cb2" checked><label for="cb2">No</label>
			<p><input class="btn btn-submit" type="submit" name="Submit" value="Post">

			</form>

<?php 		} else if ($_GET['action'] === 'edit') { 

			if(isset($_POST['Entereditcontent'])) {
				$postid = $_POST['postid'];

				if(isset($_POST['type'])) {
					if($_POST['type'] === 'reply') {
						$body = mysqli_query($db,"SELECT * FROM Replies WHERE PID=$postid");
					}
				} else {
					$body = mysqli_query($db,"SELECT * FROM Posts WHERE PID=$postid");
				}

		        while($row = mysqli_fetch_array($body)) {
				  	?>
				  	<form action="" method="post" name="Edit" id="edit">
				  	<input type="hidden" name="postid" value="<?php echo $postid; ?>">
				  	<?php if(!isset($_POST['type'])) { ?>
					<p><input class="editpage-content" name="title" id="title" type="text" placeholder="Title" value="<?php echo $row['title'] ?>">
					<?php } else { ?>
					<input type="hidden" name="type" value="reply">
					<input type="hidden" name="return_to" value="<?php $_GET['return_to'] ?>"
					<?php } ?>
					<p><textarea class="editpage-content" name="content" id="content" rows="18" cols="60" placeholder="Write stuffs here"><?php echo $row['content']; ?></textarea>

					Tags and stuff:
					<p><input class="editpage-content" type="text" placeholder="Separate tags with spaces" value="<?php echo $row['tags']; ?>" name="tags">

					<p><span class="">Tag for mature content?</span>
					<input class="" type="radio" value="1" name="nsfwcheck" id="cb1" <?php echo ($row['isNSFW'] == 1)?'checked':'' ?>><label for="cb1">Yes</label>
					<input class="" type="radio" value="0" name="nsfwcheck" id="cb2" <?php echo ($row['isNSFW'] == 0)?'checked':'' ?>><label for="cb2">No</label>
					<p><input class="btn btn-submit" type="submit" name="Edit" value="Update">

					</form>
				  	<?php
				}
			}
		} 

		?>
</div>

	<form action="login.php" method="post" name="logout" id="logout">
		<input type="hidden" value="logout">
	</form>

	<?php

	if ($usetinymce === 'true') { ?>
	<!-- <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script> -->
	<script type="text/javascript" src="<?php echo dirname($_SERVER['REQUEST_URI']); ?>/includes/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	        tinymce.init({
	        	selector:'textarea#content',
	        	plugins: [
	        		"autolink lists link image preview",
	        		"searchreplace code fullscreen",
	        		"media table paste contextmenu"
	        	],
	        	toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	        });

			setTimeout(function() {
				tinymce.get('content').dom.loadCSS('css/blag-light-tinymce.css');
			}, 500); //delay while tinymce loads

			$('textarea#bio').keydown(function (e){
			    if(e.keyCode == 13){
			        $('textarea#content').val($('textarea#bio').val() + '<br>');
			    }
			});
	</script>
	<?php 
		
		} 

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
