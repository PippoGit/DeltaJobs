<?php 
   require_once("utility.php");
   require_once("DatabaseHandler.php");   
   session_start();  
   
   if( !isset($_SESSION['mode']) ) {
      $error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
      die(json_encode($error));   //If session has expired...
   }

   $id_collaboration = $_POST['id'] or goToErrorPage(-1); //You can't access this page

   $action = $_POST['action'] or goToErrorPage(-1); //You can't access this page

   $params = array($id_collaboration);

   $db = new DatabaseHandler;
   $tmp = null;

   try {
      $query = array('accept'=> "UPDATE `collaboration` SET `date_start`=NOW() WHERE id_collaboration = %d", 
                     'decline'=>"DELETE FROM `collaboration` WHERE id_collaboration = %d");

      $db->executeQuery($query[$action], $params);
      
      //If the notification is not read yet, I read it
      $db->executeQuery("UPDATE `_notification` SET `received`=1 WHERE link='%d'", $params);
      //$tmp = $db->fetchResultArray();
   }
   catch(DeltaException $e) {
      die($e->toJSON());
   }
   finally {
      // if(!is_null($tmp)) 
      // {
         $result = array("status"=>"OK");
      //    $result = array_merge($status, $tmp);
      // }
      echo json_encode($result);
   }
?>