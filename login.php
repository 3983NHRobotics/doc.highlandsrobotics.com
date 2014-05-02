<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blag Test</title>
    <?php
    	include('/includes/includes.php');
    	include('/includes/paths.php');
    ?>
<script type="text/javascript">
clearHeader = function() {
	$('.header').html('');
	console.log('Form Submitted');
}
</script>
  </head>
<body>


<div class="header">
	<?php
		$pagemode = 'user';
		
		require('includes/user.php');

		function checkMode($type) {
			//echo 'Checked page mode ';
			global $pagemode;

			if ($pagemode === 'admin') {

				?>
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<button href="#" class="btn-lock"><i class="fa fa-lock"></i></button>
							<span>Welcome, <?php echo $unamesub; ?>!</span>
						</span>
				<?php
				//echo "pagemode = admin<br> ";
			} else if ($pagemode === 'user') {
				?>
						<span class="header-content">
							<a href="/blag" class="btn homebtn"><i class="fa fa-home"></i></a>
							<a href="#" class="btn-unlock" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt"></i></a>
						</span>
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
	?>
</div>
	<?php

		if(isset($_POST['Login'])) {
			$unamesub = $_POST['unamesub'];
			$upassSHA = sha1($_POST['upasssub']);

			if ($unamesub == $uname) {
				if ($upassSHA == $upass) {
					echo 'success';
					$pagemode = 'admin';
					checkMode('login');
				} else {
					echo 'incorrect password';
				}
			} else {
				echo 'incorrect username';
			}
		}

	?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
		  <form action="" method="post" name="login" id="login" onsubmit="clearHeader();">
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
