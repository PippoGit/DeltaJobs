<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   
	session_start();

   if( !isset($_SESSION['mode']) ) {
		$error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
		die(json_encode($error));	 //If session has expired...
	}


    $entry_id = $_POST['id'] or goToErrorPage("ACCESS_DENIED"); //You can't access this page
	$pathname = "../../img/usr/portfolio/" . md5($entry_id) . ".jpg";

	// Create connection 
	try {
		$db = new DatabaseHandler;
		if ( unlink($pathname) ) {
			$params = array($entry_id);
			$db->executeQuery("DELETE FROM portfolio WHERE id_portfolio = %d", $params, true);
		}
	}
   catch(DeltaException $e) {
      die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok");
		echo json_encode($result);	
	}
?>

