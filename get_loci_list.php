<?php

require_once 'config.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);





if(empty($_POST["YYYY"]) ||empty($_POST["area_name"]) ) 
{
	echo "Bad params";
	return;
}
else {
	
	$stmt = $conn -> prepare("SELECT Locus_ID, Locus_no FROM v_loci WHERE YYYY = :YYYY AND areaName= :area_name ORDER BY Locus_no");
	$stmt->execute(array(':YYYY' => $_REQUEST['YYYY'], ':area_name' => $_REQUEST['area_name']));
	//$c = $stmt->rowCount();
	
	//if($c > 0) {
	//$res = $stmt-> fetch(PDO::FETCH_ASSOC);
	$loci_list = $stmt->fetchAll();
	//}
	echo(json_encode($loci_list));
}

?>