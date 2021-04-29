<?php

	$source = $_POST["source"];
	$destination = $_POST["destination"];

	$conn = new mysqli("localhost", "root", "", "wp_project");

	if($conn == false) 
	{
		echo "Connection error";
		echo "<a href='index.php'>Back</a>";
	}


	 $result01 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE name='$source';");
	 $result02 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination';");
	 

	if($result01->num_rows > 0) 
	{
		while($row = $result01->fetch_assoc()) 
		{
			$sourceid = $row["StationID"];

			if($row["central"] == 1)
				$source_line = "central";
			else if($row["western"] == 1)
				$source_line = "western";
			else if($row["harbour"] == 1)
				$source_line = "harbour";

			$source_distance = $row["distance"];
			$source_speed = $row["speed"];
		}
	}

	else 
	{
		echo "Data not found";
		echo "<a href='plan.php'>Back</a>";
	}

	if($result02->num_rows > 0) 
	{
		while($row = $result02->fetch_assoc()) 
		{
			$destid = $row["StationID"];

			if($row["central"] == 1)
				$destination_line = "central";
			else if($row["western"] == 1)
				$destination_line = "western";
			else if($row["harbour"] == 1)
				$destination_line = "harbour";

			$destination_distance = $row["distance"];
			$destination_speed = $row["speed"];
		}
	} 

	else 
	{
		echo "Data not found";
		echo "<a href='plan.php'>Back</a>";
	}


	if($source == $destination) {
		echo "ERROR: Same source and destination entered!";
		echo "<form action='plan.php'><div style='padding-top: 40px;''><button class='submitb'>Back</button></div></form>";
	}

	else {

	if($source_line == $destination_line) 
	{

		if($source_line == "central") 
		{

			if($source_speed == "Fast" AND $destination_speed == "Fast") 
			{
				echo "Taking a fast train would be optimal<br>";
				$time_taken = abs(($destination_distance - $source_distance) * 1);

				if($source_distance - $destination_distance > 0)
				{
					if($source_distance > 12) 
						echo "You must take a Thane-CSMT(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
					else
						echo "You could take a Thane-CSMT (Fast) or a Kurla-CSMT(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
				}

				else
				{
					if($destination_distance > 12)
						echo "You must take a CSMT-Thane(Fast)<br>Total time taken for the journey is approximately" . $time_taken . " minutes<br>";
					else
						echo "You could take a CSMT-Kurla(Fast) or a CSMT-Thane(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
				}

				//Displaying Route (Start)

				if($source_distance < $destination_distance)
					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 AND speed = 'Fast' order by StationID");
				else
					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 AND speed = 'Fast' order by StationID DESC");
				
				if($route1->num_rows > 0)
				{
					echo "<br>Your route is<br>";
					while($row = $route1->fetch_assoc())
					{
						if($source_distance > $destination_distance)
						{
							if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
								echo $row["name"] . "<br>";
						}
						else
						{
							if($row["distance"] >= $source_distance and $row["distance"] <= $destination_distance)
								echo $row["name"] . "<br>";
						}

					}
				}

				//Displaying Route(End)
			}
		
			else if($source_speed != $destination_speed)
			{

				$midID = 0;
				$midName = "";
				$middist=0;
				$min = 100;

				if($source_speed == "Slow")
				{

					if($source_distance - $destination_distance > 0)
					{
						$result3 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE central=1 AND speed='Fast'");

						if($result3->num_rows > 0)
						{
							while($row = $result3->fetch_assoc())
							{
								if($source_distance - $row["distance"] > 0 AND $source_distance - $row["distance"] < $min)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = $source_distance - $row["distance"];
									$middist = $row["distance"];
								}
							}
						}
						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}
  			
						$time_taken = abs(($source_distance - $middist) * 1.5) + abs(($middist - $destination_distance) * 1);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($source_distance > 12)
							{
								echo "Take a Thane-CSMT(Slow) till " . $midName;
								if($middist > 12)
								{
									echo " and change to a Thane-CSMT(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a Thane-CSMT(Fast) or a Kurla-CSMT(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a Kurla-CSMT(Slow) or a Thane-CSMT(Slow) till " . $midName . " and change to a Kurla-CSMT(Fast) or a Thane-CSMT(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a Thane-CSMT(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}
					else
					{
						$result4 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE central = 1 AND speed = 'Fast';");

						if($result4->num_rows > 0)
						{
							while($row = $result4->fetch_assoc())
							{
								if(($source_distance - $row["distance"]) < 0 AND $row["distance"] < $min)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = $row["distance"];
									$middist = $row["distance"];		
								}
							}
						}

						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}

						$time_taken = abs(($source_distance - $min) * 1.5) + abs(($min - $destination_distance) * 1);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($destination_distance > 12)
							{
								
								if($min > 12)
								{
									echo "Take a CSMT-Thane(Slow) till " . $midName;
								}
								else
								{
									echo "Take a CSMT-Thane(Slow) or a CSMT-Kurla(Slow) till " . $midName;
								}

								if($min > 12)
								{
									echo " and change to a CSMT-Thane(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a CSMT-Thane(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a CSMT-Kurla(Slow) or a CSMT-Thane(Slow) till " . $midName . " and change to a CSMT-Kurla(Fast) or a CSMT-Thane(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a CSMT-Thane(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}

					//Displaying Route (Start)
					if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))  //Changing trains is necessary
					{
						if($source_distance < $destination_distance)  //Travelling up to down
						{	
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
									if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
									{
										if($row["distance"] == $middist)
											echo $row["name"] . " " . "(Change trains here)<br>";
										else
											echo $row["name"] . "<br>";
									}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 AND speed = 'Fast' order by StationID");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($row["distance"] > $middist and $row["distance"] <= $destination_distance)
										echo $row["name"] . "<br>";

								}
							}
						}
						else //Travelling down to up
						{
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID DESC");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
										if($row["distance"] >= $middist and $row["distance"] <= $source_distance)
										{
											if($row["distance"] == $middist)
												echo $row["name"] . " " . "(Change trains here)<br>";
											else
												echo $row["name"] . "<br>";
										}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 AND speed = 'Fast' order by StationID DESC");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($source_distance > $destination_distance)
									{
										if($row["distance"] < $middist and $row["distance"] >= $destination_distance)
											echo $row["name"] . "<br>";
									}
									else
									{
										if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
											echo $row["name"] . "<br>";
									}

								}
							}
						}
					}
					else  //Changing trains is not necessary
					{

						if($source_distance < $destination_distance)
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID");
						else
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID DESC");
						if($route1->num_rows > 0)
						{
							echo "<br>Your route is<br>";
							while($row = $route1->fetch_assoc())
							{
								if($source_distance > $destination_distance)
								{
									if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
										echo $row["name"] . "<br>";
								}
								else
								{
									if($row["distance"] <= $destination_distance and $row["distance"] >= $source_distance)
										echo $row["name"] . "<br>";
								}

							}
						}
					}
					//Displaying Route (End)
				}

				else if($destination_speed == "Slow")
				{
					
					if($source_distance - $destination_distance > 0)
					{
						$result5 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE central=1 AND speed='Fast'");

						if($result5->num_rows > 0)
						{
							while($row = $result5->fetch_assoc())
							{
								if($destination_distance - $row["distance"] < 0 AND $row["distance"] < $min AND abs($source_distance - $row["distance"]) != 0)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = $row["distance"];
									$middist = $row["distance"];
								}
							}
						}
						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}

						$time_taken = abs(($source_distance - $min) * 1.5) + abs(($min - $destination_distance) * 1);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($source_distance > 12)
							{
								echo "Take a Thane-CSMT(Fast) till " . $midName;
								if($min > 12)
								{
									echo " and change to a Thane-CSMT(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a Thane-CSMT(Slow) or a Kurla-CSMT(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a Kurla-CSMT(Fast) or a Thane-CSMT(Fast) till " . $midName . " and change to a Kurla-CSMT(Slow) or a Thane-CSMT(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a Thane-CSMT(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}
					else
					{
						$result6 = $conn->query("SELECT StationID,  name, central, western, harbour, speed, distance FROM stations WHERE central = 1 and speed = 'Fast'");

						if($result6->num_rows > 0)
						{
							while($row = $result6->fetch_assoc())
							{
								if(($destination_distance - $row["distance"]) > 0 AND $row["distance"] > $source_distance AND abs($destination_distance - $row["distance"]) < $min AND abs($source_distance - $row["distance"]) != 0)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = abs($destination_distance - $row["distance"]);
									$middist = $row["distance"];
								}
							}
						}
						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}

						$time_taken = abs(($source_distance - $middist) * 1) + abs(($middist - $destination_distance) * 1.5);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($destination_distance > 12)
							{
								if($middist > 12)
								{
									echo "Take a CSMT-Thane(Fast) till " . $midName;
								}
								else
								{
									echo "Take a CSMT-Thane(Fast) or a CSMT-Kurla(Fast) till " . $midName;
								}
								if($middist > 12)
								{
									echo " and change to a CSMT-Thane(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a CSMT-Thane(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a CSMT-Kurla(Fast) or a CSMT-Thane(Fast) till " . $midName . " and change to a CSMT-Kurla(Slow) or a CSMT-Thane(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a CSMT-Thane(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}

					//Displaying Route (Start)
					if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))  //Changing trains is necessary
					{
						if($source_distance < $destination_distance)  //Travelling up to down
						{	
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 AND speed = 'Fast' order by StationID");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
										if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
										{
											if($row["distance"] == $middist)
												echo $row["name"] . " " . "(Change trains here)<br>";
											else
												echo $row["name"] . "<br>";
										}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($row["distance"] > $middist and $row["distance"] <= $destination_distance)
										echo $row["name"] . "<br>";

								}
							}
						}
						else //Travelling down to up
						{
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 AND speed = 'Fast' order by StationID DESC");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
									if($row["distance"] >= $middist and $row["distance"] <= $source_distance)
									{
										if($row["distance"] == $middist)
											echo $row["name"] . " " . "(Change trains here)<br>";
										else
											echo $row["name"] . "<br>";
									}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID DESC");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($source_distance > $destination_distance)
									{
										if($row["distance"] < $middist and $row["distance"] >= $destination_distance)
											echo $row["name"] . "<br>";
									}
									else
									{
										if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
											echo $row["name"] . "<br>";
									}

								}
							}
						}
					}
					else  //Changing trains is not necessary
					{

						if($source_distance < $destination_distance)
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID");
						else
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID DESC");
						if($route1->num_rows > 0)
						{
							echo "<br>Your route is<br>";
							while($row = $route1->fetch_assoc())
							{
								if($source_distance > $destination_distance)
								{
									if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
										echo $row["name"] . "<br>";
								}
								else
								{
									if($row["distance"] <= $destination_distance and $row["distance"] >= $source_distance)
										echo $row["name"] . "<br>";
								}
							}
						}
					}
					//Displaying Route (End)
				}
			} 
			else if($source_speed == "Slow" AND $destination_speed == "Slow")
			{
				$time_taken = abs(($destination_distance - $source_distance) * 1.5);

				if($source_distance - $destination_distance > 0) 
				{
					if($source_distance > 12)
						echo "You must take a Thane-CSMT (Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";
					else
						echo "You could take a Kurla-CSMT(Slow) or a Thane-CSMT(Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";

					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID DESC");
					if($route1->num_rows > 0)
					{
						echo "<br>Your route is<br>";
						while($row = $route1->fetch_assoc())
						{
							if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
									echo $row["name"] . "<br>";
						}
					}
				}
				else
				{
					if($destination_distance > 12)
						echo "You must take a CSMT-Thane(Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";
					else
						echo "You could take a CSMT-Kurla(Slow) or a CSMT-Thane(Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";

					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE central = 1 order by StationID");

					if($route1->num_rows > 0)
					{
						echo "<br>Your route is<br>";
						while($row = $route1->fetch_assoc())
						{
							if($row["distance"] >= $source_distance and $row["distance"] <= $destination_distance)
								echo $row["name"] . "<br>";
						}
					}
				}
			}
		}

		else if($source_line == "western") 
		{
			if($source_speed == "Fast" AND $destination_speed == "Fast") 
			{
				echo "Taking a fast train would be optimal<br>";
				$time_taken = abs(($destination_distance - $source_distance) * 1);

				if($source_distance - $destination_distance > 0)
				{
					if($source_distance > 31) 
						echo "You must take a Virar-Churchgate(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
					else
						echo "You could take a Virar-Churchgate(Fast) or a Borivali-Churchgate(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
				}

				else
				{
					if($destination_distance > 31)
						echo "You must take a Churchgate-Virar(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
					else
						echo "You could take a Churchgate-Borivali(Fast) or a Churchgate-Virar(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes<br>";
				}

				if($source_distance < $destination_distance)
					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 AND speed = 'Fast' order by StationID");
				else
					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 AND speed = 'Fast' order by StationID DESC");
				
				if($route1->num_rows > 0)
				{
					echo "<br>Your route is<br>";
					while($row = $route1->fetch_assoc())
					{
						if($source_distance > $destination_distance)
						{
							if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
								echo $row["name"] . "<br>";
						}
						else
						{
							if($row["distance"] >= $source_distance and $row["distance"] <= $destination_distance)
								echo $row["name"] . "<br>";
						}

					}
				}
			}
		
			else if($source_speed != $destination_speed)
			{

				$midID = 0;
				$midName = "";
				$min = 100;
				$middist = 0;

				if($source_speed == "Slow")
				{

					if($source_distance - $destination_distance > 0)
					{
						$result3 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE western=1 AND speed='Fast'");

						if($result3->num_rows > 0)
						{
							while($row = $result3->fetch_assoc())
							{

								if($source_distance - $row["distance"] > 0 AND $source_distance - $row["distance"] < $min)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = $source_distance - $row["distance"];
									$middist = $row["distance"];
								}
							}
						}
						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}

						$time_taken = abs(($source_distance - $middist) * 1.5) + abs(($middist - $destination_distance) * 1);



						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($source_distance > 31)
							{
								echo "Take a Virar-Churchgate(Slow) till " . $midName;
								if($middist > 31)
								{
									echo " and change to a Virar-Churchgate(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a Virar-Churchgate(Fast) or a Borivali-Churchgate(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a Borivali-Churchgate(Slow) or a Virar-Churchgate(Slow) till " . $midName . " and change to a Borivali-Churchgate(Fast) or a Virar-Churchgate(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a Virar-Churchgate(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}
					else
					{
						$result4 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE western = 1 AND speed = 'Fast';");

						if($result4->num_rows > 0)
						{
							while($row = $result4->fetch_assoc())
							{
								if(($source_distance - $row["distance"]) < 0 AND $row["distance"] < $min)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = $row["distance"];	
									$middist = $row["distance"];	
								}
							}
						}

						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}

						$time_taken = abs(($source_distance - $min) * 1.5) + abs(($min - $destination_distance) * 1);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($destination_distance > 31)
							{
								if($min > 31)
								{
									echo "Take a Churchgate-Virar(Slow) till " . $midName;
								}
								else
								{
									echo "Take a Churchgate-Virar(Slow) or a Churchgate-Borivali(Slow) till " . $midName;
								}	
								if($min > 31)
								{
									echo " and change to a Churchgate-Virar(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a Churchgate-Virar(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a Churchgate-Borivali(Slow) or a Churchgate-Virar(Slow) till " . $midName . " and change to a Churchgate-Borivali(Fast) or a Churchgate-Virar(Fast)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a Churchgate-Virar(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}

					//Displaying Route (Start)
					if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))  //Changing trains is necessary
					{
						if($source_distance < $destination_distance)  //Travelling up to down
						{	
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
										if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
										{
											if($row["distance"] == $middist)
												echo $row["name"] . " " . "(Change trains here)<br>";
											else
												echo $row["name"] . "<br>";
										}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 AND speed = 'Fast' order by StationID");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($row["distance"] > $middist and $row["distance"] <= $destination_distance)
										echo $row["name"] . "<br>";

								}
							}
						}
						else //Travelling down to up
						{
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID DESC");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
										if($row["distance"] >= $middist and $row["distance"] <= $source_distance)
										{
											if($row["distance"] == $middist)
												echo $row["name"] . " " . "(Change trains here)<br>";
											else
												echo $row["name"] . "<br>";
										}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 AND speed = 'Fast' order by StationID DESC");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($source_distance > $destination_distance)
									{
										if($row["distance"] < $middist and $row["distance"] >= $destination_distance)
											echo $row["name"] . "<br>";
									}
									else
									{
										if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
											echo $row["name"] . "<br>";
									}

								}
							}
						}
					}
					else  //Changing trains is not necessary
					{

						if($source_distance < $destination_distance)
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID");
						else
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID DESC");
						if($route1->num_rows > 0)
						{
							echo "<br>Your route is<br>";
							while($row = $route1->fetch_assoc())
							{
								if($source_distance > $destination_distance)
								{
									if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
										echo $row["name"] . "<br>";
								}
								else
								{
									if($row["distance"] <= $destination_distance and $row["distance"] >= $source_distance)
										echo $row["name"] . "<br>";
								}

							}
						}
					}
					//Displaying Route (End)

				}

				else if($destination_speed == "Slow")
				{
					
					if($source_distance - $destination_distance > 0)
					{
						$result5 = $conn->query("SELECT StationID, name, central, western, harbour, speed, distance FROM stations WHERE western=1 AND speed='Fast'");

						if($result5->num_rows > 0)
						{
							while($row = $result5->fetch_assoc())
							{
								if($destination_distance - $row["distance"] < 0 AND $row["distance"] < $min AND abs($source_distance - $row["distance"]) != 0)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = $row["distance"];
									$middist = $row["distance"];
								}
							}
						}
						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}
						$time_taken = abs(($source_distance - $min) * 1.5) + abs(($min - $destination_distance) * 1);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($source_distance > 31)
							{
								echo "Take a Virar-Churchgate(Fast) till " . $midName;
								if($min > 31)
								{
									echo " and change to a Virar-Churchgate(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a Virar-Churchgate(Slow) or a Borivali-Churchgate(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a Borivali-Churchgate(Fast) or a Virar-Churchgate(Fast) till " . $midName . " and change to a Borivali-Churchgate(Slow) or a Virar-Churchgate(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a Virar-Churchgate(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}
					else
					{
						$result6 = $conn->query("SELECT StationID,  name, central, western, harbour, speed, distance FROM stations WHERE western = 1 and speed = 'Fast'");

						if($result6->num_rows > 0)
						{
							while($row = $result6->fetch_assoc())
							{
								if(($destination_distance - $row["distance"]) > 0 AND $row["distance"] > $source_distance AND abs($destination_distance - $row["distance"]) < $min AND abs($source_distance - $row["distance"]) != 0)
								{
									$midID = $row["StationID"];
									$midName = $row["name"];
									$min = abs($destination_distance - $row["distance"]);
									$middist = $row["distance"];
								}
							}
						}
						else
						{
							echo "Data not found";
							echo "<a href='index.html'>Back</a>";
						}

						$time_taken = abs(($source_distance - $middist) * 1) + abs(($middist - $destination_distance) * 1.5);

						if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))
						{
							if($destination_distance > 31)
							{
								if($middist > 31)
								{
									echo "Take a Churchgate-Virar(Fast) till " . $midName;
								}
								else
								{
									echo "Take a Churchgate-Virar(Fast) or a Churchgate-Borivali(Fast) till " . $midName;
								}
								if($middist > 31)
								{
									echo " and change to a Churchgate-Virar(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
								else
								{
									echo " and change to a Churchgate-Virar(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
								}
							}
							else
							{
								echo "Take a Churchgate-Borivali(Fast) or a Churchgate-Virar(Fast) till " . $midName . " and change to a Churchgate-Borivali(Slow) or a Churchgate-Virar(Slow)<br>Total time taken for the journey is approximately " . $time_taken . " minutes";
							}
						}
						else
						{
							echo "Take a Churchgate-Virar(Slow)<br>Total time taken for the journey is " . (abs(($destination_distance - $source_distance) * 1.5)) . " minutes";
						}
					}
				

					//Displaying Route (Start)
					if($time_taken < (abs(($destination_distance - $source_distance) * 1.5)))  //Changing trains is necessary
					{
						if($source_distance < $destination_distance)  //Travelling up to down
						{	
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 AND speed = 'Fast' order by StationID");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
									if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
									{
										if($row["distance"] == $middist)
											echo $row["name"] . " " . "(Change trains here)<br>";
										else
											echo $row["name"] . "<br>";
									}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($row["distance"] > $middist and $row["distance"] <= $destination_distance)
										echo $row["name"] . "<br>";

								}
							}
						}
						else //Travelling down to up
						{
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 AND speed = 'Fast' order by StationID DESC");
							if($route1->num_rows > 0)
							{
								echo "<br>Your route is<br>";
								while($row = $route1->fetch_assoc())
								{
									if($row["distance"] >= $middist and $row["distance"] <= $source_distance)
									{
										if($row["distance"] == $middist)
											echo $row["name"] . " " . "(Change trains here)<br>";
										else
											echo $row["name"] . "<br>";
									}
								}
							}

							$route2 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID DESC");
							if($route2->num_rows > 0)
							{
								while($row = $route2->fetch_assoc())
								{
									if($source_distance > $destination_distance)
									{
										if($row["distance"] < $middist and $row["distance"] >= $destination_distance)
											echo $row["name"] . "<br>";
									}
									else
									{
										if($row["distance"] <= $middist and $row["distance"] >= $source_distance)
											echo $row["name"] . "<br>";
									}							
								}
							}
						}
					}
					else  //Changing trains is not necessary
					{

						if($source_distance < $destination_distance)
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID");
						else
							$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID DESC");
						if($route1->num_rows > 0)
						{
							echo "<br>Your route is<br>";
							while($row = $route1->fetch_assoc())
							{
								if($source_distance > $destination_distance)
								{
									if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
										echo $row["name"] . "<br>";
								}
								else
								{
									if($row["distance"] <= $destination_distance and $row["distance"] >= $source_distance)
										echo $row["name"] . "<br>";
								}

							}
						}
					}
					//Displaying Route (End)
				}
			} 
			else if($source_speed == "Slow" AND $destination_speed == "Slow")
			{
				$time_taken = abs(($destination_distance - $source_distance) * 1.5);

				if($source_distance - $destination_distance > 0) 
				{
					if($source_distance > 31)
						echo "You must take a Virar-Churchgate (Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";
					else
						echo "You could take a Borivali-Churchgate(Slow) or a Virar-Churchgate(Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";

					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID DESC");
					if($route1->num_rows > 0)
					{
						echo "<br>Your route is<br>";
						while($row = $route1->fetch_assoc())
						{
							if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
									echo $row["name"] . "<br>";
						}
					}

				}
				else
				{
					if($destination_distance > 31)
						echo "You must take a Churchgate-Virar(Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";
					else
						echo "You could take a Churchgate-Borivali(Slow) or a Churchgate-Virar(Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";

					$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE western = 1 order by StationID");

					if($route1->num_rows > 0)
					{
						echo "<br>Your route is<br>";
						while($row = $route1->fetch_assoc())
						{
							if($row["distance"] >= $source_distance and $row["distance"] <= $destination_distance)
								echo $row["name"] . "<br>";
						}
					}
				}
			}
		}

		else if($source_line == "harbour") 
		{

			$time_taken = abs(($destination_distance - $source_distance) * 1.5);

			if($source_distance - $destination_distance > 0) 
			{
				echo "You must take a Panvel-CSMT (Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";

				$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE harbour = 1 AND western = 0 order by StationID DESC");
				if($route1->num_rows > 0)
				{
					echo "<br>Your route is<br>";
					while($row = $route1->fetch_assoc())
					{
						if($row["distance"] <= $source_distance and $row["distance"] >= $destination_distance)
								echo $row["name"] . "<br>";
					}
				}
			}
			else
			{
				echo "You must take a CSMT-Panvel (Slow)<br>Time take is approximately ". $time_taken . " minutes<br>";

				$route1 = $conn->query("SELECT StationID, name, distance FROM stations WHERE harbour = 1 and western = 0 order by StationID");

				if($route1->num_rows > 0)
				{
					echo "<br>Your route is<br>";
					while($row = $route1->fetch_assoc())
					{
						if($row["distance"] >= $source_distance and $row["distance"] <= $destination_distance)
							echo $row["name"] . "<br>";
					}
				}
			}
		}
	}




	else 
	{
		$query_1 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$source'";
		$query_2 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination'";
		$query_3 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$source'";
		$query_4 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination'";
		$query_5 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$source'";
		$query_6 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination'";
		$query_7 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$source'";
		$query_8 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination'";
		$query_9 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$source'";
		$query_10 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination'";
		$query_11 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$source'";
		$query_12 = "SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE name='$destination'";

		$result1 = $conn->query($query_1);
		$result2 = $conn->query($query_2);
		$result3 = $conn->query($query_3);
		$result4 = $conn->query($query_4);
		$result5 = $conn->query($query_5);
		$result6 = $conn->query($query_6);
		$result7 = $conn->query($query_7);
		$result8 = $conn->query($query_8);
		$result9 = $conn->query($query_9);
		$result10 = $conn->query($query_10);
		$result11 = $conn->query($query_11);
		$result12 = $conn->query($query_12);

		$counter1=0;
		$counter2=0;
		$counter3=0;
		$counter4=0;
		$counter5=0;
		$counter6=0;
		$stationsmiddle=0;
		$faststationsmiddle=0;

		if($result1->num_rows > 0) {
			while($row=$result1->fetch_assoc())
			{
				if($row["stationid"]>=49 AND $row["stationid"]<=73)
				{
					$sourcename=$row["name"];
					$sourcedist=$row["distance"];
					$sourcesid=$row["stationid"];
					$counter1=$counter1+1;
				}
			}
		}

		if($result2->num_rows > 0) {
			while($row=$result2->fetch_assoc())
			{
				if(($row["stationid"]>=1 AND $row["stationid"]<=29)OR $row["stationid"]==0)
				{
					$destname=$row["name"];
					$destdist=$row["distance"];
					$destsid=$row["stationid"];
					$counter1=$counter1+1;
				}
			}
		}

		if($result3->num_rows > 0) {
			while($row=$result3->fetch_assoc())
			{
				if(($row["stationid"]>=1 AND $row["stationid"]<=29)OR $row["stationid"]==0)
				{
					$sourcename=$row["name"];
					$sourcedist=$row["distance"];
					$sourcesid=$row["stationid"];
					$counter2=$counter2+1;
				}
			}
		}

		if($result4->num_rows > 0) {
			while($row=$result4->fetch_assoc())
			{
				if($row["stationid"]>=49 AND $row["stationid"]<=73)
				{
					$destname=$row["name"];
					$destdist=$row["distance"];
					$destsid=$row["stationid"];
					$counter2=$counter2+1;
				}
			}
		}

		if($result5->num_rows > 0) {
			while($row=$result5->fetch_assoc())
			{
				if($row["stationid"]>=1 AND $row["stationid"]<=29)
				{
					$sourcename=$row["name"];
					$sourcedist=$row["distance"];
					$sourcesid=$row["stationid"];
					$sourcespeed=$row["speed"];
					$counter3=$counter3+1;
				}
			}
		}

		if($result6->num_rows > 0) {
			while($row=$result6->fetch_assoc())
			{
				if($row["stationid"]>=30 AND $row["stationid"]<=48)
				{
					$destname=$row["name"];
					$destdist=$row["distance"];
					$destsid=$row["stationid"];
					$destspeed=$row["speed"];
					$counter3=$counter3+1;
				}
			}
		}

		if($result7->num_rows > 0) {
			while($row=$result7->fetch_assoc())
			{
				if($row["stationid"]>=30 AND $row["stationid"]<=48)
				{
					$sourcename=$row["name"];
					$sourcedist=$row["distance"];
					$sourcesid=$row["stationid"];
					$sourcespeed=$row["speed"];
					$counter4=$counter4+1;
				}
			}
		}

		if($result8->num_rows > 0) {
			while($row=$result8->fetch_assoc())
			{
				if($row["stationid"]>=1 AND $row["stationid"]<=29)
				{
					$destname=$row["name"];
					$destdist=$row["distance"];
					$destsid=$row["stationid"];
					$destspeed=$row["speed"];
					$counter4=$counter4+1;
				}
			}
		}

		if($result9->num_rows > 0) {
			while($row=$result9->fetch_assoc())
			{
				if($row["stationid"]>=30 AND $row["stationid"]<=48)
				{
					$sourcename=$row["name"];
					$sourcedist=$row["distance"];
					$sourcesid=$row["stationid"];
					$sourcespeed=$row["speed"];
					$counter5=$counter5+1;
				}
			}
		}

		if($result10->num_rows > 0) {
			while($row=$result10->fetch_assoc())
			{
				if($row["stationid"]>=49 AND $row["stationid"]<=73 OR $row["stationid"]==0)
				{
					$destname=$row["name"];
					$destdist=$row["distance"];
					$destsid=$row["stationid"];
					$destspeed=$row["speed"];
					$counter5=$counter5+1;
				}
			}
		}

		if($result11->num_rows > 0) {
			while($row=$result11->fetch_assoc())
			{
				if($row["stationid"]>=49 AND $row["stationid"]<=73 OR $row["stationid"]==0)
				{
					$sourcename=$row["name"];
					$sourcedist=$row["distance"];
					$sourcesid=$row["stationid"];
					$sourcespeed=$row["speed"];
					$counter6=$counter6+1;
				}
			}
		}

		if($result12->num_rows > 0) {
			while($row=$result12->fetch_assoc())
			{
				if($row["stationid"]>=30 AND $row["stationid"]<=48)
				{
					$destname=$row["name"];
					$destdist=$row["distance"];
					$destsid=$row["stationid"];
					$destspeed=$row["speed"];
					$counter6=$counter6+1;
				}
			}
		}

		if($counter1==2)
		{
			if($sourcesid>=57 AND $sourcesid<=73 AND ($destsid>=11 AND $destsid<=29 OR $destsid==0))
			{
				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>='$sourcesid' AND  destid<=56";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}

				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=56 ORDER BY distance DESC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
					$middist2 = $res['distance'];
				}
				echo "Change trains here "."<br>";
				// $time = abs(($source_distance - $middist2))*1.25;

				$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<=56 AND  destid=19";
				$ressult1=mysqli_query($conn,$querry1);
				while($ress1=mysqli_fetch_array($ressult1))
				{
					echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
				}
				// $time = $time + (abs(($middist2 - $middist3))*1.3) + ((abs($middist3 - $destination_distance)*1.3));
				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>=11 AND stationid<='$destsid' AND harbour=1 ORDER BY distance ASC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}	
				if($destsid>19)
				{
					echo "Change trains here"."<br>";
					$querry2="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<=19 AND destid>='$destsid' AND speed='slow' AND route='western'";
					$ressult2=mysqli_query($conn,$querry2);
					while($ress2=mysqli_fetch_array($ressult2))
					{
						echo "Take a ".$ress2['start']."-".$ress2['dest']."(".$ress2['speed'].")<br>";
					}

					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>19 AND stationid<='$destsid' ORDER BY distance ASC";
					$result7=mysqli_query($conn,$query_7);
					while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}	
				}
			}


			if($sourcesid>=57 AND $sourcesid<=73 AND $destsid>=1 AND $destsid<=10)
			{
				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>='$sourcesid' AND  destid<=56";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}
			
				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=56 ORDER BY distance DESC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
				echo "Change trains here "."<br>";

				$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<=56 AND  destid=19";
				$ressult1=mysqli_query($conn,$querry1);
				while($ress1=mysqli_fetch_array($ressult1))
				{
					echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
				}

				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				echo "Mahim (Change trains here)"."<br>";
				$stationsmiddle+=1;
				$querry2="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>=11 AND  destid=1 AND speed='slow'";
				$ressult2=mysqli_query($conn,$querry2);
				while($ress2=mysqli_fetch_array($ressult2))
				{
					echo "Take a ".$ress2['start']."-".$ress2['dest']."(".$ress2['speed'].")<br>";
				}

				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<11 AND stationid>='$destsid' ORDER BY distance DESC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}	
			}
			if($sourcesid>=49 AND $sourcesid<=56 AND ($destsid>=11 AND $destsid<=29 OR $destsid==0))
			{
				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<='$sourcesid' AND  destid=19";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}
			
				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=56 ORDER BY distance ASC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>=11 AND stationid<='$destsid' AND harbour=1 ORDER BY distance ASC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
				if($destsid>19)
				{
					echo "Change trains here"."<br>";
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<=19 AND  destid>='$destsid' AND speed='slow' AND route='western'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}

					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>19 AND stationid<='$destsid' ORDER BY distance ASC";
					$result7=mysqli_query($conn,$query_7);
					while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}	
				}
			}

			if($sourcesid>=49 AND $sourcesid<=56 AND $destsid>=1 AND $destsid<=10)
			{
				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<='$sourcesid' AND  destid=19";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}
				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=56 ORDER BY distance ASC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				echo "Mahim (Change trains here)"."<br>";
				$stationsmiddle+=1;
				$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>=11 AND  destid<='$destsid' AND speed='slow' AND route='western'";
				$ressult1=mysqli_query($conn,$querry1);
				while($ress1=mysqli_fetch_array($ressult1))
				{
					echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
				}
				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<11 AND stationid>='$destsid' ORDER BY distance DESC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
			}
		}

		if($counter2==2)
		{
			if(($sourcesid>=11 AND $sourcesid<=29  OR $sourcesid==0) AND $destsid>=57 AND $destsid<=73)
			{
				if($sourcesid>19)
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>='$sourcesid' AND  destid<=19 AND speed='slow' AND route='western'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{	
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}

					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>=19 AND stationid<='$sourcesid' ORDER BY distance DESC";
					$result7=mysqli_query($conn,$query_7);
					while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
					echo "Change trains here"."<br>";
				}
				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=19 AND  destid=49";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}

				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid!=19 AND stationid>=11 AND harbour=1 ORDER BY distance DESC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				echo "Wadala (Change trains here)<br>";
				$stationsmiddle+=1;
				$querry2="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<=56 AND  destid>='$destsid'";
				$ressult2=mysqli_query($conn,$querry2);
				while($ress2=mysqli_fetch_array($ressult2))
				{
					echo "Take a ".$ress2['start']."-".$ress2['dest']."(".$ress2['speed'].")<br>";
				}

				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>56 AND stationid<='$destsid' ORDER BY distance ASC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}	
			}

			if($sourcesid>=1 AND $sourcesid<=10 AND $destsid>=57 AND $destsid<=73)
			{
				$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<='$sourcesid' AND  destid>=11 AND speed='slow' AND route='western'";
				$ressult1=mysqli_query($conn,$querry1);
				while($ress1=mysqli_fetch_array($ressult1))
				{
					echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
				}

				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=11 ORDER BY distance ASC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}

				echo "Change trains here "."<br>";
				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=19 AND  destid=49";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "-".$ress['start']."-".$ress['dest']."-".$ress['speed']."<br>";
				}
				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				echo "Wadala (Change trains here)"."<br>";
				$stationsmiddle+=1;
				$querry2="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<=56 AND  destid>='$destsid'";
				$ressult2=mysqli_query($conn,$querry2);
				while($ress2=mysqli_fetch_array($ressult2))
				{
					echo "Take a ".$ress2['start']."-".$ress2['dest']."(".$ress2['speed'].")<br>";
				}
				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>56 AND stationid<='$destsid' ORDER BY distance ASC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}	
			}

			if(($sourcesid>=11 AND $sourcesid<=29 OR $sourcesid==0) AND $destsid>=49 AND $destsid<=56)
			{
				if($sourcesid>19)
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>='$sourcesid' AND  destid<=19 AND speed='slow' AND route='western'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{	
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}

					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>=19 AND stationid<='$sourcesid' ORDER BY distance DESC";
					$result7=mysqli_query($conn,$query_7);
					while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
					echo "Change trains here"."<br>";
				}

				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=19 AND  destid=49";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}

				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid!=19 AND stationid>=11 AND harbour=1 ORDER BY distance DESC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}

				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				echo "Wadala"."<br>";
				$stationsmiddle+=1;
				if($destsid!=56)
				{

					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<56 AND stationid>='$destsid' ORDER BY distance DESC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}

			if(($sourcesid>=1 AND $sourcesid<=10) AND $destsid>=49 AND $destsid<=56)
			{
				$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid<='$sourcesid' AND  destid>=11 AND speed='slow' AND route='western'";
				$ressult1=mysqli_query($conn,$querry1);
				while($ress1=mysqli_fetch_array($ressult1))
				{
					echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
				}

				$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=10 ORDER BY distance ASC";
				$result5=mysqli_query($conn,$query_5);
				while($res=mysqli_fetch_array($result5))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
				echo "Mahim (Change trains here)"."<br>";
				$stationsmiddle+=1;

				$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=19 AND  destid=49";
				$ressult=mysqli_query($conn,$querry);
				while($ress=mysqli_fetch_array($ressult))
				{
					echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
				}

				echo "Kings Circle"."<br>";
				$stationsmiddle+=1;
				$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<=56 AND stationid>='$destsid' ORDER BY distance DESC";
				$result6=mysqli_query($conn,$query_6);
				while($res=mysqli_fetch_array($result6))
				{
					echo $res['name']."<br>";
					$stationsmiddle+=1;
				}
			}
		}

		if($counter3==2)
		{
			if($sourcesid>9)
			{
				if($sourcespeed=="Fast")
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>='$sourcesid' AND  destid=1 AND speed='fast'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=9 AND speed='Fast' ORDER BY distance DESC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>='$sourcesid' AND  destid=1 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=9 ORDER BY distance DESC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
			else
			{
				if($sourcespeed=="Fast")
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=1 AND  destid>='$sourcesid' AND speed='fast'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}

					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=9 AND speed='Fast' ORDER BY distance ASC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=1 AND  destid>='$sourcesid' AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=9 ORDER BY distance ASC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
			echo "Change trains here "."<br>";
			if($destsid>37)
			{
				if($destspeed=="Fast")
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND  destid=48 AND speed='fast'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$destsid' AND stationid>37 AND speed='Fast' ORDER BY distance ASC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND  destid=48 AND speed='slow'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>37 AND stationid<='$destsid' ORDER BY distance ASC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
			else
			{
				if($destspeed=="Fast")
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND  destid=30 AND speed='fast'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$destsid' AND stationid<37 AND speed='Fast' ORDER BY distance DESC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND  destid=30 AND speed='slow'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<37 AND stationid>='$destsid' ORDER BY distance DESC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
		}

		if($counter4==2)
		{
			if($sourcesid>37)
			{
				if($sourcespeed=="Fast")
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND  destid=30 AND speed='fast'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=37 AND speed='Fast' ORDER BY distance DESC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND  destid=30 AND speed='slow'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=37 ORDER BY distance DESC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
			else
			{
				if($sourcespeed=="Fast")
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND  destid=48 AND speed='fast'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=37 AND speed='Fast' ORDER BY distance ASC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry1="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND  destid=48 AND speed='slow'";
					$ressult1=mysqli_query($conn,$querry1);
					while($ress1=mysqli_fetch_array($ressult1))
					{
						echo "Take a ".$ress1['start']."-".$ress1['dest']."(".$ress1['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=37 ORDER BY distance ASC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}

			echo "Change trains here "."<br>";

			if($destsid>9)
			{
				if($destspeed=="Fast")
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=1 AND  destid>='$destsid' AND speed='fast'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$destsid' AND stationid>9 AND speed='Fast' ORDER BY distance ASC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=1 AND  destid>='$destsid' AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>9 AND stationid<='$destsid' ORDER BY distance ASC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
			else
			{
				if($destspeed=="Fast")
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>=9 AND  destid=1 AND speed='fast'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$destsid' AND stationid<9 AND speed='Fast' ORDER BY distance DESC";
					$result7=mysqli_query($conn,$query_7);
			    	while($res=mysqli_fetch_array($result7))
					{
						echo $res['name']."<br>";
						$faststationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid>=9 AND  destid=1 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<9 AND stationid>='$destsid' ORDER BY distance DESC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
		}

		if($counter5==2)
		{
			if($sourcesid>=30 AND $sourcesid<=32 AND $destsid>=49 AND $destsid<=51)
			{
				$sourcesid1=$sourcesid-30;
				$destsid1=$destsid-49;
				$diff2=$destsid1-$sourcesid1;
				if($sourcesid1<$destsid1)
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE (startid=30 OR startid=49) AND (destid=48 OR destid=19 or destid=73) AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}

					$newdest=$sourcesid+$diff2;
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<='$newdest' ORDER BY distance ASC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE (startid=48 OR startid=19 OR startid=73) AND (destid=30 OR destid=49) AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}

					$newdest=$sourcesid+$diff2;
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>='$newdest' ORDER BY distance DESC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}
			else
			{
				if($sourcesid>40)
				{
					if($sourcespeed=="Fast")
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND destid=30 AND speed='fast'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=40 AND speed='Fast' ORDER BY distance DESC";
						$result7=mysqli_query($conn,$query_7);
			    		while($res=mysqli_fetch_array($result7))
						{
							echo $res['name']."<br>";
							$faststationsmiddle+=1;
						}
					}
					else
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND destid=30 AND speed='slow'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=40 ORDER BY distance DESC";
			    		$result5=mysqli_query($conn,$query_5);
			    		while($res=mysqli_fetch_array($result5))
						{
							echo $res['name']."<br>";
							$stationsmiddle+=1;
						}
					}
				}
				else
				{
					if($sourcespeed=="Fast")
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND destid=48 AND speed='fast'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=40 AND speed='Fast' ORDER BY distance ASC";
						$result7=mysqli_query($conn,$query_7);
			    		while($res=mysqli_fetch_array($result7))
						{
							echo $res['name']."<br>";
							$faststationsmiddle+=1;
						}
					}
					else
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND destid=48 AND speed='slow'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=40 ORDER BY distance ASC";
			    		$result5=mysqli_query($conn,$query_5);
			    		while($res=mysqli_fetch_array($result5))
						{
							echo $res['name']."<br>";
							$stationsmiddle+=1;
						}
					}
				}
				echo "Change trains here "."<br>";
				if($destsid>59)
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=49 AND destid=73 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>59 AND stationid<='$destsid' ORDER BY distance ASC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				else if($destsid<59 AND $destsid>=49)
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=73 AND destid=49 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<59 AND stationid>='$destsid' ORDER BY distance DESC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=73 AND destid=49 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<59 AND stationid>=56 ORDER BY distance DESC";
					$result6=mysqli_query($conn,$query_6);
					while($res=mysqli_fetch_array($result6))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
					echo "Change trains here "."<br>";
					$querry3="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=49 AND destid=19 AND speed='slow'";
					$ressult3=mysqli_query($conn,$querry3);
					while($ress3=mysqli_fetch_array($ressult3))
					{
						echo "Take a ".$ress3['start']."-".$ress3['dest']."(".$ress3['speed'].")<br>";
					}
					echo "Kings Circle"."<br>";
					$stationsmiddle+=1;
				}
			}
		}

		if($counter6==2)
		{
			if($sourcesid>=49 AND $sourcesid<=51 AND $destsid>=30 AND $destsid<=32)
			{
				$sourcesid1=$sourcesid-49;
				$destsid1=$destsid-30;
				$diff2=$destsid1-$sourcesid1;
				if($sourcesid1<$destsid1)
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE (startid=30 OR startid=49) AND (destid=48 OR destid=19 or destid=73) AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$newdest=$sourcesid+$diff2;
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<='$newdest' ORDER BY distance ASC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				else
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE (startid=48 OR startid=19 OR startid=73) AND (destid=30 OR destid=49) AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$newdest=$sourcesid+$diff2;
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>='$newdest' ORDER BY distance DESC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
			}

			else
			{
				if($sourcesid>59)
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=73 AND destid=49 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$sourcesid' AND stationid>=59 ORDER BY distance DESC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				else if($sourcesid<=59 AND $sourcesid>=49)
				{
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=49 AND destid=73 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$sourcesid' AND stationid<=59 ORDER BY distance ASC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				else
				{
					$querry4="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=19 AND destid=49 AND speed='slow'";
					$ressult4=mysqli_query($conn,$querry4);
					while($ress4=mysqli_fetch_array($ressult4))
					{
						echo "-".$ress4['start']."-".$ress4['dest']."-".$ress4['speed']."<br>";
					}
					echo "Kings Circle"."<br>";
					$stationsmiddle+=1;
					echo "Wadala (Change trains here)"."<br>";
					$stationsmiddle+=1;
					$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=49 AND destid=73 AND speed='slow'";
					$ressult=mysqli_query($conn,$querry);
					while($ress=mysqli_fetch_array($ressult))
					{
						echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
					}
					$query_5="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>56 AND stationid<=59 ORDER BY distance ASC";
			    	$result5=mysqli_query($conn,$query_5);
			    	while($res=mysqli_fetch_array($result5))
					{
						echo $res['name']."<br>";
						$stationsmiddle+=1;
					}
				}
				echo "Change trains here "."<br>";
				if($destsid>40)
				{
					if($destspeed=="Fast")
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND destid=48 AND speed='fast'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<='$destsid' AND stationid>40 AND speed='Fast' ORDER BY distance ASC";
						$result7=mysqli_query($conn,$query_7);
			    		while($res=mysqli_fetch_array($result7))
						{
							echo $res['name']."<br>";
							$faststationsmiddle+=1;
						}
					}
					else
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=30 AND destid=48 AND speed='slow'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>40 AND stationid<='$destsid' ORDER BY distance ASC";
						$result6=mysqli_query($conn,$query_6);
						while($res=mysqli_fetch_array($result6))
						{
							echo $res['name']."<br>";
							$stationsmiddle+=1;
						}
					}
				}
				else
				{
					if($destspeed=="Fast")
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND destid=30 AND speed='fast'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_7="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid>='$destsid' AND stationid<40 AND speed='Fast' ORDER BY distance DESC";
						$result7=mysqli_query($conn,$query_7);
			    		while($res=mysqli_fetch_array($result7))
						{
							echo $res['name']."<br>";
							$faststationsmiddle+=1;
						}
					}
					else
					{
						$querry="SELECT TrainID, start, dest, startid, destid, speed FROM trains WHERE startid=48 AND destid=30 AND speed='slow'";
						$ressult=mysqli_query($conn,$querry);
						while($ress=mysqli_fetch_array($ressult))
						{
							echo "Take a ".$ress['start']."-".$ress['dest']."(".$ress['speed'].")<br>";
						}
						$query_6="SELECT stationid, name, central, western, harbour, speed, distance FROM stations WHERE stationid<40 AND stationid>='$destsid' ORDER BY distance DESC";
						$result6=mysqli_query($conn,$query_6);
						while($res=mysqli_fetch_array($result6))
						{	
							echo $res['name']."<br>";
							$stationsmiddle+=1;
						}
					}
				}
			}
		}		
	}
	if($source_line==$destination_line)
	{
		$distance=abs($source_distance-$destination_distance);
		if($distance<=7)
		{
			$price=5;
		}
		else if($distance<=14)
		{
			$price=10;
		}
		else if($distance<=21)
		{
			$price=15;
		}
		else if($distance<=28)
		{
			$price=20;
		}
		else
		{
			$price=25;
		}
	}
	else
	{
		// echo "Stations in between = "."$stationsmiddle"."<br>";
		// echo "Fast stations in between = "."$faststationsmiddle"."<br>";
		if($source_speed == "Fast" & $destination_speed == "Fast")
		{
			$time = ($stationsmiddle + $faststationsmiddle)*6.5;
		}
		else
		{
			$time = ($stationsmiddle + $faststationsmiddle)*5;
		}
		echo "Total time taken for the journey is approximately "."$time "."minutes";
		if($stationsmiddle<=5)
		{
			$price1=5;
		}
		else if($stationsmiddle<=10)
		{
			$price1=10;
		}
		else if($stationsmiddle<=15)
		{
			$price1=15;
		}
		else if($stationsmiddle<=20)
		{
			$price1=20;
		}
		else if($stationsmiddle<=25)
		{
			$price1=25;
		}
		else if($stationsmiddle<=30)
		{
			$price1=30;
		}
		else
		{
			$price1=40;
		}

		if($faststationsmiddle<=2)
		{
			$price2=5;
		}
		else if($faststationsmiddle<=4)
		{
			$price2=10;
		}
		else if($faststationsmiddle<=6)
		{
			$price2=15;
		}
		else if($faststationsmiddle<=8)
		{
			$price2=20;
		}
		else
		{
			$price2=25;
		}
		$price=$price1+$price2;
    }

	session_start();

	if(isset($_SESSION["price"]) and isset($_SESSION["username"]))
	{
		echo "<br><br><b>You are limited to one ticket per person</b><br>";
		echo "<form action='logout.php'><div style='padding-top: 40px;'><button class='submitb'>Logout</button></div></form>";
		echo "<form action='plan.php'><div><button class='submitb'>Back</button></div></form>";
	}
	else if(isset($_SESSION["username"]) and !isset($_SESSION["price"]))
	{
		echo "<form action='book.php'> <div style='padding-top: 40px;'><button class='submitb'>Book Ticket</button></div> </form>";
		echo "<form action='back.php'> <div><button class='submitb'>Back</button></div> </form>";
		$_SESSION["source"]=$source;
		$_SESSION["destination"]=$destination;
	    $_SESSION["price"]=$price;
	}
	else
	{
		echo "<form action='book.php'> <div style='padding-top: 40px;'><button class='submitb'>Book Ticket</button></div> </form>";
		echo "<form action='plan.php'> <div><button class='submitb'>Back</button></div> </form>";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Your Commute Plan</title>
	<link rel="preconnect" href="https://fonts.gstatic.com/%22%3E">
	<link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
	<style>
		.submitb {
			position: relative;
			margin: 0 auto;
			padding: 10px;
			border: .15em solid #3ab09e;
			border-radius: 5em;
			text-align: center;
			font-size: 0.8em;
			line-height: 2em;
			height: 70px;
			width: 200px;
		}
		.submitb:hover {
			background-color: #3ab09e;
			cursor: pointer;
		  	color: white;
		  	transition-duration: 0.4s;
		}
	</style>
</head>
<body style="text-align: center; margin-top: 100px; background-image: url('images/bg1.png');
    color: black; font-family:'lexend'; font-size: 30px; font: ariel; background-repeat: no-repeat;">

</body>
</html>