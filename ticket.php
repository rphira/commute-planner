<?php
	session_start();
	$counter = 0;

	$conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn === false) {
		die("ERROR: Could not connect.<br> " . $conn->connect_error);
	}

	$uname = $_SESSION["username"];
	$result1 = $conn->query("SELECT ticket from persons WHERE username='$uname'");

	if($result1->num_rows > 0)
	{
		while($row = $result1->fetch_assoc()) 
		{
			if($row["ticket"] = "none")
				$counter = 1;
		}
	}

	if($counter != 0)
	{
		echo "<div class='bord' style='border: 2px solid black;'";
		echo "<h3><em>YOUR TICKET</em></h3>";
		echo "<hr>";
		echo "<strong>Source: </strong>" . $_SESSION["source"];
		echo "<br><strong>Destination: </strong>" . $_SESSION["destination"];
		echo "<br><strong>Price: </strong>Rs. " . $_SESSION["price"];
		echo "<form action='plan.php'> <div style='padding-top: 40px;''><button class='submitb'>Back</button></div> </form>";
		echo "</div>";
	}
	else
	{
		echo "You have not booked a ticket yet";
		echo "<form action='plan.php'> <div style='padding-top: 40px;''><button class='submitb'>Back</button></div> </form>";
		echo "</div";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ticket</title>
	

	<style>
		.submitb {
			position: relative;
			margin: 0 auto;
			border: .15em solid #3ab09e;
			border-radius: 5em;
			text-align: center;
			font-size: 0.8em;
			
			height: 80px;
			width: 200px;
		}
		.submitb:hover {
			background-color: #3ab09e;
			cursor: pointer;
		  	color: white;
		  	transition-duration: 0.4s;
		}
		hr {
			width: 50%;
			color: black;
		}
	</style>
	
	
</head>
<body style="text-align: center; margin-top: 100px; background-color: #eff3f0;
	color: tomato ; font-size: 30px; font: ariel;">
	<link rel="stylesheet" type="text/css" href="plan.php">
</body>
</html>