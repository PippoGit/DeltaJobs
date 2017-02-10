<!-- PROFILE INTRO -->
<section class="intro-s">
         <header class="section-header profile-header"
            <?php
               if($mode=='c') { 
                  echo ' style="background: url(../'. $user->profileInformation_->picture . '); background-position: center;background-size: cover;"';
               }
               ?>
               >
            <?php
               if($mode=='u') { ?>
               <div class="avatar">
                  <img style="<?php echo echoCroppedStyle($user->profileInformation_->picture, 230); ?>" alt="user-avatar" src="<?php echo $user->profileInformation_->picture;  ?>">
               </div>
               <?php } ?>
            <div class="section-header-description profile-description">
               <div class="name-and-rate" style="padding-left:30px;">
                  <h2 class="profile-h2"><?php echo $user->profileInformation_->name; 
                     if($mode=='c') echo '</h2>';
                     else
                        echo " " . $user->profileInformation_->surname .'</h2><div class="rate" style="width:'. ($user->profileInformation_->_avg*20)  .'px;"></div>'; ?> 
               </div>
               <div class="user-info-row first">
                  <div class="img location"></div> <span><b>location</b>:  <?php echo $user->profileInformation_->city . ", " . $user->profileInformation_->country;  
         ?> </span>
               </div>
               <?php
                  if($mode=='u') { ?>
               <div class="user-info-row">
                  <div class="img language"></div>
                  <span><b>languages</b>: <?php echo $user->profileInformation_->languages; ?> </span>
               </div>
               <?php } ?>
               <div class="user-info-row">
                  <div class="img role"></div>
                  <span><b>fav job</b>:  <?php echo $user->profileInformation_->category;  ?></span>
               </div>
               <div class="user-info-row">
                  <div class="img email"></div>
                  <span><b>email</b>:  <?php echo $user->profileInformation_->email;  ?></span>
               </div>
               <div class="user-info-row">
                  <div class="img tel"></div>
                  <span><b>tel</b>:  <?php echo $user->profileInformation_->telephone;  ?> </span>
               </div>
            </div>
         </header>
         <article class="md-card">
            <header>
               <h2>Bio</h2>
            </header>
            <p>  <?php echo  utf8_encode(nl2br($user->profileInformation_->bio));  ?> </p>
         </article>
      </section>
<!-- /PROFILE INTRO -->