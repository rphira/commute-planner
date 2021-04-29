<?php
	session_start();

		unset($_SESSION["source"]);
		unset($_SESSION["destination"]);
		unset($_SESSION["price"]);
		header("location:plan.php");

?>