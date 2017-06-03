<?php


require_once 'config.php';
//require_once 'funcs.php';

try {
	
	$nloc = get_new_locus_id($_POST["loc_id"], $_POST["req"], $_POST["loc_no"], $_POST["yyyy"], $_POST["area_name"]);
	$loc_json = GetLocusInfo($nloc);
	echo $loc_json;
}

catch( PDOException $Exception ) {
	// Note The Typecast To An Integer!
	throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
}


function get_new_locus_id($loc_id, $req, $loc_no, $yyyy, $area_name){
	
	global $servername, $dbname, $con_name, $con_pw;
	
	
	//echo "get_new_locus_id";
	
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	switch ($req) {
		case "bFirst":
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute();
			$nloc = $stmt->fetchColumn();
			break;
			
		case "bLast":
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute();
			$nloc = $stmt->fetchColumn();
			break;
			
		case "bNext":
			
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM v_loci WHERE Locus_ID = :loc");
			$stmt->execute(array(':loc' => $loc_id));
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName = :an AND Locus_no > :ln ORDER BY Locus_no ASC LIMIT 1");
			$stmt->execute(array(':y' => $y, ':an' => $an, ':ln' => $ln));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName > :an ORDER BY AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute(array(':y' => $y, ':an' => $an));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY > :y ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute(array(':y' => $y));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$nloc = $loc_id;
			break;
			
		case "bPrev":
			//twice - put in func
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM v_loci WHERE Locus_ID = :loc");
			$stmt->execute(array(':loc' => $loc_id));
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName = :an AND Locus_no < :ln ORDER BY Locus_no DESC LIMIT 1");
			$stmt->execute(array(':y' => $y, ':an' => $an, ':ln' => $ln));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName < :an ORDER BY AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute(array(':y' => $y, ':an' => $an));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY < :y ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute(array(':y' => $y));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$nloc = $loc_id;
			break;
			
		case "bGo":
			$nloc = $loc_id;
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :yyyy AND AreaName = :area_name AND Locus_no = :locus_no");
			$stmt->execute(array(':yyyy' => $yyyy, ':area_name' => $area_name, ':locus_no' => $loc_no));
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

/*
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
			$stmt->execute(array(':loc' => $loc));
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
			$stmt->execute(array(':y' => $y, ':an' => $an));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY > :y ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute(array(':y' => $y));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$nloc = $loc;
			break;
			
		case "btnPrev":
			//twice - put in func
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM v_loci WHERE Locus_ID = :loc");
			$stmt->execute(array(':loc' => $loc));
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName = :an AND Locus_no < :ln ORDER BY Locus_no DESC LIMIT 1");
			$stmt->execute(array(':y' => $y, ':an' => $an, ':ln' => $ln));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY = :y AND AreaName < :an ORDER BY AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute(array(':y' => $y, ':an' => $an));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE YYYY < :y ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute(array(':y' => $y));
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();
				break;
			}
			$nloc = $loc;
			break;
			
		case "btnGo":
			$nloc = $loc;
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM v_loci WHERE Area_ID = :area_id AND Locus_no = :locus_no");
			$stmt->execute(array(':area_id' => $area_id, ':locus_no' => $loc_no));
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
*/

function GetLocusInfo($nloc){
	
	global $servername, $dbname, $con_name, $con_pw;
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $conn -> prepare("SELECT * FROM v_loci WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$loc = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$ptCnt = 0;
	$arCnt = 0;
	$lbCnt = 0;
	$flCnt = 0;
	$gsCnt = 0;
	$imCnt = 0;
	
	//------- PT ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM pt WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$ptCnt = $stmt->fetchColumn();
	
	if ($ptCnt > 0) {
		$stmt = $conn -> prepare("SELECT PT_ID, PT_no, Pd_text, Description, Notes, PT_date, Top_Lv, Bot_Lv, Keep FROM pt WHERE Locus_ID = :loc ORDER BY PT_no");
		$stmt->execute(array(':loc' => $nloc));
		$pt = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//------- AR ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM v_ar WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$arCnt = $stmt->fetchColumn();
	
	if ($arCnt > 0) {
		$stmt = $conn -> prepare("SELECT AR_ID, AR_no, Related_PT_no, Category_Name, Date, Level, Description, Notes FROM v_ar WHERE Locus_ID = :loc ORDER BY AR_no");
		$stmt->execute(array(':loc' => $nloc));
		$ar = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//------- LB ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM v_lb WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$lbCnt = $stmt->fetchColumn();
	
	if ($lbCnt > 0) {
		$stmt = $conn -> prepare("SELECT LB_no, Related_PT_no, Square, LB_date, Quantity, Description, Category_Name FROM v_lb WHERE Locus_ID = :loc ORDER BY LB_no");
		$stmt->execute(array(':loc' => $nloc));
		$lb = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	//------- FL ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM fl WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$flCnt = $stmt->fetchColumn();
	
	if ($flCnt > 0) {
		$stmt = $conn -> prepare("SELECT FL_no, Related_PT_no, FL_date, Wt_grams, Description, Notes FROM fl WHERE Locus_ID = :loc");
		$stmt->execute(array(':loc' => $nloc));
		$fl = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//------- GS ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM gs WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$gsCnt = $stmt->fetchColumn();
	
	if ($gsCnt > 0) {
		$stmt = $conn -> prepare("SELECT GS_no, Related_PT_no, No_of_pieces, Description, Notes, GS_date FROM gs WHERE Locus_ID = :loc ORDER BY GS_no");
		$stmt->execute(array(':loc' => $nloc));
		$gs = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	//------- Images ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM images WHERE Locus_ID = :loc");
	$stmt->execute(array(':loc' => $nloc));
	$imCnt = $stmt->fetchColumn();
	
	if ($imCnt > 0) {
		$stmt = $conn -> prepare("SELECT * FROM images WHERE Locus_ID = :loc ORDER BY Of_entity_type, Find_no, Image_no");
		$stmt->execute(array(':loc' => $nloc));
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
