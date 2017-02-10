<?php 
   //Init
   require_once("../deltabase/sys/api/DeltaCompany.php");
   require_once("../deltabase/sys/api/DeltaUser.php");

   session_start();

   $mode = $_SESSION['mode'] or goToErrorPage(-1);

   $category_caption[1] = " sent you a <b>collaboration request</b>.";
   $category_caption[2] = " left you a <b>review</b>.";

   $current_page = basename(__FILE__, '.php'); 
   $user = new DeltaProfile($_SESSION['id'], $mode);

   $user_info = $user->getBasicInformation();
   $messages = $user->getMessages();
   $notifications =  $user->getNotifications();

   $notifications_counter = count($notifications);
   $message_counter = count($messages);
   $newmessage_counter = $message_counter;   
   foreach($messages as $m) {
      $newmessage_counter -= $m->read;
   }

   $advices = array();
   $advices = $user->getAdvices();

   $second_option_menu = array('u'=>"pending requests", 'c'=>"collaborations");

?>
<!doctype html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>delta-jobs.com – Home</title>
   <!-- Include CSS and stylez and some magic-->
   <link href="../css/apple.css" rel="stylesheet" type="text/css">
   <link href="../css/dashboard.css" rel="stylesheet" type="text/css">
   <link href="../css/search-bar.css" rel="stylesheet" type="text/css">
   <link href="../css/profile-common-style.css" rel="stylesheet" type="text/css">

   <link href="../css/localfonts.css" rel="stylesheet" type="text/css">
   <!-- Include javascript -->
   <script type="text/javascript" src="../js/adashboard.js"></script>
   <script type="text/javascript" src="../js/Slider.js"></script>
   <script type="text/javascript" src="../js/DialogCard.js"></script>
   <script type="text/javascript" src="../js/Search.js"></script>
   <script type="text/javascript" src="../js/Ajax.js"></script>  
   <script type="text/javascript" src="../js/utility.js"></script>   
   <script type="text/javascript" src="../js/collaborations.js"></script>  

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
         <div id="logout-button" class="header-button" onclick="changeLocation('../logout.php')">log out</div>
      </div>
      <nav>
         <a href="#">
            <div class="menu-item current">
               home
            </div>
         </a>
         <a href="pending_requests.php">
            <div class="menu-item">
               <?php echo $second_option_menu[$mode]; ?></div>
         </a>   
         <a href=<?php echo '"profile.php?' . $mode . '='. $_SESSION['id'] . '"';?> >
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
      <?php require_once("../deltabase/sys/require/advices.php"); ?>
      <?php require_once("../deltabase/sys/require/dashboard.php"); ?>
   </main>
   <?php require_once("../deltabase/sys/require/footer.php"); ?>
</body>
</html>