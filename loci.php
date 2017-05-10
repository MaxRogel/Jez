<?php
//logged-in user access only
session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: index.php");   
}
require_once 'config.php';

//populate $areas list
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$r = $conn -> prepare("SELECT * FROM vareas ORDER BY YYYY, AreaName" );
	$areas = $r-> fetch(PDO::FETCH_ASSOC);
	$r->execute();
	$c = $r->rowCount();
	$areas = $r->fetchAll();
	//print_r($areas);	


?>


<html lang="en">

<head>
	<TITLE>Jez Loci</TITLE>
	
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
  <script src="./js/loci.js"></script>	

	<style>
	
	</style>	
	
	<link rel="stylesheet" type="text/css" href="css/jez.css" />
		

</head>

<body>




	<div class="body-wrappper">
	
	
	
	
	
	<div id="header">
		<?php include("header.inc.php"); ?>
	</div>

	<div class="nav_top">
		<?php include("nav-top.inc.php"); ?>
	</div>

	
	<div class=main-wrapper>
		

		
		<div class=main-content>
			<!--loci main content (locus form and find tables)-->			
			<?php include("loci-content.php"); ?>
		</div>	
	</div>

	<div id="footer">
		<?php include("footer.inc.php"); ?>
	</div>



	
</body>
</html>
