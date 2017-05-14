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



body {
    height: 100%;
}

body {
		background-color: LightSkyBlue;  
/*
    background: url("images/DSCF1356.JPG")no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
  */
}


.navbar-nav {
    color: #FFFFFF;
    display: inline-block;
    vertical-align: middle;
    float: none;
    background-image: none;
}

.form-horizontal {
    margin: 10px 10px;
}

.table {
    margin-left: 10px;
    margin-right: 10px;
}




.find-tables th {
  padding: 1px !important;

  text-transform:uppercase;
  text-align: left;
 
  background: rgb(229,76,16);
  background: -webkit-linear-gradient(rgb(229,76,16), rgb(173,54,8));
  background: -moz-linear-gradient(rgb(229,76,16), rgb(173,54,8));
  background: -o-linear-gradient(rgb(229,76,16), rgb(173,54,8));
  background: linear-gradient(rgb(229,76,16), rgb(173,54,8));
  color: black;
}
.find-tables tr:nth-of-type(even){
  //background-color: rgba(255,255,255,.1);
  background-color: #b8d1f3;
}
.find-tables tr:nth-of-type(odd){
  //background-color: rgba(229,76,16,.1);
    background-color: #dae5f4;
}
	</style>	
	
	 <!-- <link rel="stylesheet" type="text/css" href="css/jez.css" /> -->
		

</head>

<body>





	
	
	
	

<nav class="navbar navbar-pills navbar-inverse navbar-fixed-top">
	<div class="container-fluid">

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse"
			id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><p class="navbar-text">Locus Navigator</p></li>
				<li><a href="#" class="arrow-nav" id="bFirst">First</a></li>
				<li><a href="#" class="arrow-nav" id="bPrev">Prev</a></li>
				<li><a href="#" class="arrow-nav" id="bNext">Next</a></li>
				<li><a href="#" class="arrow-nav" id="bLast">Last</a></li>
				<!--  <li><p class="navbar-text">| Area:</p></li>-->
				<li class="dropdown"><a href="#" class="dropdown-toggle"
					id="areas_dropdown_toggle" data-toggle="dropdown" role="button"
					aria-haspopup="true" aria-expanded="false">Area<span class="caret"></span></a>
					<ul class="dropdown-menu  area_dropdown">
						<?php foreach ( $areas as $a ) { ?>
							<li><a href="#"><?php echo $a["YYYY"] . '.' . $a["AreaName"]; ?></a></li>
						<?php } ?>			
						</ul></li>

				<li class="dropdown"><a href="#" class="dropdown-toggle"
					id="loci_dropdown_toggle" data-toggle="dropdown" role="button"
					aria-haspopup="true" aria-expanded="false">Locus<span class="caret"></span></a>
					<ul class="dropdown-menu loci_dropdown" id="loci_list">
					</ul></li>
				<li><a href="#" class="arrow-nav" id="bGo">GO!</a></li>

				<li>

					<form class="navbar-form navbar-left">
						<div class="form-group">
							<label class="checkbox-inline"> <input type="checkbox" value="">PT</label> 
							<label class="checkbox-inline"> <input type="checkbox" value="">All finds
							</label> <label class="checkbox-inline"> <input type="checkbox"	value="">Images</label>
						</div>
					</form>
				</li>
			</ul>







			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">Link</a></li>
				<li class="dropdown"><a href="#" class="dropdown-toggle"
					data-toggle="dropdown" role="button" aria-haspopup="true"
					aria-expanded="false">Dropdown <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#">Action</a></li>
						<li><a href="#">Another action</a></li>
						<li><a href="#">Something else here</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="#">Separated link</a></li>
					</ul></li>
			</ul>
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container-fluid -->
</nav>

