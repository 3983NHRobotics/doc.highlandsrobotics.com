<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blag Test - Edit</title>
    <?php
    	include_once('/includes/includes.php');
    	include_once('/includes/paths.php');
    ?>
  </head>
<body>

	<?php
		
		if(isset($_POST["Submit"])) {
			$title = $_POST["title"];
			$content_1 = $_POST["content"];
			$type = $_POST["posttype"];
			//$content_2 = "JSON file edit test";

			if ($title == '') {
				echo "Missing title.";
			} else if ($content_1 == '') {
				echo "Missing content.";
			} else {
			
			$file = "pages/posts.json";

			$json = json_decode(file_get_contents($file), true) or exit ("FAILED");

			$json[$title] = array("content" => $content_1, "type" => $type);

			file_put_contents($file, json_encode($json));

			}
		}

	?>

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
