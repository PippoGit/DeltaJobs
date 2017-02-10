<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   

	$email = htmlentities($_POST['email'], ENT_QUOTES);
	$password = md5($_POST['password']);
    $name = htmlentities($_POST['name'], ENT_QUOTES);  //You can't access this page
    $surname = htmlentities($_POST['surname'], ENT_QUOTES);// or goToErrorPage(-1); //You can't access this page
    $favjob = $_POST['favjob'];// or goToErrorPage(-1); //You can't access this page
    $location_city = htmlentities($_POST['city'], ENT_QUOTES);
    $location_country = htmlentities($_POST['country'], ENT_QUOTES);
    $bio =  htmlentities(utf8ize($_POST['bio']), ENT_QUOTES);
    $telephone = htmlentities($_POST['telephone']);
    $languages = htmlentities(implode(", ", $_POST['languages']));
    $skills = $_POST['skills']; 
	$new_user_id = -1;
	
	$file_dir = "deltabase/img/usr/profile_pic/";
	$upload_dir = "../../../" . $file_dir;
	
	if(!getimagesize($_FILES["src_picture"]["tmp_name"])) {
		$error = array("error_id"=>"200", "msg"=>$errors['200']);
		die(json_encode($error));
	}

	// Create connection 
	try {
		$db = new DatabaseHandler;
		$params = array($email, $password);
		$db->executeQuery("INSERT INTO user VALUES(NULL, '%s', '%s')", $params, false);
		$new_user_id = $db->insertId();
		$newfilename = md5($new_user_id) . '.jpg';
		
		if (move_uploaded_file($_FILES["src_picture"]["tmp_name"], $upload_dir . $newfilename)) {
			$params = array($new_user_id, $name, $surname, $location_city, $location_country, $languages, $telephone, $favjob, '../' . $file_dir . $newfilename, $bio);
			$db->executeQuery("INSERT INTO  `deltabase`.`usr_information` VALUES(%d, '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', 0);", $params);
			
			//There are skills to insert
			if(isset($_POST['skills'])) {
				$params_skill = array();
				//for each skill value
				$i=0;
				foreach($skills as $skill_value) {
					$i++;

					if(!$skill_value) 
					{
						$skill_value=0;
					}

					$query_skills = "INSERT INTO usr_skill VALUES(%d, %d, %d)";

					$params_skill[0] = $i;
					$params_skill[1] = $new_user_id;
					$params_skill[2] = $skill_value;
					
					$db->executeQuery($query_skills, $params_skill);		
				}

			}
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
		$_SESSION['mode'] = 'u';		
		header("Location: ../../../d/profile.php?u=".$new_user_id);
	}
?>