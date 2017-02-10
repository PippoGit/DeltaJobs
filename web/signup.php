<?php
   require_once("deltabase/sys/api/utility.php");

   session_start();
   if(isset($_SESSION['id'])) { //if you're already logged
       header("Location: d/dashboard.php");
       exit();
   }

   $mode = $_GET['type'] or goToErrorPage(100);
   $script = array('u'=> 'add_new_user.php', 'c'=> 'add_new_company.php');
   $name_placeholder = array('u'=>"what's you name?", 'c'=>"what's your company name?");
   $bio_placeholder = array('u'=>"tell us about you", 'c'=>"tell us about your company");
   $picture_caption = array('u'=>"let us see you, upload a <b>picture</b>", 'c'=>"upload a <b>picture</b> for your company page");
   $telephone_placeholder = array('u' => "what's your telephone number?", 'c'=>"what's your company telephone number?");
   $location_placeholder = array('u' => "where do you live?", 'c'=>"where is your company located?");
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <title>delta-jobs.com – Sign Up</title>
   <!-- Include CSS and stylez and some magic-->
   <link href="css/apple.css" rel="stylesheet" type="text/css">
   <link href="css/localfonts.css" rel="stylesheet" type="text/css">
   <link href="css/profile-common-style.css" rel="stylesheet" type="text/css">
   <link href="css/profile.css" rel="stylesheet" type="text/css">


   <script type="text/javascript" src="js/signup.js"></script>
   <script type="text/javascript" src="js/FormValidation.js"></script>
   <script type="text/javascript" src="js/utility.js"></script>      
</head>

