

	<nav class="navbar navbar-pills">
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
					<li class="dropdown" >
					<a href="#" class="dropdown-toggle" id="areas_dropdown_toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Area<span class="caret"></span></a>
						<ul class="dropdown-menu  area_dropdown">
						<?php foreach ( $areas as $a ) { ?>
							<li><a href="#"><?php echo $a["YYYY"] . '.' . $a["AreaName"]; ?></a></li>
						<?php } ?>			
						</ul>
					</li>
						
					<li class="dropdown">
					<a href="#" class="dropdown-toggle"  id="loci_dropdown_toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Locus<span class="caret"></span></a>
						<ul class="dropdown-menu loci_dropdown" id="loci_list">
						</ul></li>
					<li><a href="#" class="arrow-nav" id="bGo">GO!</a></li>
					
					
					
		
     				
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




<!--  
 <a href="loci.php"><strong>Home</strong></a>
<a href="loci.php"><strong>Loci</strong></a>
<a href="loci.php"><strong>Walls</strong></a>
<a href="logout.php"><strong>Logout</strong></a>
--> 

  