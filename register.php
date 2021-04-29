<?php
	$uname = $_POST["username"];
	$pass = $_POST["password"];

	$conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn === false) 
	{
		die("ERROR: Could not connect. " . $conn->connect_error);
	}

	$query1 = "INSERT into `persons`(`username`, `password`) VALUES ('$uname', '$pass')";

	if($conn->query($query1) == true)
	{
		echo "Registered successfully.";
    	echo "<br><br>";
    	echo "<a href='login.html'>Proceed to Login</a>";
	}
	else
    	echo "ERROR: Could not able to execute $sql1. " . $conn->error;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<link rel="stylesheet" type="text/css" href="plan-style.css">
</head>
<body style="text-align: center; margin-top: 30px;">
</body>
</html>