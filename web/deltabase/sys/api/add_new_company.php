<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   

	$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$password = md5($_POST['password']);
    $name = htmlentities($_POST['name'], ENT_QUOTES);  //You can't access this page
    $favjob = $_POST['favjob'];// or goToErrorPage(-1); //You can't access this page
    $location_city = htmlentities($_POST['city'], ENT_QUOTES);
    $location_country = htmlentities($_POST['country'], ENT_QUOTES);
    $bio =  htmlentities(utf8ize($_POST['bio']), ENT_QUOTES);
    $telephone = htmlentities($_POST['telephone']);
    $requirements = htmlentities(utf8ize($_POST['requirements']), ENT_QUOTES);
	$new_user_id = -1;
	
	$file_dir = "deltabase/img/com/profile_pic/";
	$upload_dir = "../../../" . $file_dir;
	
	if(!getimagesize($_FILES["src_picture"]["tmp_name"])) {
		$error = array("error_id"=>"200", "msg"=>$errors['200']);
		die(json_encode($error));
	}

	// Create connection 
	try {
		$db = new DatabaseHandler;
		$params = array($email, $password);
		$db->executeQuery("INSERT INTO company VALUES(NULL, '%s', '%s')", $params, false);
		$new_user_id = $db->insertId();
		$newfilename = md5($new_user_id) . '.jpg';
		
		if (move_uploaded_file($_FILES["src_picture"]["tmp_name"], $upload_dir . $newfilename)) {
			$params = array($new_user_id, $name, $favjob, $telephone,'../' . $file_dir . $newfilename, $bio, $requirements, $location_city, $location_country);
			$db->executeQuery("INSERT INTO  `deltabase`.`com_information` VALUES(%d, '%s', %d, '%s', '%s', '%s', '%s', '%s', '%s', 0);", $params);
		}
	}
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok", "id"=>$new_user_id);
		session_start();
		session_destroy();

		session_start();
		$_SESSION['id'] = $new_user_id;
		$_SESSION['mode'] = 'c';
		header("Location: ../../../d/profile.php?c=".$new_user_id);
	}
?>