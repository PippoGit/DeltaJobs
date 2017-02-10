<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   

	session_start();

    $location_city = htmlspecialchars($_POST['city'], ENT_QUOTES);
    $location_country = htmlspecialchars($_POST['country'], ENT_QUOTES);
    $bio =  htmlspecialchars(utf8ize($_POST['bio']), ENT_QUOTES);
    $requirements = htmlspecialchars(utf8ize($_POST['requirements']), ENT_QUOTES);
    $requested_avg = $_POST['requested_avg'];
    
    $telephone = htmlspecialchars($_POST['telephone']);

	$user_id = $_SESSION['id'] or goToErrorPage(101); //session has expired
	

	// Create connection 
	try {
		$db = new DatabaseHandler;

		$params = array($location_city, $location_country, $requirements, $telephone, $bio, $requested_avg, $user_id);
		$db->executeQuery("UPDATE  `deltabase`.`com_information` SET city='%s', country='%s', requirements='%s', telephone='%s', bio='%s', requested_avg=%d WHERE id_company=%d", $params);

	}
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok", "id"=>$user_id);	
		header("Location: ../../../d/profile.php?c=".$user_id);
	}
?>