<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
</head>
<style type="text/css">

	body{
	  	font-family:poppins;
	  	background: #005762;
	}
	h2 {
		font-family: 'Yellowtail', cursive;
		position: relative;
		animation: change2 .5s;
	}
	h3 {
		position: relative;
		animation: change1 .5s;
	}

	.logo{
	  	width: 20%;
	  	float: left;
	  	padding: 30px 0 0;
	  	margin-left: 40px;
	  	font-size: 25px;
	  	font-weight: 700;
	  	color: #fff;
	}
	
	.navigation{
	  height: 60px;
	}

	.text-area{
	  text-align: center;
	  width: 75%;
	  margin: 0 auto;
	  padding: 50px;
	
	}
	.text-area h2{
	  font-size: 75px;
	  color: #fff;
	  margin: 0 0 5px;
	}

	.text-area h3{
	  margin-top: 2px;
	  color: #fff;
	  text-transform: uppercase;
	  margin: 0 0 15px;
	  font-size: 35px;
	}

	.text-area a{
	  text-decoration: none;
	  background: #262626;
	  color: #fff;
	  padding: 15px 60px;
	  font-size: 18px;
	  display: inline-block;
	  margin-top: 5%;
	  border-radius: 50px;
	}

	.text-area a:hover {
	  background-color: grey;
	  cursor: pointer;
	  color: black;
	  transition-duration: 0.4s;
	}

	.text-area p{
	  font-size: 18px;
	  color: #fff;
	  width: 70%;
	  margin: 0 auto;
	  line-height:1.9;
	}

	@keyframes change1 {
		0%   {left: 200px; }
  		100% {left: 0px; }
	}

	@keyframes change2 {
		0%   {right: 200px; }
  		100% {right: 0px; top: 0px;}
	}

</style>
<body>
	<div class="navigation">
    	<div class="logo">	©TRJ</div>
    </div>
    <div class="text-area">
        <h2>Planner</h2>
        <h3><em>one stop app</em></h3>
        <p>Our algorithm helps you find the quickest route from the source to the destination of your choosing.</p>
        <a href="plan.php">Get Started</a>
    </div>
</body>
</html>