<br><br>









		<form class="form-horizontal">
			<fieldset>

				<!-- Form Name -->

				<div class="form-group">
				
					<div class="col-lg-2">
						<label class="col-lg-1 control-label" for="locus_name">Locus</label>
						<input id="locus_name" name="locus_name" type="text" class="form-control input-sm">
					</div>

					<div class="col-lg-2">
						<label class="col-lg-1 control-label" for="square">Square</label>
						<input id="square" name="square" type="text" class="form-control input-sm">
					</div>

					<div class="col-lg-2">
						<label class="control-label" for="co_existing">co-existing</label> <input
							id="co_existing" name="co_existing" type="text" class="form-control input-sm">
					</div>

					<div class="col-lg-2">
						<label class="col-lg-1 control-label" for="above">above</label>
						<input id="above" name="above" type="text" class="form-control input-sm">
					</div>
					
					<div class="col-lg-2">
						<label class="col-lg-1 control-label" for="below">below</label>
						<input id="below" name="below" type="text" class="form-control input-sm">
					</div>
					
				</div>


				<div class="form-group">
				
					<div class="col-lg-2">
						<label class="control-label" for="date_opened">date opened</label>
						<input id="date_opened" name="date_opened" type="text" class="form-control input-sm">
					</div>

					<div class="col-lg-2">
						<label class="control-label" for="date_closed">date closed</label>
						<input id="date_closed" name="date_closed" type="text" class="form-control input-sm">
					</div>

					<div class="col-lg-2">
						<label class="control-label" for="level_opened">opened level</label>
						<input id="level_opened" name="level_opened" type="text" class="form-control input-sm">
					</div>

					<div class="col-lg-2">
						<label class="control-label" for="level_closed">closed level</label>
						<input id="level_closed" name="level_closed" type="text" class="form-control input-sm">
					</div>
					
					<div class="col-lg-4">
						<label class="control-label" for="find_summary">find summary</label>
						<input id="find_summary" name="find_summary" type="text" class="form-control input-sm">
					</div>

				</div>


				<div class="form-group">
					<div class="col-lg-4">
						<label class="control-label" for="description">description</label>
						<textarea id="description" class="form-control" rows="3"></textarea>
					</div>
				
					<div class="col-lg-4">
						<label class="control-label" for="notes">notes</label>
						<textarea id="notes" class="form-control" rows="3"></textarea>
					</div>
				
					<div class="col-lg-4">
						<label class="control-label" for="registration">registration</label>
						<textarea id="registration" class="form-control" rows="3"></textarea>
					</div>
				</div>

			</fieldset>
		</form>
	

	
	
	<!-- Finds tables -->
	<table id="pt_table" class="table find-tables">
		<thead>
			<tr>
				<th data-field="PT_no" class="col-lg-1">PT</th>
				<th data-field="Keep" class="col-lg-1">Keep</th>
				<th data-field="Pd_text" class="col-lg-2">Periods</th>
				<th data-field="Description" class="col-lg-2">Description</th>
				<th data-field="Notes" class="col-lg-2">Notes</th>
				<th data-field="PT_date" class="col-lg-1">Date</th>
				<th data-field="Top_lv" class="col-lg-1">Top</th>
				<th data-field="Bot_lv" class="col-lg-1">Bottom</th>
			</tr>
		</thead>
	</table>

	<table id="ar_table" class="table find-tables">
		<thead>
			<tr>
				<th data-field="AR_no" class="col-lg-1">AR</th>
				<th data-field="Related_PT_no" class="col-lg-1">R/T PT</th>
				<th data-field="Category_Name" class="col-lg-3">Category_Name</th>
				<th data-field="Description" class="col-lg-3">Description</th>
				<th data-field="Notes" class="col-lg-2">Notes</th>
				<th data-field="Date" class="col-lg-1">Date</th>				
				<th data-field="Level" class="col-lg-1">level</th>
			</tr>
		</thead>
	</table>

	<table id="lb_table" class="table find-tables">
		<thead>
			<tr>
				<th data-field="LB_no" class="col-lg-1">LB</th>
				<th data-field="Related_PT_no" class="col-lg-1">R/T PT</th>
				<th data-field="Quantity" class="col-lg-1">Quantity</th>
				<th data-field="Category_Name" class="col-lg-4">Category</th>
				<th data-field="Description" class="col-lg-4">Description</th>
				<th data-field="LB_date" class="col-lg-1">Date</th>				
			</tr>
		</thead>
	</table>	
	

	

	<table id="fl_table" class="table find-tables">
		<thead>
			<tr>
				<th data-field="FL_no" class="col-lg-1">FL</th>
				<th data-field="Related_PT_no" class="col-lg-1">R/T PT</th>
				<th data-field="Wt_grams" class="col-lg-1">Wt(milligrams)</th>
				<th data-field="Description" class="col-lg-3">Description</th>
				<th data-field="Notes" class="col-lg-2">Notes</th>
				<th data-field="FL_date" class="col-lg-1">Date</th>				
			</tr>
		</thead>
	</table>

	<table id="gs_table" class="table find-tables">
		<thead>
			<tr>
				<th data-field="GS_no" class="col-lg-1">GS</th>
				<th data-field="Related_PT_no" class="col-lg-1">R/T PT</th>
				<th data-field="Category_Name" class="col-lg-2">Category</th>
				<th data-field="Description" class="col-lg-3">Description</th>
				<th data-field="Notes" class="col-lg-3">Notes</th>
				<th data-field="Date" class="col-lg-1">Date</th>
			</tr>
		</thead>
	</table>
	<br><br>


		

		
		<div class=main-content>
			<!--loci main content (locus form and find tables)-->			
			<?php include("loci-content.php"); ?>
		</div>	
	

	<div id="footer">
		<?php include("footer.inc.php"); ?>
	</div>



	
</body>
</html>
