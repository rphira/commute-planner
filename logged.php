<?php
	$uname = $_POST["username"];
	$pass = $_POST["password"];

	$conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn === false) {
		die("ERROR: Could not connect.<br> " . $conn->connect_error);
	}

	if($uname == "admin" and $pass == "admin")
		header("location:admin.php");
	
	else 
	{
		$result1 = $conn->query("SELECT username, password, ticket from persons WHERE username='$uname'");

		if($result1->num_rows > 0)
		{
			while($row = $result1->fetch_assoc()) {

				if($row["password"] == $pass) 
				{
					session_start();
					if($row["ticket"] != "")
					{
						$a = explode(",",$row["ticket"]);
						$_SESSION["source"] = $a[0];
						$_SESSION["destination"] = $a[1];
						$_SESSION["price"] = $a[2];
					}
					$_SESSION["username"] = $uname;
					$_SESSION["password"] = $pass;
					if($row["ticket"] != 0)
						$_SESSION["ticket"] = $row["ticket"];

					header("location:plan.php");
				}
				else
				{
					echo "Password incorrect";
					echo "<br><br>";
					echo "<a href='login.html'>Back</a>";
				}
			}
		}
		else
		{
			echo "User not found";
			echo "<br><br>";
			echo "<a href='login.html'>Back</a>";
		}
	}
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
