<?php 
   require_once("utility.php");
   require_once("DatabaseHandler.php");   
   session_start();  
   
   if( !isset($_SESSION['mode']) ) {
      $error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
      die(json_encode($error));   //If session has expired...
   }

   $id_notification = $_POST['id'] or goToErrorPage(-1); //You can't access this page
   $params = array($id_notification);
   $db = new DatabaseHandler;
   $tmp = null;

   try {
      $db->executeQuery("UPDATE `_notification` SET `received`=1 WHERE id_notification = %d", $params);
      $db->executeQuery("SELECT * FROM _notification WHERE id_notification=%d", $params);
      
      $tmp = $db->fetchResultArray();
   }
   catch(DeltaException $e) {
      die($e->toJSON());
   }
   finally {
      if(!is_null($tmp)) 
      {
         $status = array("status"=>"OK");
         $result = array_merge($status, $tmp);
      }
      echo json_encode($result);
   }
?>