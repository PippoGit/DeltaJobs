<?php 
   //Init
   require_once("../deltabase/sys/api/DeltaProfile.php");

   session_start();
   $mode = $_SESSION['mode'] or goToErrorPage(-1);
   $id = $_SESSION['id'] or goToErrorPage(-1);
   $current_page = basename(__FILE__, '.php'); 
   
   $user = new DeltaProfile($id, $mode);
   $user_info = $user->getBasicInformation();

   $second_option_menu = array('u'=>"pending requests", 'c'=>"collaborations");
   $collaboration_caption = array('u' => " sent you a <b>collaboration request. </b>", 'c'=> "");
   $collaboration_intro = array('u' => "", 'c'=> "Collaboration with ");
   $collaboration_footer = array('u' =>"Please contact the company for further information.",'c'=>"");
   $action = array('u'=> "manageUserPendingRequest", 'c'=>"manageCompanyCollaboration");

   //Get pending requests or pending collaborations
   $pending_requests = $user->getPendingRequests();

?>
<!doctype html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>delta-jobs.com – <?php echo ucfirst($second_option_menu[$mode]); ?></title>
   <!-- Include CSS and stylez and some magic-->
   <link href="../css/apple.css" rel="stylesheet" type="text/css">
   <link href="../css/search-bar.css" rel="stylesheet" type="text/css">
   <link href="../css/profile-common-style.css" rel="stylesheet" type="text/css">

   <link href="../css/localfonts.css" rel="stylesheet" type="text/css">
   <!-- Include javascript -->
   <script type="text/javascript" src="../js/DialogCard.js"></script>
   <script type="text/javascript" src="../js/Search.js"></script>
   <script type="text/javascript" src="../js/Ajax.js"></script>  
   <script type="text/javascript" src="../js/FormValidation.js"></script>  
   <script type="text/javascript" src="../js/utility.js"></script>   
   <script type="text/javascript" src="../js/collaborations.js"></script>   
   <script type="text/javascript" src="../js/collaborations_page.js"></script>   


   <script type="text/javascript">
      var COMPANY_ID = <?php echo ($mode=='c')?$_SESSION['id']:'undefined'; ?>;
      var USER_ID = <?php echo ($mode=='u')?$_SESSION['id']:'undefined'; ?>;
      var MODE = '<?php echo $mode; ?>';        
   </script>
</head>

<body>
   <header class="site-header">
      <div id="header-wrapper">
         <div id="logo"></div>
         <h1 id="logo-text">Delta-Jobs</h1>
         <div id="logout-button" class="header-button" onclick="location.href='../logout.php'">log out</div>
      </div>
      <nav>
         <a href="dashboard.php">
            <div class="menu-item">
               home
            </div>
         </a>
         <a href="#">
            <div class="menu-item current">
               <?php echo $second_option_menu[$mode]; ?></div>
         </a>   
         <a href=<?php echo '"profile.php?' . $mode . '='. $id . '"';?> >
            <div class="menu-item">
               your profile</div>
         </a>
         <input id="search-bar" class="search-bar clickable" placeholder="search" accesskey="f">
      </nav>
      <div class="divider outer">
         <div class="divider inner">
         </div>
      </div>
   </header>
   <?php require_once("../deltabase/sys/require/dialog_card.php"); ?>
   <?php require_once("../deltabase/sys/require/search_result.php"); ?>
   <main>
      <section id="request-s" class="intro-s">
         <header class="relative-heading">
            <h1><?php
                  echo $user_info->name;
                  if(substr($user_info->name, -1) == 's')
                     echo "' ";
                  else
                     echo "'s ";
                  echo $second_option_menu[$mode]; ?>
            </h1>
            <div id="refresh-notifications-button" class="refresh" title="refresh"></div>
         </header>
         <div class="column-layout" style="height: 500px;">
            <section class="column full" id="notifications" style="/* float: left; */ background: rgba(255, 255, 255, 0.71); 
                                                                   width: 900px; display: inline-block; 
                                                                   /* margin-top: 15px; */ border: 1px rgb(255, 255, 255) solid; padding: 30px; 
                                                                   height: auto;">
               <h3><?php echo ($mode=='u')?"Requests":"Collaborations";?></h3>
               
               <?php
                  if(count($pending_requests)) {
                     echo '<ul class="notifications-list">';
                     foreach($pending_requests as $n) {
                        echo '<li class="notification" onclick="'. $action[$mode] .'(' . $n->id_collaboration . ')" id="collaboration-'. $n->id_collaboration . '" onclick="openRequest('. $n->id_collaboration . ')">';
                        echo '   <span class="notification-object">' . $collaboration_intro[$mode] . '<b>'. $n->name .'</b>' . $collaboration_caption[$mode] .'  </span>';
                        echo '   <span class="notification-time"> '. (($mode=='u')?$collaboration_footer[$mode]:( ($n->date_start==null)?'Collaboration has not started yet.':'Collaboration is still in progress. Click to manage this collaboration.')) . ' </span>';
                        echo '</li>';
                     }
                     echo '</ul>';
                  }
                  else {
                     echo '<p>There are no ' . $second_option_menu[$mode] . '.</p>';
                  }
               ?>
            </section>
         </div>
      </section>
   </main>
   <?php require_once("../deltabase/sys/require/footer.php"); ?>
</body>
</html>