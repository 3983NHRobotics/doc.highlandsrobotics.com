<?php
session_start();
require('includes/user.php');
//connect to database  
$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);

if(isset($_POST['username'])) {

	$username = addslashes($_POST['username']);  
	 
	$result = mysqli_query($db, "SELECT NAME from USERS where NAME = '". $username . "'");  
	  
	//if number of rows fields is bigger them 0 that means it's NOT available '  
	if(mysqli_num_rows($result)>0){  
	    echo 0;  
	}else{  
	    echo 1;  
	}
}

if(isset($_POST['udname'])) {
	$udname = addslashes($_POST['udname']);
	$user = $_SESSION['user'];

	if (mysqli_query($db, "UPDATE USERS SET disname='$udname' WHERE name='$user'")) {
		echo 1;
	} else {
		echo 0;
	}
}

if(isset($_POST['udbio'])) {
	$udbio = addslashes($_POST['udbio']);
	$user = $_SESSION['user'];
	$sql = "UPDATE Users SET bio='$udbio' WHERE name='$user'";

	if (mysqli_query($db, $sql)) {
		echo 1;
	} else {
		echo 0;
	}
}

if(isset($_POST['deletepost'])) {
	if(isset($_SESSION['mode'])) {
		if($_SESSION['mode'] === 'admin') {
			$pid = $_POST['postid'];
			$sql = "DELETE FROM Posts WHERE PID=$pid";
			mysqli_query($db, $sql);
			echo 'hello';
			header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/index.php');
		}
	}
}

//echo $_SESSION['mode'];