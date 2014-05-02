<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blag Test - Admin</title>
    <?php
    	include_once('/includes/includes.php');
    	include_once('/includes/paths.php');
    ?>
  </head>
<body>

<ul class="posts-container-admin">
	<?php
		//Read from the pages file to get contents because an efficient database is too efficient.

		$file = 'pages/posts.json';

		$json = file_get_contents($file);

		$jsonIterator = new RecursiveIteratorIterator(
	    new RecursiveArrayIterator(json_decode($json, TRUE)),
	    RecursiveIteratorIterator::SELF_FIRST);

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

		//unset($jsonIterator['Test']);
		//file_put_contents($file, json_encode($jsonIterator));
	?>
</ul>

<div class="main-container-admin">

</div>

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
