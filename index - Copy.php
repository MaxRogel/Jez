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
			$r = $conn -> prepare("SELECT user_id, name, pw, priv FROM users WHERE name = :user AND pw = :pw" );
			$r->execute(array(':user' => $_REQUEST['username'], ':pw' => $_REQUEST['password']));		
			$c = $r->rowCount();
			if($c == 1) {
				$res = $r-> fetch(PDO::FETCH_ASSOC);
				
				$_SESSION['user_id'] = $res['user_id'];
				$_SESSION['name'] = $res['name'];				
				$_SESSION['priv'] = $res['priv'];	
				//echo "<h3> PHP List All Session Variables</h3>";
			    //foreach ($_SESSION as $key=>$val)
				//echo $key." ".$val."<br/>";
				header("Location: loci.php");	
			} else {
				$message = 'Wrong password Please try again'; 			
			}
		}	
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
		$message = 'Server connection problem. Try later';
	}		
}
	//include("login.php");
?>

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
