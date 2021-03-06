<?php 
   require_once("utility.php");
   require_once("DatabaseHandler.php");   
   session_start(); 
   
   if( !isset($_SESSION['id']) ) {
      $error = array("status"=>"error", "error_id"=>"101", "msg"=>$errors['101']);
      die(json_encode($error));   //If session has expired...
   }

   $id_message = $_POST['id'] or goToErrorPage(-1); //You can't access this page
   $mode = $_SESSION['mode'] or goToErrorPage(-1);
   $table = array('u'=>"usr_inbox", 'c'=>"com_inbox");

   $params = array($id_message);
   $db = new DatabaseHandler;
   $result = null;

   try {
      $db->executeQuery("UPDATE  `" . $table[$mode] . "` SET  `read` =1 WHERE id_message =%d", $params);
      $db->executeQuery("SELECT * FROM " . $table[$mode] . " WHERE id_message =%d", $params, true);
      $result = $db->fetchResultObject();
   }
   catch(DeltaException $e) {
      die($e->toJSON());
   }
   finally {
         if(!is_null($result)) {
          echo json_encode($result);
      }
   }
?>