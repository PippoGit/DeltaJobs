<?php
   $advise_subtitle = array('c'=> 'Check out these people, they seem really good for your company!', 'u'=>'Here are some suggestions for you.', 'nc'=>'Mhh, it looks like there is no suggestions for you :(');
   $count =count($advices);
?>
<!-- ADVICE SECTION -->
<section class="intro-s">

         <header class="section-intro section-header dash-intro-header">
            <h1>Welcome back <?php echo $user_info->name ?></h1>
            <span class="subtitle"><?php if($count) echo $advise_subtitle[$mode]; else echo $advise_subtitle['nc']; ?></span>
         </header>
         <div class="slider-wrapper">
            <div id="control-panel">
               <a href="javascript:void(0)" class="float-left">
                  <div class="arrow l" onclick="slide('LEFT');"></div>
               </a>
               <a href="javascript:void(0)" class="float-right">
                  <div class="arrow r" onclick="slide('RIGHT');"></div>
               </a>
            </div>
<ul id="dashboard-suggestion-slider" class="slider" style="width: <?php echo $count*960; ?>px;">
      <?php 
        foreach($advices as $a) {
          if($mode === 'c') {
?>
 <li class="slide suggestion" style="/* background-image: url('http://wallpaper.pickywallpapers.com/ipad/olivia-wilde-tiny-black-ugg.jpg'); */">
                  <div class="content">
                     <img alt="avatar" src="<?php echo $a->picture; ?>" style="
    height: 100px;
    width: 100px;
    position: absolute;
    top: -50px;
    border-radius: 50%;
    border: 1px solid #ffffff;
    left: 30px;
    /* z-index: -1; */
">
                     <h2 onclick="changeLocation('<?php echo 'profile.php?u=' . $a->id_user; ?>')"><?php echo $a->name; ?> <?php echo $a->surname; ?> </h2>
                     <p class="category"><?php echo $a->favjob; ?></p>
                     <div style="margin: 0 auto; width:<?php echo($a->_avg*20) .'px;';?>" class="rate"></div>
                     <div class="text-description location">
                        <p><?php echo $a->city; ?>, <?php echo $a->country; ?></p>
                     </div>

               </div>
             </li>
<?php
        }
        else {?>
               <li class="slide suggestion" style="background-image: url('<?php echo $a->picture; ?>');">
                  <div class="content">
                     <h2 onclick="changeLocation('<?php echo 'profile.php?c=' . $a->id_company; ?>')"><?php echo $a->name; ?></h2>
                     <p class="category"><?php echo $a->favjob; ?></p>
                     <div class="text-description">
                        <p><?php echo $a->bio; ?></p>
                     </div>
                  </div>
               </li>              
        <?php } } ?>
            </ul>
         </div>
      </section>
<!-- ADVICE SECTION -->