<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   
	session_start();	
   if( !isset($_SESSION['id']) ) {
		$error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
		die(json_encode($error));	 //If session has expired...
	}

	$id_user = ($_SESSION['mode']=='u')?$_POST['me']:$_POST['to'] or goToErrorPage(-1); //You can't access this page
	$id_company = ($_SESSION['mode']=='u')?$_POST['to']:$_POST['me'] or goToErrorPage(-1); //You can't access this page
	
	$mode = $_SESSION['mode'];
	$query = array('u'=>"INSERT INTO  `pay_attention` VALUES(%d, %d, 0, 'profile.php?u=" . $id_user ."')", 
		           'c'=>"INSERT INTO collaboration VALUES(NULL, %d, %d, NULL, NULL, NULL)");

	try {
		$db = new DatabaseHandler;      
		$params = array($id_user, $id_company);
		if($mode === 'c') 
		{
			$db->executeQuery("SELECT * FROM collaboration WHERE id_user=%d AND id_company=%d AND date_start IS NULL", $params);	

			// $result = array("status"=>"error", "error_id"=>'300', "msg"=>"SELECT * FROM collaboration WHERE id_user=%d AND id_company=%d AND date_start IS NULL",
			// 				'count'=>count($db->fetchResultArray()), 'paramsU'=> $params[0], 'paramsC'=>$params[1]);
			// die(json_encode($result));					
			
			if( count($db->fetchResultArray()) > 0) {
				$result = array("status"=>"error", "error_id"=>'300', "msg"=>"It seems like there is still a pending request for this user...");
				die(json_encode($result));
			}
		}
		else {
			$db->executeQuery("SELECT * FROM collaboration WHERE id_user=%d AND id_company=%d AND date_start IS NOT NULL AND date_end IS NOT NULL", $params);	
			
			if( count($db->fetchResultArray()) > 0) {
				$result = array("status"=>"error", "error_id"=>'300', "msg"=>"It seems like there is still a pending request for this user...");
				die(json_encode($result));
			}
		}
		
		$db->executeQuery($query[$mode], $params, true);
	}
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok");
		echo json_encode($result);
	}
?>