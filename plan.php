<?php
session_start();
if(isset($_SESSION["username"]))
{
echo "<a style='float: right; margin-top: 10px; margin-right: 2.5px;' href='logout.php'>Logout</a>";
echo "<p style='float: right; margin: 10px 10px; font-size: 20px;color:#eff3f0';>Logged in: " . $_SESSION["username"] . "</p>";
}

else
{
echo "<p style='float: right; margin: 10px 10px; font-size: 18px;color:#eff3f0';>Not logged in</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Plannr.</title>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
<!-- <link rel="stylesheet" type="text/css" href="plan-style.css"> -->
<style type="text/css">
.navbar-brand {
   font-size: 1.65rem;
   padding-left: 20px ;
   font-family: 'Alegreya Sans SC', sans-serif;
   letter-spacing: 0.1em;
}
/*@import url('https://fonts.googleapis.com/css2?family=Yellowtail&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Itim&display=swap%27');*/

body{
/*background: linear-gradient(to right,#2c5364, #203a43, #0f2027);
color: #3ab09e;*/
/*background-color: black;*/
}

html,body {
                margin:0;
                padding:0;
                overflow-x:hidden;
            }

h1{

font-family: 'Lexend';
color: #4c9ab6;
text-align: center;
font-size: 50px;
margin-bottom: 0px;
margin-top: 20px;
margin-left: 145px;
position:relative;
left: 420px;
}

h2{
font-family: 'Yellowtail', cursive;
text-align: center;
font-size: 20px;
}

.inputs {
  font-family: 'Roboto', sans-serif;
color: #333;
  font-size: 25px;
padding: 20px;
  border-radius: 10px;
  background-color: white;
  border: none;
  width: 300px;
  display: inline;
  height: 65px;
 
}

.an {
position: relative;
animation: change2 .5s;
}

.navbar {
padding-bottom: 25px;
}

a {
    color: black;
    padding: 10px;
    border-radius: 5px;
    background: #eff3f0;
    text-decoration: none;
  }

a:hover {
  cursor: pointer;
  color: #203a43;
  transition-duration: 0.5s;
}

.submitb {
position: relative;
margin: 0 auto;
border: .15em solid #3ab09e;
border-radius: 5em;
text-align: center;
font-size: 1.3em;
line-height: 2em;
}
.submitb:hover {
background-color: #3ab09e;
cursor: pointer;
  color: white;
  transition-duration: 0.4s;
 
}

.liner{
display: inline-block;
padding-right: 20px;
float: right;
}

#formsec{
text-align: center;
padding-top: 16px;
}

#sourcetext{
margin-left: 30px;
margin-bottom: 12px;
}

#destinationtext{
margin-bottom: 12px;
}

footer {
background-color: black;
color: white;
font-size: 30px;
text-align: center;
}

.im {
border: 8px black solid;
border-radius: 10px;
}

#ImageMap{
padding: 20px;
position: relative;
animation: change1 .5s;
}

@keyframes change1 {
0%   {left: 100px; }
100% {left: 0px; }
}

@keyframes change2 {
0%   {right: 100px; }
100% {right: 0px; }
}
#top{
background-color: black;
}
main{
background-color: #eff3f0;
}
#right{
position: relative;
left: 933px;
}
</style>
</head>

<body>
<!-- <nav class="navbar navbar-dark navbar-expand-md bg-dark sticky-top">
      <a href="plan.php" class="navbar-brand">Planner</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navLinks">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navLinks">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="login.html" class="nav-link">LOGIN</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">ABOUT</a>
          </li>
        </ul>
      </div>

    </nav> -->
    <div id="top">

<section class="navbar">
<nav align="right">
<header>
<!-- <h1 class="lead display-3" >Plan your commute</h1> -->
<h1 align="center">Plan your commute</h1>
</header>
<ul id="right">
<?php
if(isset($_SESSION["username"]))
{
if(isset($_SESSION["price"]))
{
echo "<li class='liner'><a href='ticket.php'>View Ticket</a></li>";
echo "<li class='liner'><a href='reset.php'>Reset Ticket</a></li>";
}
}
else if(!isset($_SESSION["username"]))
echo "<li class='liner'><a href='login.html'>Login/Sign Up</a></li>";
?>
<li class="liner"><a href="about.html">About us</a></li>
</ul>
</nav>
</section>
</div>
<main>
<section id="formsec">
<div class="an">
<form action="calc.php" method="post">
<br>
   <input type="text" id="sourcetext" class="inputs" name="source" placeholder="Source">
   <input type="text" id="destinationtext" class="inputs" name="destination" placeholder="Destination"><br>
   <button class="btn btn-dark ">Submit</button>  
   <input type="button" class=" btn btn-dark " value="Reset" onclick="Reset()">
