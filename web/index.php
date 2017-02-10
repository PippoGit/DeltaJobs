<?php
   session_start();
   
   if(isset($_SESSION['id'])) {
      header("Location: d/dashboard.php");
   }
   $current_page = basename(__FILE__, '.php'); 
?>

<!doctype html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- banana -->
   <title>delta-jobs.com – Are you a developer? Change the way you approach getting a job!</title>
   <!-- Include CSS and stylez and some magic-->
   <link href="css/apple.css" rel="stylesheet" type="text/css">
   <link href="css/gradient-background.css" rel="stylesheet" type="text/css">
   <link href="css/index.css" rel="stylesheet" type="text/css">

   <link href="css/localfonts.css" rel="stylesheet" type="text/css">
   <!-- Include javascript -->
   <script type="text/javascript" src="js/apple.js"></script>
   <script type="text/javascript" src="js/Slider.js"></script>
   <script type="text/javascript" src="js/DialogCard.js"></script>
   <script type="text/javascript" src="js/FormValidation.js"></script>
   <script type="text/javascript" src="js/utility.js"></script>   
</head>

<body>
   <!--site header-->
   <header class="site-header">
      <div id="header-wrapper">
         <div id="logo"></div>
         <h1 id="logo-text">Delta-Jobs</h1>
         <div id="login-button" class="header-button">log in</div>
      </div>
      <nav>
         <a href="#site-introduction">
            <div class="menu-item">about us</div>
         </a>
         <a href="#partner-s">
            <div class="menu-item">our partners</div>
         </a>
         <a href="#register-s">
            <div class="menu-item">join us</div>
         </a>
      </nav>
      <div class="divider outer">
         <div class="divider inner">
         </div>
      </div>
   </header>
   <!--  /site header-->
   <!-- overlay dialogbox -->
   <div id="dialog-window-wrapper" class="overlay">
      <div class="dialog-card">
         <header class="noborder-bottom">
            <span class="title"></span>
            <div class="actionbutton close-button" id="close-dialog"></div>
            <div class="picture"></div>
         </header>
         <div class="content nobackground">
            <div class="caption"></div>
            <div class="description">
            </div>
         </div>
      </div>
   </div>
   <!--   /overlay dialogbox -->
   <!-- main -->
   <main>
      <!--   introsection -->
      <section id="site-introduction" class="intro-s">
         <h1>welcome to ∆-jobs</h1>
         <div class="slider-wrapper">
            <div id="control-panel">
               <a href="javascript:void(0)" class="float-left">
                  <div class="arrow l" onclick="slide('LEFT');"></div>
               </a>
               <a href="javascript:void(0)" class="float-right">
                  <div class="arrow r" onclick="slide('RIGHT');"></div>
               </a>
            </div>
            <ul id="main-slider" class="slider">
               <li class="slide md-card gradient-blue">
                  <header>
                     <h2><img src="img/about-us-48.png" alt="aboutus"></h2>
                     <img class="slide-title" src="img/slide1-title.png" alt="AboutUs">
                  </header>

                  <p>Wheter you are a developer or a software house, delta-jobs is the best place for you. Register, now.</p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In at ex porttitor, mattis eros sed, volutpat nisl. Cras quam leo, accumsan ut malesuada ut, rhoncus vitae nisi. Mauris ac lobortis dui. Pellentesque laoreet laoreet rhoncus. Suspendisse tincidunt nulla quis augue aliquet elementum. Suspendisse potenti. Aenean imperdiet, augue id egestas porttitor, felis lacus euismod nisi, nec tempus arcu lectus aliquam ipsum. Cras at feugiat ipsum, vitae semper dui. Duis lobortis dolor in arcu congue, a condimentum felis volutpat.</p>
                  <p>What? You think this couldn't be better? Well, it's <b>free</b>.</p>
               </li>
               <li class="slide md-card gradient-red">
                  <header>
                     <h2><img src="img/how-works-48.png" alt="howitworks"></h2>
                     <img class="slide-title" src="img/slide2-title.png" alt="How it works">
                  </header>

                  <p>Wheter you are a developer or a software house, delta-jobs is the best place for you. Register, now.</p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In at ex porttitor, mattis eros sed, volutpat nisl. Cras quam leo, accumsan ut malesuada ut, rhoncus vitae nisi. Mauris ac lobortis dui. Pellentesque laoreet laoreet rhoncus. Suspendisse tincidunt nulla quis augue aliquet elementum. Suspendisse potenti. Aenean imperdiet, augue id egestas porttitor, felis lacus euismod nisi, nec tempus arcu lectus aliquam ipsum. Cras at feugiat ipsum, vitae semper dui. Duis lobortis dolor in arcu congue, a condimentum felis volutpat.</p>
                  <p>What? You think this couldn't be better? Well, it's <b>free</b>.</p>
               </li>
               <li class="slide md-card gradient-purple">
                  <header>
                     <h2><img src="img/start-working-48.png" alt="working"></h2>
                     <img class="slide-title" src="img/slide3-title.png" alt="Start working">
                  </header>
                  <p>Wheter you are a developer or a software house, delta-jobs is the best place for you. Register, now.</p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In at ex porttitor, mattis eros sed, volutpat nisl. Cras quam leo, accumsan ut malesuada ut, rhoncus vitae nisi. Mauris ac lobortis dui. Pellentesque laoreet laoreet rhoncus. Suspendisse tincidunt nulla quis augue aliquet elementum. Suspendisse potenti. Aenean imperdiet, augue id egestas porttitor, felis lacus euismod nisi, nec tempus arcu lectus aliquam ipsum. Cras at feugiat ipsum, vitae semper dui. Duis lobortis dolor in arcu congue, a condimentum felis volutpat.</p>
                  <p>What? You think this couldn't be better? Well, it's <b>free</b>.</p>
               </li>
            </ul>
         </div>
      </section>
      <!--   /introsection -->
      <!--   partnersection -->
      <section id="partner-s" class="section">
         <h1>our partners</h1>
         <div id="partner-list">
            <img class="partner" src="../img/partners/starbucks-128.png" alt="Yahoo">
            <img class="partner" src="../img/partners/windows-128.png" alt="Google">
            <img class="partner" src="../img/partners/safari-128.png" alt="Apple">
            <img class="partner" src="../img/partners/badge-html-5-128.png" alt="Apple">
            <img class="partner" src="../img/partners/badge-css-3-128.png" alt="Apple">
            <img class="partner" src="../img/partners/pinterest-128.png" alt="Google">
            <img class="partner" src="../img/partners/google-play-128.png" alt="Google">
            <img class="partner" src="../img/partners/circle-linkedin-128.png" alt="Yahoo">
            <img class="partner" src="../img/partners/vimeo-128.png" alt="Google">
         </div>
      </section>
      <!--   /partnersection -->

      <!--   register section -->
      <section id="register-s" class="section full-width-section">
         <h1>you.<b>join</b>(us);</h1>
         <p class="subtitle">What are you still waiting for? Join us and start working!</p>
         <ul class="register-list">
            <li class="card-s clickable register-sh" onclick="changeLocation('signup.php?type=c')">
               <div class="content">
                  <h2>company</h2>
                  <p>Are you a software house looking for developers?</p>
               </div>
            </li>

            <li class="card-s r clickable register-developer" onclick="changeLocation('signup.php?type=u')">
               <div class="content">
                  <h2>developer</h2>
                  <p>Are you a software developer looking for a job?</p>
               </div>
            </li>
         </ul>
      </section>
      <!--   /register section -->
   </main>
   <!-- /main-->
   <!-- footer -->
   <footer>
      <div id="footer-content">
         <div id="credit">
            <b>Filippo Scotto © 2014-2015 UNIPI</b>
         </div>
      </div>
   </footer>
   <!-- /footer -->



</body>

</html>