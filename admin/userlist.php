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
    <base target="_blank" />
        <?php
    	require ('../includes/config.php');
    	require('../includes/user.php');

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
	table.table-userlist {

	}
	table.table-userlist tr {
		margin: 1px;
	}
	table.table-userlist td {
		border: rgba(0,0,0,.45) 1px solid;
		padding: 5px;
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
    <script src="../js/jquery.smoothscroll.js"></script>
    <script src="../js/blag.js"></script>
  </head>
<body>

	<div class="alert-error" id="errordiv">
		Error goes here
	</div>

	<?php
	#   Sanitizer function - removes forbidden tags, including script tags

		if($_SESSION['mode'] === 'admin') {
			$postid = 0;

			$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
		        if (mysqli_connect_errno()) {
		        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
		        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
		        }			
		?>

<div class="userlist-body">

<?php
	$sql="SELECT * FROM Users";
	$users = mysqli_query($db, $sql);
	echo '<table class="table-userlist">';
	echo '<tr><td></td><td>Name</td><td>Email</td><td>Display name</td><td>isAdmin</td></tr>';
	while($row = mysqli_fetch_array($users)) {
		$email = $row['email'];
		$size = 50;
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . $grav_default . "&s=" . $size . "r=" . $grav_rating;

		?>
		<tr>
		<td><?php echo '<a href="../user.php?u=' . $row["name"] . '"><img src="' . $grav_url . '" /></a>'; ?></td>
		<td><?php echo $row['name']; ?></td> 
		<td><?php echo $row['email']; ?></td> 
		<td><?php echo $row['disname']; ?></td> 
		<td><?php echo $row['isAdmin']; ?></td>
		</tr>
		<?php
	}
	echo '</table>';
?>
</div>

	<form action="../login.php" method="post" name="logout" id="logout">
		<input type="hidden" value="logout">
	</form>

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
