<?php

require_once 'config.php';

	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
	
	
	
if(!empty($_POST["area_id"])) {
	$stmt = $conn -> prepare("SELECT Locus_ID, Locus_no FROM v_loci WHERE Area_ID = :areaID");
	$stmt->execute([':areaID' => $_REQUEST['area_id']]);
	//$c = $stmt->rowCount();
	
	//if($c > 0) {
		//$res = $stmt-> fetch(PDO::FETCH_ASSOC);
		$loci = $stmt->fetchAll();	
	//}
}
?>
	<option value="">Locus</option>
<?php
	foreach($loci as $loc) {
?>
	<option value="<?php echo $loc["Locus_ID"]; ?>"><?php echo $loc["Locus_no"]; ?></option>
<?php
	}
?>
 