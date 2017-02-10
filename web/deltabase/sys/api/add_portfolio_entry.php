<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   
	session_start();	
	if(!isset($_SESSION['id'])) {
		$error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
		die(json_encode($error));	 //If session has expired...
	}

    $title = htmlspecialchars($_POST['entry-title'], ENT_QUOTES) or goToErrorPage(-1); //You can't access this page
    $description = htmlspecialchars($_POST['entry-desc'], ENT_QUOTES) or goToErrorPage(-1); //You can't access this page
    $category = $_POST['category'] or goToErrorPage(-1); //You can't access this page
    $link = $_POST['entry-link'];
	$file_dir = "deltabase/img/usr/portfolio/";
	$upload_dir = "../../../" . $file_dir;
	$size = getimagesize($_FILES["src_picture"]["tmp_name"]);

	if(!$size) {
		$error = array("error_id"=>"200", "msg"=>$errors['200']);
		die(json_encode($error));
	}

	// Create connection 
	try {
		$db = new DatabaseHandler;
		$db->executeQuery("SHOW TABLE STATUS FROM `deltabase` WHERE name LIKE  'portfolio'", false);
		$file_name = ($db->fetchResultObject()->Auto_increment);
		$newfilename = md5($file_name) . '.jpg';
		
		if (move_uploaded_file($_FILES["src_picture"]["tmp_name"], $upload_dir . $newfilename)) {
			$params = array($_SESSION['id'], $category, $title, ($file_dir . $newfilename), htmlspecialchars($description), $link);
			$db->executeQuery("INSERT INTO portfolio VALUES(NULL, %d, %d, '%s', '%s','%s', '%s')", $params, true);
		}
	}
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok", "id"=>$db->insertId(), "src"=>('../' . $file_dir . $newfilename), "width"=>$size[0], "height"=>$size[1]);
		echo json_encode($result);
	}
?>

