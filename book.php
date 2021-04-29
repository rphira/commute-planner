<?php
session_start();

if(isset($_SESSION["username"]))
{
	
	$uname = $_SESSION["username"];
	$price = $_SESSION["price"];
    $detail = $_SESSION["source"];
    $detail.= "," . $_SESSION["destination"];
    $detail.= "," . $price;

    
    $conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn === false) 
	{
		die("ERROR: Could not connect. " . $conn->connect_error);
	}

	$query1 = "UPDATE persons SET ticket='$detail' where username='$uname'";
	if($conn->query($query1) == true)
	{
		echo "Booked successfully <br>";
		echo "Ticket valid between ".$_SESSION["source"]." and ".$_SESSION["destination"]."<br>";
    	echo "Ticket Price = Rs. ".$_SESSION["price"];
    	echo "<br><br>";
    	echo "<a href='plan.php'>Back</a>";
	}
	else
    	echo "ERROR: Could not execute";
}
else
{
	echo "<script>alert('PLEASE LOG IN FIRST')</script";
	header("location:login.html");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Book Ticket</title>
	<link rel="stylesheet" type="text/css" href="plan-style.css">
</head>
<body style="text-align: center; font-size: 25px; margin-top: 30px;">

</body>
</html>