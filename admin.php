<?php
	echo "<script>alert('YOU ARE NOW DIRECTED TO THE ADMIN PAGE')</SCRIPT>";

	$conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn === false) {
		die("ERROR: Could not connect.<br> " . $conn->connect_error);
	}

	$selecttbl = 'SELECT * FROM persons';
    $selecttblresult = $conn->query($selecttbl);

    if( $selecttblresult->num_rows > 0)
    {	
    	echo "<h1 style='text-align: center; margin-top: 50px; font-size: 40px; text-decoration: underline;'><em>User Details (For Admin)</em></h1>";
    	echo "<div class='tab' style='margin-top: 5%;'>";
        echo '<table border="2" class="ui inverted large table">
        <tr >
            <th class="center aligned" style="font-size: 30px;"><em>ID</em></th>
            <th class="center aligned" style="font-size: 30px;"><em>Username</em></th>
            <th class="center aligned" style="font-size: 30px;"><em>Ticket</em></th>
        </tr>';
        while( $row = $selecttblresult->fetch_assoc() ) 
        {
            echo '  <tr class="center aligned">
                    <td class="center aligned">' . $row["id"] . '</td>
                    <td class="center aligned">' . $row["username"] . '</td>
                    <td class="center aligned">' . $row["ticket"] .'</td>
                    </tr>';
        }

        echo '</table></div>';
    }

    else
        echo '<br><h2>Empty Table</h2>';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" integrity="sha512-8bHTC73gkZ7rZ7vpqUQThUDhqcNFyYi2xgDgPDHc+GXVGHXq+xPjynxIopALmOPqzo9JZj0k6OqqewdGO3EsrQ==" crossorigin="anonymous" />
</head>
<body style="font-size: 20px; background: #005762;">
	<form action="index.php" style="text-align: center; padding-top: 20px;">
		<button class="ui secondary button" style="font-size: 20px;">Log out</button>
	</form>
</body>
</html>