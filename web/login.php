<?php 
   require_once("deltabase/sys/api/DatabaseHandler.php");
   require_once("deltabase/sys/api/utility.php");
   session_start();
   session_destroy();

   $usr_email = $_POST['email'] or goToErrorPage(-1); //You can't access this page
   $usr_pwd = $_POST['pwd'] or goToErrorPage(-1); //You can't access this page

   $params = array($usr_email, md5($usr_pwd));

   //try to connect
   try {
      // Create connection 
      $newDb = new DatabaseHandler;
      $newDb->executeQuery("SELECT id_user FROM user WHERE email='%s' AND  password='%s'", $params, true);
   }
   catch(DeltaException $e) {
      if($e->isCode(603)) { //Not a valid user. Is it a company?
         try {
            $newDb->releaseResult();
            $newDb->executeQuery("SELECT id_company FROM company WHERE email='%s' AND  password='%s'", $params, true);
         }
         catch(DeltaException $e) {
            goToErrorPage(102);
         }
         finally {
            $user = $newDb->fetchResultObject();
            session_start();
            
            if(isset($user->id_company)) {
                $_SESSION['id'] = $user->id_company;
            }
            
            $_SESSION['mode'] = 'c';
            die(header("Location: d/dashboard.php"));
         }
      }   
   }
   finally {
      $user = $newDb->fetchResultObject();
      session_start();
      if(isset($user->id_user)) {
        $_SESSION['id'] = $user->id_user;
      }
      $_SESSION['mode'] = 'u';    
      header("Location: d/dashboard.php");
   }
?>