<body>
   <header class="site-header">
      <div id="header-wrapper">
         <div id="logo"></div>
         <h1 id="logo-text">Delta-Jobs</h1>
      </div>
      <nav>
         <a href="index.php">
            <div class="menu-item current">
               home
            </div>
         </a>
      </nav>
      <div class="divider outer">
         <div class="divider inner">
         </div>
      </div>
   </header>
   <main>
      <form id="signup-form" method="POST" action="deltabase/sys/api/<?php echo $script[$mode]; ?>" style="padding-bottom: 39px;" enctype="multipart/form-data">
         <section class="section md-card">
            <h2>Basic information</h2>
            <form id="personal-information" method="POST" action="deltabase/sys/api/add_new_user.php" style="padding-bottom: 39px;" enctype="multipart/form-data">
               <input class="input fullwidth" id="name" name="name" type="text" placeholder="<?php echo $name_placeholder[$mode]; ?>" required>
               <?php if($mode === 'u') { ?> <input class="input fullwidth" id="surname" name="surname" type="text" placeholder="what's your surname?" required> <?php } ?>
               <input class="input fullwidth" id="email" name="email" type="text" placeholder="what is your email address?" required>
               <input class="input fullwidth" id="password" name="password" type="password" placeholder="choose a password" required>
               <input class="input fullwidth" id="telephone" name="telephone" type="text" placeholder="<?php echo $telephone_placeholder[$mode]; ?>" required>
               <input class="input fullwidth" id="city" name="city" type="text" placeholder="<?php echo $location_placeholder[$mode]; ?>" required>
               <select name="country" class="select fullwidth">
                  <option value="" selected="selected">select country</option>
                  <option value="United States">United States</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="American Samoa">American Samoa</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Anguilla">Anguilla</option>
                  <option value="Antarctica">Antarctica</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Aruba">Aruba</option>
                  <option value="Australia">Australia</option>
                  <option value="Austria">Austria</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bermuda">Bermuda</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Bouvet Island">Bouvet Island</option>
                  <option value="Brazil">Brazil</option>
                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Cape Verde">Cape Verde</option>
                  <option value="Cayman Islands">Cayman Islands</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Christmas Island">Christmas Island</option>
                  <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                  <option value="Cook Islands">Cook Islands</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Cote D'ivoire">Cote D'ivoire</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominica">Dominica</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France">France</option>
                  <option value="French Guiana">French Guiana</option>
                  <option value="French Polynesia">French Polynesia</option>
                  <option value="French Southern Territories">French Southern Territories</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Greenland">Greenland</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guadeloupe">Guadeloupe</option>
                  <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guinea-bissau">Guinea-bissau</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                  <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                  <option value="Korea, Republic of">Korea, Republic of</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macao">Macao</option>
                  <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Martinique">Martinique</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mayotte">Mayotte</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                  <option value="Moldova, Republic of">Moldova, Republic of</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montserrat">Montserrat</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                  <option value="New Caledonia">New Caledonia</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Niger">Niger</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="Niue">Niue</option>
                  <option value="Norfolk Island">Norfolk Island</option>
                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Pitcairn">Pitcairn</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Puerto Rico">Puerto Rico</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Reunion">Reunion</option>
                  <option value="Romania">Romania</option>
                  <option value="Russian Federation">Russian Federation</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="Saint Helena">Saint Helena</option>
                  <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                  <option value="Saint Lucia">Saint Lucia</option>
                  <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                  <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovakia">Slovakia</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                  <option value="Swaziland">Swaziland</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                  <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Timor-leste">Timor-leste</option>
                  <option value="Togo">Togo</option>
                  <option value="Tokelau">Tokelau</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="Uganda">Uganda</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                  <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Viet Nam">Viet Nam</option>
                  <option value="Virgin Islands, British">Virgin Islands, British</option>
                  <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                  <option value="Wallis and Futuna">Wallis and Futuna</option>
                  <option value="Western Sahara">Western Sahara</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>
               </select>
               <select name="favjob" class="select fullwidth">
                  <option value="" selected="selected">choose your favourite job</option>
                  <option value="1">Web Designer</option>
                  <option value="2">Developer</option>
                  <option value="3">Designer</option>
               </select>
               <?php if($mode=='u') { ?>
               <label for="languages[]">Languages:</label>
               <br>
               <input type="checkbox" name="languages[]" value="English">English</input>
               <br>
               <input type="checkbox" name="languages[]" value="Italian">Italian</input>
               <br>
               <input type="checkbox" name="languages[]" value="French">French</input>
               <br>
               <input type="checkbox" name="languages[]" value="Spanish">Spanish</input>
               <br>
               <input type="checkbox" name="languages[]" value="German">German</input>
               <br>
               <input type="checkbox" name="languages[]" value="Chinese">Chinese</input>
               <br>
               <input type="checkbox" name="languages[]" value="Russian">Russian</input>
               <br>
               <input type="checkbox" name="languages[]" value="Japanese">Japanese</input>
               <br>
               <?php } ?>
               <textarea id="bio" name="bio" placeholder="<?php echo $bio_placeholder[$mode]; ?>" class="input textarea px_500 fullwidth" required></textarea>
               <?php if($mode=='c') { ?><textarea id="requirements" name="requirements" placeholder="what are the requirements for working in your company?" class="input textarea px_500 fullwidth" required></textarea>
                                        <button class="button white" style="float:right;">Join us!</button></br>
               <?php } ?>
               <div class="upload">
                  <label for="src_picture"><?php echo $picture_caption[$mode]; ?></label>
                  <input name="src_picture" id="src_picture" type="file" required>
               </div>
         </section>
         <?php if($mode=='u') { ?>
         <section class="section md-card">
            <h2>Skills</h2>
            <div id="skill-s">
               
               <article class="md-card row">
                  <header class="skill-category">
                     <span class="blue-slash">//</span>
                     <h2>Programming</h2>
                  </header>
                  <div class="content">
                     <div class="skill-description">C/C++</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Java</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Objective-C/Swift</div> 
                      <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">iOS</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Android</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Windows Phone</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Ruby</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Python</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>                     
                  </div>
               </article>
               <article class="md-card row">
                  <header class="skill-category">
                     <span class="blue-slash">//</span>
                     <h2>Design</h2>
                  </header>
                  <div class="content">
                     <div class="skill-description">Photoshop</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
                  <div class="content">
                     <div class="skill-description">Illustrator</div>
                     <input class="input" type="number" name="skills[]" placeholder="skill value in %" max="100" min="1" style="width: 387px;" step="1" required>
                  </div>
               </article>
            </div>
            <button class="button white" style="float:right;">Join us!</button></br>
         </section>
         <?php } ?>
         </form>
   </main>
   <footer>
      <div id="footer-content">
         <div id="credit">
            <b>Filippo Scotto © 2014-2015 UNIPI</b>
         </div>
      </div>
   </footer>
</body>
</html>