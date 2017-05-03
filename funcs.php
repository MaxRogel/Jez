<?php

	require_once 'config.php';

	
	function RequestedLocus($loc, $req, $loc_no, $area_id){	

	global $servername, $dbname, $con_name, $con_pw;	

	
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	switch ($req) {
		case "btnFirst":
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute();
			$nloc = $stmt->fetchColumn();	
			break;
			
		case "btnLast":
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute();
			$nloc = $stmt->fetchColumn();	
			break;
			
		case "btnNext":
			
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM v_loci WHERE Locus_ID = :loc");
			$stmt->execute([':loc' => $loc]);
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName = :an AND Locus_no > :ln ORDER BY Locus_no ASC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an, ':ln' => $ln]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName > :an ORDER BY AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY > :y ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute([':y' => $y]);	
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$nloc = $loc;
			break;
			
		case "btnPrev":
			//twice - put in func
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM v_loci WHERE Locus_ID = :loc");
			$stmt->execute([':loc' => $loc]);
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];	

			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName = :an AND Locus_no < :ln ORDER BY Locus_no DESC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an, ':ln' => $ln]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName < :an ORDER BY AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY < :y ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute([':y' => $y]);	
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$nloc = $loc;
			break;
		
		case "btnGo":
			$nloc = $loc;
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE Area_ID = :area_id AND Locus_no = :locus_no");
			$stmt->execute([':area_id' => $area_id, ':locus_no' => $loc_no]);
			if ($stmt->rowCount() > 0) 
			{
				$nloc = $stmt->fetchColumn();
				break;
			}
			else
			{
				$nloc = 48;
			}
	 
	}
	
	return $nloc;	
} 


function GetLocusInfo($nloc){
	
	global $servername, $dbname, $con_name, $con_pw;
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	
	$stmt = $conn -> prepare("SELECT * FROM v_loci WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$loc = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$ptCnt = 0;
	$arCnt = 0;
	$lbCnt = 0;
	$flCnt = 0;
	$gsCnt = 0;
	$imCnt = 0;
	
	//------- PT ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM pt WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$ptCnt = $stmt->fetchColumn();
	
	if ($ptCnt > 0) {
		$stmt = $conn -> prepare("SELECT PT_ID, PT_no, Pd_text, Description, Notes, PT_date, Top_Lv, Bot_Lv, Keep FROM pt WHERE Locus_ID = :loc ORDER BY PT_no");
		$stmt->execute([':loc' => $nloc]);
		$pt = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 

	//------- AR ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM v_ar WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$arCnt = $stmt->fetchColumn();	

	if ($arCnt > 0) {
		$stmt = $conn -> prepare("SELECT AR_ID, AR_no, Related_PT_no, Category_Name, Date, Level, Description, Notes FROM v_ar WHERE Locus_ID = :loc ORDER BY AR_no");
		$stmt->execute([':loc' => $nloc]);
		$ar = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	

//------- LB ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM v_lb WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$lbCnt = $stmt->fetchColumn();	

	if ($lbCnt > 0) {
		$stmt = $conn -> prepare("SELECT LB_no, Related_PT_no, Square, LB_date, Quantity, Description, Category_Name FROM v_lb WHERE Locus_ID = :loc ORDER BY LB_no");
		$stmt->execute([':loc' => $nloc]);
		$lb = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 
	
	
//------- FL ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM fl WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$flCnt = $stmt->fetchColumn();	

	if ($flCnt > 0) {
		$stmt = $conn -> prepare("SELECT FL_no, Related_PT_no, FL_date, Wt_grams, Description, Notes FROM fl WHERE Locus_ID = :loc");
		$stmt->execute([':loc' => $nloc]);
		$fl = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	
	
//------- GS ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM gs WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$gsCnt = $stmt->fetchColumn();	

	if ($gsCnt > 0) {
		$stmt = $conn -> prepare("SELECT GS_no, Related_PT_no, No_of_pieces, Description, Notes, GS_date FROM gs WHERE Locus_ID = :loc ORDER BY GS_no");
		$stmt->execute([':loc' => $nloc]);
		$gs = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	
	
//------- Images ----------	
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM images WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$imCnt = $stmt->fetchColumn();	

	if ($imCnt > 0) {
		$stmt = $conn -> prepare("SELECT * FROM images WHERE Locus_ID = :loc ORDER BY Image_no");
		$stmt->execute([':loc' => $nloc]);
		$im = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	
	
	$data = array('locId' => $nloc, 'loc' => $loc, 'ptCnt' => $ptCnt, 'arCnt' => $arCnt, 'lbCnt' => $lbCnt, 'flCnt' => $flCnt, 'gsCnt' => $gsCnt, 'imCnt' => $imCnt);
	
	if($ptCnt > 0)
		$data["pt"] = $pt;
		
	if($arCnt > 0)
		$data["ar"] = $ar;
	
	if($lbCnt > 0)
		$data["lb"] = $lb;   
		
	if($flCnt > 0)
		$data["fl"] = $fl;
			
	if($gsCnt > 0)
		$data["gs"] = $gs;
	
	if($imCnt > 0)
		$data["im"] = $im;
	
	return(json_encode($data));
}
?>
