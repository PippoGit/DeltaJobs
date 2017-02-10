<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   
	session_start();	
	if(!isset($_SESSION['id'])) {
		$error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
		die(json_encode($error));	 //If session has expired...
	}

    $query = htmlspecialchars($_POST['q'],ENT_QUOTES); //You can't access this page
    $mode = $_SESSION['mode'] or goToErrorPage(-1); //You can't access this page
    $limit = $_POST['limit'] or goToErrorPage(-1); //You can't access this page
    $me = $_SESSION['id'] or goToErrorPage(-1); //You can't access this page

    $table_id = array('c'=>'user', 'u'=>'company');
    $table_information = array('c'=>'usr_information', 'u'=>'com_information');
    $target = array('c'=>'u', 'u'=>'c');
    $name = array('c'=>"CONCAT(name, ' ', surname)", 'u'=>'name');
    $target_id = array('c'=>'id_user', 'u'=>'id_company');
    $orderby = array('c'=>' ORDER BY usr_information._avg DESC', 'u'=>'');
    $avg = array('c'=>', _avg', 'u'=>'');
    $surname = array('c'=>" OR '%s' LIKE  CONCAT(CONCAT('%%',surname),'%%')", 'u'=>'');

	$text =	  "SELECT " . $name[$mode] ." AS name, picture, " . $target_id[$mode] . $avg[$mode] .", description AS role, CONCAT('profile.php?" . $target[$mode] . "=', ". $target_id[$mode] .") AS link "
			. " FROM ". $table_id[$mode] . " NATURAL JOIN ". $table_information[$mode] . " NATURAL JOIN job_category"
			. " WHERE  '%s' LIKE CONCAT(CONCAT('%%',name),'%%') OR INSTR(name, '%s') " 
			. $surname[$mode]
			. " OR job_category.description LIKE  '%%%s%%'"
			. " OR country LIKE  '%%%s%%'"
			. " OR city LIKE  '%%%s%%'"
			. $orderby[$mode];
			//. " LIMIT " .  $limit;

	// Create connection 
	try {
		$params = array($query, $query,$query,$query, $query, $query);
		if($mode=='u') {
			array_pop($params);
		}

		$db = new DatabaseHandler;
		$db->executeQuery($text, $params);
	} 
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = $db->fetchJSONResult();
		echo $result;
	}
?>

