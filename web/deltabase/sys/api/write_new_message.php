<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   
	session_start();	
   if( !isset($_SESSION['id']) ) {
		$error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
		die(json_encode($error));	 //If session has expired...
	}

	$id_user = ($_SESSION['mode']=== 'u')?$_POST['idf']:$_POST['idt'] or goToErrorPage(-1); //You can't access this page
	$id_company = ($_SESSION['mode']==='u')?$_POST['idt']:$_POST['idf'] or goToErrorPage(-1); //You can't access this page
	
	$msg = htmlspecialchars($_POST['m'], ENT_QUOTES);
	$obj = htmlspecialchars($_POST['o'], ENT_QUOTES);
	
	$mode = $_SESSION['mode'];
	$table = array('u'=>"com_inbox", 'c'=>"usr_inbox");


	try {
		$db = new DatabaseHandler;      
		$params = array($id_user, $id_company, $obj, $msg);
		$db->executeQuery("INSERT INTO  `" . $table[$mode] . "` VALUES(NULL, %d, %d, '%s', '%s', NOW(), 0)", $params, true);
	}
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok");
		echo json_encode($result);
	}
?>