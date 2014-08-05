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

  </head>
<body>
<div class="alert-error" id="errordiv">
    Error goes here
  </div>
<?php

include('../includes/user.php');

if (isset($_POST["Submit"])) {

$string = '<?php 

$dbuname = "' . $_POST['dbuname'] . '";

$dbupass = "' . $_POST['dbupass'] . '";

$dbhost = "' . $_POST['dbhost'] . '";

$dbname = "' . $_POST['dbname'] . '";

//$installed = true;

?>';

    if (!isset($installed)) {

        $fp = fopen("../includes/user.php", "w");

        fwrite($fp, $string);

        fclose($fp);

        $db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);
        if (mysqli_connect_errno()) {
        //echo "Failed to connect to MySQL: " . mysqli_connect_error();
        echo "<script type='text/javascript'>displayLoginError('error', 'MySQL conn failed: " . mysqli_connect_error() . "')</script>";
        } else {
            //Create MySQL table for users
            $sql = 'CREATE TABLE Users(
                PID INT NOT NULL AUTO_INCREMENT, 
                PRIMARY KEY(PID),
                name VARCHAR(50), 
                pass VARCHAR(512), 
                email VARCHAR(50),
                disname VARCHAR(50),
                age INT)';
            $age = htmlentities($_POST['uage']);
            $uname = ($_POST['uname']);
            $options = [
                'cost' => 11,
            ];

            $upass = password_hash($_POST['upass'], PASSWORD_BCRYPT, $options);
            //$upass = sha2($_POST['upass'], 512);
            $default = 'not set';

            // Execute query
            if (mysqli_query($db,$sql)) {
              echo "Table Users created successfully";
            } else {
              echo "Error creating table: " . mysqli_error($db);
            }

            $sql = "INSERT INTO Users (name, pass, email, disname, age)
                    VALUES ('$uname', 
                    '$upass',
                    '$default',
                    '$default',
                    '$default')";

            if (!mysqli_query($db,$sql)) {
                die('Error: ' . mysqli_error($db));
            }

            //Create MySQL table for posts
            $sql = 'CREATE TABLE Posts(
                PID INT NOT NULL AUTO_INCREMENT, 
                PRIMARY KEY(PID),
                title VARCHAR(50), 
                content TEXT,
                creator VARCHAR(50),
                timestamp VARCHAR(30),
                tags VARCHAR)';
            $firstpost_title = 'Welcome to Blag';
            $firstpost_content = "Welcome to Blag - the lightweight bloggy thing that was written in (currently) 4 days!";
            $firstpost_creator = 'blag';
            $firstpost_timestamp = date('m/d/Y h:i:s a', time());
            $firstpost_tags = 'first';

            // Execute query
            if (mysqli_query($db,$sql)) {
              echo "Table Posts created successfully";
            } else {
              echo "Error creating table: " . mysqli_error($db);
            }

            $sql = "INSERT INTO Posts (title, content, creator, timestamp, tags)
                    VALUES ('$firstpost_title', 
                    '$firstpost_content', 
                    '$firstpost_creator',
                    '$firstpost_timestamp',
                    '$firstpost_tags')";

            if (!mysqli_query($db,$sql)) {
                die('Error: ' . mysqli_error($db));
            }


        } 

    } else {
        echo "<script type='text/javascript'>displayLoginError('error', 'Blag is already installed')</script>";
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
      <input type="hidden" value="0" name="uage">
  <p>
      <label class="loginpage-content-title"><i class="fa fa-cog"></i> Enter database username.</label>
      <input class="loginpage-content" name="dbuname" type="text" id="dbuname" value=""> 
  </p>
    <p>
      <label class="loginpage-content-title"><i class="fa fa-cog"></i> Enter database password.</label>
      <input class="loginpage-content" name="dbupass" type="password" id="dbupass"> 
  </p>
  <p>
      <label class="loginpage-content-title"><i class="fa fa-cog"></i> Enter database host url.</label>
      <input class="loginpage-content" name="dbhost" type="text" id="dbhost" value=""> 
  </p>
    <p>
      <label class="loginpage-content-title"><i class="fa fa-cog"></i> Enter database name.</label>
      <input class="loginpage-content" name="dbname" type="text" id="dbname"> 
  </p>
    <p>
      <button class="btn btn-submit" type="submit" name="Submit" value="Install">Installe</button>
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
