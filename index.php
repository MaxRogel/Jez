<?php

require_once 'config.php';


$message = "&nbsp";

session_start();

if (isset($_SESSION['user_id'])) {
	header("Location: loci.php");
} 

if (isset($_POST['username'])) {


	try {
		
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$r = $conn -> prepare("SELECT COUNT(*) FROM users WHERE name = :user" );
		$r->execute([':user' => $_REQUEST['username']]);
		
		$c = $r->fetchColumn();

		
		if($c == 0) {
				$message = 'No such user. Please try again'; 
		} else {
			$r = $conn -> prepare("SELECT user_id, name, pw FROM users WHERE name = :user AND pw = :pw" );
			$r->execute(array(':user' => $_REQUEST['username'], ':pw' => $_REQUEST['password']));		
			$c = $r->rowCount();
			if($c == 1) {
				$res = $r-> fetch(PDO::FETCH_ASSOC);
				
				$_SESSION['user_id'] = $res['user_id'];
				$_SESSION['name'] = $res['name'];				
	
				//echo "<h3> PHP List All Session Variables</h3>";
			    //foreach ($_SESSION as $key=>$val)
				//echo $key." ".$val."<br/>";
				header("Location: loci.php");	
			} else {
				$message = 'Wrong password! Try again'; 			
			}
		}	
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
		$message = 'Server connection problem. Try later';
	}		
}

?>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Jezreel DB</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
<style>

.main{
    margin-top: 150px;
}

.main-content {
   /*background-color: Silver;
   opacity: 0.5;*/
    border: 2px solid #009edf;
    margin: 0 auto;
    max-width: 500px;
    padding: 20px 40px;
    color: #ccc;
    text-shadow: none;

}

.message {
font-weight: bold;
text-align: center;
width: 100%;
}

.input-group{
	margin: 20px 0px;
}
.input-group-addon {
    color: #009edf ;
    font-size: 17px;
}
.login-button{
    margin: 0px auto;
    max-width: 200px;;
    
}


.form-header{
    max-width: 500px;
    margin: 0 auto;
    background-color: #009edf;
    color: #fff;
    width: 100% ;
    padding: 20px 0px;
    border-top-right-radius:10px ;
    border-top-left-radius:10px 
}
.remember{
    color: black;
}

body {
    height: 100%;
}

body {
    background: url("images/sq.jpg")no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}

</style>    
    
  </head>
  <body>


	<section class="login-info">
	<div class="container">
	
	<form name="form-login" class="form-login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	
		
	
	    <div class="row main">
	       <div class="form-header">
	          <h1 class="text-center ">Jezreel DB. Please login</h1>
	        </div>
	    <div class="main-content">
				
				<div class="message text-white"><h2><?php if($message!="") { echo $message; } ?></h2></div>
		<br>

	            
	          <div class="input-group ">
	            <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
	            <input id="email" type="text" class="form-control" name="username" placeholder="Enter user name">
	          </div>
	          
	          <div class="input-group">
	            <span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
	            <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password">
	          </div>
	          
	          <div class="form-group ">
				  <input type="submit" class="btn btn-danger center-block login-button" value="submit">	              
	          </div>
	      </div>
	    </div>
	    </form>
	</div>
	</section>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed 
    <script src="bootstrap/js/bootstrap.min.js"></script>-->
  </body>
</html>




<!---
<html>
<head>
	<title>Jez DB User Login</title>
	<link rel="stylesheet" type="text/css" href="css/jez.css" />
</head>

<body>
	<div class="body-wrappper">
	<div id="header">
		<?php include("header.inc.php"); ?>
	</div>
	
	<form name="form-login" class="form-login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<div class="message"><?php if($message!="") { echo $message; } ?></div>
		<br>
		<p>Welcome to the Jezreel database. Please Login</p>	
		<br>	
		
		<label for="username">User name:</label>
		<input type="text" name="username">
		<br>
		<label for="password">Password:</label>
		<input type="text" name="password">
		<br>
		
		
		<div class='submitWrapper'>    
			<input type='submit' name='submit' value='Submit'> 
		</div>


	</form>
	</div>
</body>
</html>
-->
