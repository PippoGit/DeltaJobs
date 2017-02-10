<!-- FULL USER PROFILE !--> 
<section id="collaboration-s" class="section md-card">
         <h2>Collaborations</h2>
         <table class="md-table">
            <thead>
            <tr>
                <th>Company</th>
                <th>Date start</th>
                <th>Date end</th>
                <th>Review</th>
            </tr>
         </thead>
         <tbody>
            <?php

               foreach ($user->collaborations__ as $c) 
               {
                  echo '<tr id="collaboration-' . $c->id_collaboration . '"';
                  if(isset($_GET['cid']) && $_GET['cid']==$c->id_collaboration) {
                     echo 'class="current-collaboration"';
                  }
                  echo '>';
                  echo '<td><b>' . $c->c_name . '</b></td>';
                  echo '<td>' . $c->date_start . '</td>';
                  echo '<td>' . $c->date_end . '</td>';
                  echo '<td><div class="rate" style="width: ' . $c->review*20 . 'px;"></div></td>';
                  echo '</tr>';
               }
            ?>
         </tbody>
      </table>
      </section>
      <section class="section">
         <h1>Skills</h1>
         <div id="skill-s">
            <article class="md-card row">
               <header class="skill-category">
                  <span class="blue-slash">//</span>
                  <h2>Programming</h2>
               </header>
               <?php 
                  foreach ($user->skills__['1'] as $skill) {
                     echo '<div class="content">';
                     echo '   <div class="skill-description">'. $skill->name . '</div>';
                     echo '   <span class="skill-indicator">'. $skill->value . '%</span>';
                     echo '   <progress class="skill-progress" value="'.$skill->value .'" max="100"></progress>';
                     echo '</div>';
                  }
               ?>
            </article>
            <article class="md-card row">
               <header class="skill-category">
                  <span class="blue-slash">//</span>
                  <h2>Webdesign</h2>
               </header>
               <?php 
                  foreach ($user->skills__['2'] as $skill) {
                     echo '<div class="content">';
                     echo '   <div class="skill-description">'. $skill->name . '</div>';
                     echo '   <span class="skill-indicator">'. $skill->value . '%</span>';
                     echo '   <progress class="skill-progress" value="'.$skill->value .'" max="100"></progress>';
                     echo '</div>';
                  }
               ?>
            </article>
            <article class="md-card row">
               <header class="skill-category">
                  <span class="blue-slash">//</span>
                  <h2>Design</h2>
               </header>
               <?php 
                  foreach ($user->skills__['3'] as $skill) {
                     echo '<div class="content">';
                     echo '   <div class="skill-description">'. $skill->name . '</div>';
                     echo '   <span class="skill-indicator">'. $skill->value . '%</span>';
                     echo '   <progress class="skill-progress" value="'.$skill->value .'" max="100"></progress>';
                     echo '</div>';
                  }
               ?>
            </article>
         </div>
      </section>
      <section class="section full-width-section">
         <header class="relative-heading">
            <h1><?php
                  echo $user->profileInformation_->name;
                  if(substr($user->profileInformation_->name, -1) == 's')
                     echo "' ";
                  else
                     echo "'s ";
             ?>Portfolio</h1>
            <?php
               if($_SESSION['id'] == $user->id_) {
                  echo '<div id="add-portfolio-button" title="add new entry" class="actionbutton px_32 add" onclick="addEntry()"></div>';
               }
            ?>
         </header>
         <ul id="portfolio-list" class="card-s-list">
            <?php 
               foreach ($user->portfolio__ as $entry) {
                  switch ($entry->id_category) {
                     case '1':
                        $category = "web design";
                        break;
                     case '2':
                        $category = "programming";
                        break;
                     case '3':
                        $category = "design";
                        break;   
                  }

                  echo '<li onclick="showPortFolioElement(this);" id="entry-' . $entry->id_portfolio . '">';
                  echo '   <div class="card-s portfolio-card card-s-list-element">';
                  echo '      <div class="picture-hidden">';
                  echo '         <img style="' . echoCroppedStyle("../" . $entry->picture, 300) . '" alt="' . $entry->name . '" src="../' . $entry->picture . '">';
                  echo '      </div>';
                  echo '      <div class="title">'. $entry->name. '</div>';
                  echo '      <div class="category"> – '. $category . ' – </div>';
                  echo '      <div class="proj-description">'. $entry->description. '</div>';
                  if(!is_null($entry->website)) {
                     echo '      <div class="proj-link">' . $entry->website .'</div>';
                  }
                  if($_SESSION['id'] == $user->id_) {
                     echo '<div title="remove entry" class="actionbutton px_20 close-button" onclick="removeEntry(\'entry-' . $entry->id_portfolio .  '\', event)"></div>';
                  }
                  echo '   </div>';
                  echo "</li> \n";
               }
            ?>
         </ul>
      </section>
<!-- /FULL USER PROFILE !-->       