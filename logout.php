<?php
	session_start();
	unset($_SESSION["username"]);
	unset($_SESSION["password"]);
	unset($_SESSION["ticket"]);
	if(isset($_SESSION["source"]))
		unset($_SESSION["source"]);
	if(isset($_SESSION["destination"]))
		unset($_SESSION["destination"]);
	if(isset($_SESSION["price"]))
		unset($_SESSION["price"]);
	header("location:index.php");
?>