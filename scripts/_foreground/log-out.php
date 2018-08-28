<?php
	if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
		session_start();
	}	
	session_destroy();
	header('location:  ../../login.php');
	exit();
?>