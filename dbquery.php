<?php
session_start();
require('includes/user.php');
//connect to database  
$db = mysqli_connect($dbhost,$dbuname,$dbupass,$dbname);

function strip_tags_attributes( $str, 
		    $allowedTags = array('<a>','<b>','<blockquote>','<br>','<cite>','<code>','<del>','<div>','<em>','<ul>','<ol>','<li>','<dl>','<dt>','<dd>','<img>','<video>','<iframe>','<ins>','<u>','<q>','<h3>','<h4>','<h5>','<h6>','<samp>','<strong>','<sub>','<sup>','<p>','<table>','<tr>','<td>','<th>','<pre>','<span>'), 
		    $disabledEvents = array('onclick','ondblclick','onkeydown','onkeypress','onkeyup','onload','onmousedown','onmousemove','onmouseout','onmouseover','onmouseup','onunload') )
		{       
		    if( empty($disabledEvents) ) {
		        return strip_tags($str, implode('', $allowedTags));
		    }
		    return preg_replace('/<(.*?)>/ies', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $disabledEvents) . ")=[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($str, implode('', $allowedTags)));
		}

if(isset($_POST['username'])) {

	$username = addslashes(strip_tags_attributes($_POST['username']));  
	 
	$result = mysqli_query($db, "SELECT NAME from USERS where NAME = '". $username . "'");  
	  
	//if number of rows fields is bigger them 0 that means it's NOT available '  
	if(mysqli_num_rows($result)>0){  
	    echo 0;  
	}else{  
	    echo 1;  
	}
}

if(isset($_POST['udname'])) {
	$udname = addslashes(strip_tags_attributes($_POST['udname']));
	$user = $_SESSION['user'];

	if (mysqli_query($db, "UPDATE USERS SET disname='$udname' WHERE name='$user'")) {
		echo 1;
	} else {
		echo 0;
	}
}

if(isset($_POST['udbio'])) {
	$udbio = addslashes(strip_tags_attributes($_POST['udbio']));
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

if(isset($_POST['newpass'])) {
	$newpass = addslashes(strip_tags_attributes($_POST['newpass']));
	$newpassconf = addslashes(strip_tags_attributes($_POST['newpassconf']));
	$user = $_SESSION['user'];
	if ($newpass === $newpassconf) {
		$options = [
	                'cost' => 11,
	            ];
		$passtoset = password_hash($newpass, PASSWORD_BCRYPT, $options);
		$sql = "UPDATE users SET pass='$passtoset' WHERE name='$user'";
		if (mysqli_query($db, $sql)) {
			echo 1;
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}
}

if(isset($_POST['newemail'])) {
	$newemail = addslashes(strip_tags_attributes($_POST['newemail']));
	$user = $_SESSION['user'];

	$sql = "UPDATE users SET email='$newemail' WHERE name='$user'";
	if (mysqli_query($db, $sql)) {
		echo 1;
	} else {
		echo 0;
	}
}

//echo $_SESSION['mode'];