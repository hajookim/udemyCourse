<?php
	session_start(); 
	$_SESSION['username'] = "robpercival"; 
	if ($_SESSION['email']) {
		echo "you are logged in"; 
	}
?>