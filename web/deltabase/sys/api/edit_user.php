<?php 
	require_once("utility.php");
	require_once("DatabaseHandler.php");   

	session_start();

    $location_city = htmlspecialchars($_POST['city'], ENT_QUOTES);
    $location_country = htmlspecialchars($_POST['country'], ENT_QUOTES);
    $bio =  htmlentities($_POST['bio'], ENT_QUOTES);
    $telephone = htmlspecialchars($_POST['telephone']);
    $languages = htmlspecialchars(implode(", ", $_POST['languages']));
    $skills = $_POST['skills']; 
	$user_id = $_SESSION['id'] or goToErrorPage(101); //session has expired
	

	// Create connection 
	try {
		$db = new DatabaseHandler;

		$params = array($location_city, $location_country, $languages, $telephone, $bio, $user_id);
		$db->executeQuery("UPDATE  `deltabase`.`usr_information` SET city='%s', country='%s', languages='%s', telephone='%s', bio='%s' WHERE id_user=%d", $params);
			
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

				$query_skills = "UPDATE usr_skill SET value=%d WHERE id_usr=%d AND id_skill=%d";

				$params_skill[2] = $skill_value;
				$params_skill[1] = $user_id;				
				$params_skill[0] = $i;
				
				$db->executeQuery($query_skills, $params_skill);		
			}
		}
	}
	catch(DeltaException $e) {
		die($e->toJSON());
	}
	finally {
		$result = array("status"=>"ok", "id"=>$user_id);	
		header("Location: ../../../d/profile.php?u=".$user_id);
	}
?>