<?php


require_once 'config.php';
require_once 'funcs.php';

try {
	$nloc = RequestedLocus($_POST["loc_id"], $_POST["req"], $_POST["loc_no"], $_POST["area_id"]);	
	$loc_json = GetLocusInfo($nloc);
	echo $loc_json;
}

catch( PDOException $Exception ) {
	// Note The Typecast To An Integer!
	throw new MyDatabaseException( $Exception->getMessage( ) , (int)$Exception->getCode( ) );
}




?>