</form>
<div class="Reset">  </div>
</div>
<div id="ImageMap">
   <img src="images/TMap.jpg" alt="Mumbai Train Map" usemap="#TMap" class="im">
    <map name="TMap">
    <area shape="rect" coords="45,1105,226,1140" onclick="SelectfromMap('Churchgate');">
    <area shape="rect" coords="110,1078,225,1093" onclick="SelectfromMap('Marine Lines');">
    <area shape="rect" coords="112,1034,225,1053" onclick="SelectfromMap('Charni Road');">
    <area shape="rect" coords="120,993,225,1012" onclick="SelectfromMap('Grant Road');">
    <area shape="rect" coords="49,945,230,976" onclick="SelectfromMap('Mumbai Central')">
    <area shape="rect" coords="120,908,210,929" onclick="SelectfromMap('Mahalaxmi')">
    <area shape="rect" coords="120,869,210,888" onclick="SelectfromMap('Lower Parel')">
    <area shape="rect" coords="80,825,210,847" onclick="SelectfromMap('Prabhadevi')">
    <area shape="poly" coords="155,719,250,719,292,748,270,778,240,750,115,750" onclick="SelectfromMap('Dadar W')">
    <area shape="poly" coords="394,785,394,814,310,814,275,782,290,755,325,780" onclick="SelectfromMap('Dadar C')">
    <area shape="rect" coords="158,668,268,686" onclick="SelectfromMap('Matunga Road')">
    <area shape="rect" coords="150,598,268,647" onclick="SelectfromMap('Mahim')">
    <area shape="rect" coords="110,545,310,580" onclick="SelectfromMap('Bandra')">
    <area shape="rect" coords="170,484,266,502" onclick="SelectfromMap('Khar Road')">
    <area shape="rect" coords="170,445,266,464" onclick="SelectfromMap('Santacruz')">
    <area shape="rect" coords="175,415,270,426" onclick="SelectfromMap('Vile Parle')">
    <area shape="rect" coords="229,363,287,390" onclick="SelectfromMap('Andheri')">
    <area shape="rect" coords="180,336,280,348" onclick="SelectfromMap('Jogeshwari')">
    <area shape="rect" coords="145,305,280,328" onclick="SelectfromMap('Goregaon')">
    <area shape="rect" coords="145,277,280,300" onclick="SelectfromMap('Malad')">
    <area shape="rect" coords="195,255,280,272" onclick="SelectfromMap('Kandivali')">
    <area shape="rect" coords="145,215,285,242" onclick="SelectfromMap('Borivali')">
    <area shape="rect" coords="215,190,280,205" onclick="SelectfromMap('Dahisar')">
    <area shape="rect" coords="195,160,280,177" onclick="SelectfromMap('Mira Road')">
    <area shape="rect" coords="140,125,280,150" onclick="SelectfromMap('Bhayandar')">
    <area shape="rect" coords="205,105,280,119" onclick="SelectfromMap('Nalgaon')">
    <area shape="rect" coords="130,70,288,97" onclick="SelectfromMap('Vasai Road')">
    <area shape="rect" coords="185,46,280,63" onclick="SelectfromMap('Nallasopara')">
    <area shape="rect" coords="145,10,288,41" onclick="SelectfromMap('Virar')">
    <area shape="rect" coords="259,1089,480,1115" onclick="SelectfromMap('CSMT C')">
    <area shape="rect" coords="259,1059,360,1070" onclick="SelectfromMap('Masjid C')">
    <area shape="rect" coords="283,1006,475,1035" onclick="SelectfromMap('Sandhurst Road C')">
    <area shape="rect" coords="283,953,355,966" onclick="SelectfromMap('Byculla')">
    <area shape="rect" coords="283,912,380,926" onclick="SelectfromMap('Chinchpokli')">
    <area shape="rect" coords="283,872,380,888" onclick="SelectfromMap('Currey Road')">
    <area shape="rect" coords="283,833,345,847" onclick="SelectfromMap('Parel')">
    <area shape="rect" coords="280,702,356,718" onclick="SelectfromMap('Matunga')">
    <area shape="rect" coords="365,642,417,660" onclick="SelectfromMap('Sion')">
    <area shape="poly" coords="460,588,445,610,470,627,485,607" onclick="SelectfromMap('Kurla C')">
    <area shape="rect" coords="485,622,509,649" onclick="SelectfromMap('Kurla H')">
    <area shape="rect" coords="425,550,509,569" onclick="SelectfromMap('Vidyavihar')">
    <area shape="poly" coords="555,497,539,512,563,538,582,523" onclick="SelectfromMap('Ghatkopar')">
    <area shape="rect" coords="530,470,595,486" onclick="SelectfromMap('Vikhroli')">
    <area shape="rect" coords="530,445,619,463" onclick="SelectfromMap('Kanjur Marg')">
    <area shape="rect" coords="570,430,645,440" onclick="SelectfromMap('Bhandup')">
    <area shape="rect" coords="615,407,659,415" onclick="SelectfromMap('Nahur')">
    <area shape="rect" coords="628,382,694,390" onclick="SelectfromMap('Mulund')">
    <area shape="poly" coords="710,347,695,360,730,395,745,380,720,350" onclick="SelectfromMap('Thane')">
    <area shape="rect" coords="343,967,460,979" onclick="SelectfromMap('Dockyard Road')">
    <area shape="rect" coords="385,927,472,943" onclick="SelectfromMap('Reay Road')">
    <area shape="rect" coords="425,877,528,893" onclick="SelectfromMap('Cotton Green')">
    <area shape="rect" coords="425,823,478,836" onclick="SelectfromMap('Sewri')">
    <area shape="rect" coords="417,773,580,802" onclick="SelectfromMap('Wadala')">
    <area shape="rect" coords="325,742,413,757" onclick="SelectfromMap('Kings Circle')">
    <area shape="rect" coords="425,715,510,729" onclick="SelectfromMap('GTB Nagar')">
    <area shape="rect" coords="450,672,545,683" onclick="SelectfromMap('Chunnabhatti')">
    <area shape="poly" coords="580,560,525,627,552,627,602,560" onclick="SelectfromMap('Tilaknagar')">
    <area shape="poly" coords="610,560,558,628,582,628,628,560" onclick="SelectfromMap('Chembur')">
    <area shape="poly" coords="638,560,590,628,616,628,648,560" onclick="SelectfromMap('Govandi')">
    <area shape="poly" coords="671,560,620,628,642,628,695,560" onclick="SelectfromMap('Mankhurd')">
    <area shape="rect" coords="675,592,703,633" onclick="SelectfromMap('Vashi')">
    <area shape="rect" coords="724,600,738,631" onclick="SelectfromMap('Sanpada')">
    <area shape="rect" coords="734,680,762,667" onclick="SelectfromMap('Juinagar')">
    <area shape="rect" coords="710,690,770,720" onclick="SelectfromMap('Nerul')">
    <area shape="rect" coords="760,740,745,760" onclick="SelectfromMap('Seawoods Darave')">
    <area shape="rect" coords="817,735,844,763" onclick="SelectfromMap('Belapur CBD')">
    <area shape="rect" coords="896,777,909,787" onclick="SelectfromMap('Kharghar')">
    <area shape="rect" coords="927,807,938,819" onclick="SelectfromMap('Mansarovar')">
    <area shape="circle" coords="1000,875,22" onclick="SelectfromMap('Panvel')">
    </map>
</div>
</section>
</main>
<footer>©Planner</footer>
</body>

<script>
var counter = 0;

function SelectfromMap(p)
{
if(counter==0)
{
SourceMap(p);
counter = counter+1;
}
else if(counter==1)
{
DestinationMap(p);
counter = counter+1;
}
}

function SourceMap(p)
{
document.getElementById("sourcetext").value = p;
}

function DestinationMap(p)
{
document.getElementById("destinationtext").value = p;
}

function Reset(){
document.getElementById("destinationtext").value = null;
document.getElementById("sourcetext").value = null;
counter = 0;
}
</script>
</html>

