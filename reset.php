<?php
	session_start();
	unset($_SESSION["source"]);
	unset($_SESSION["destination"]);
	unset($_SESSION["price"]);

	$uname = $_SESSION["username"];

	$conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn === false) {
		die("ERROR: Could not connect.<br> " . $conn->connect_error);
	}

	$result1 = "UPDATE persons SET ticket='none' where username='$uname'";

	if($conn->query($result1) == true)
	{
		header("location:plan.php");
	}
	else
    	echo "ERROR: Could not execute";
?>	