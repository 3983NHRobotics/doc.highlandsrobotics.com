<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blag Test</title>
        <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">

  <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/blag-light.css">

  <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/blag.js"></script>
    <script src="../js/blag_parser.js"></script>

  </head>
<body>
<div class="alert-error" id="errordiv">
    Error goes here
  </div>
<?php

include('../includes/user.php');

if (isset($_POST["Submit"])) {

$string = '<?php 

$uname = "' . sha1($_POST["uname"]) . '";

$upass = "' . sha1(md5(md5($_POST["upass"]))) . '";

$installed = true;

?>';

    if (!isset($installed)) {

        $fp = fopen("../includes/user.php", "w");

        fwrite($fp, $string);

        fclose($fp);

    } else {
        echo "Blag is already installed.";
    }
}

?>

<div class="blag-body">
  <form action="" method="post" name="install" id="install">
    <p>
      <label class="loginpage-content-title"><i class="fa fa-user"></i> Set a username.</label>
      <input class="loginpage-content" name="uname" type="text" id="uname" value=""> 
  </p>
    <p>
      <label class="loginpage-content-title"><i class="fa fa-lock"></i>Set a password.</label>
      <input class="loginpage-content" name="upass" type="password" id="upass"> 
  </p>
    <p>
      <button class="btn btn-submit" type="submit" name="Submit" value="Install">Install</button>
    </p>
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
