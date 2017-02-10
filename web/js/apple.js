var mainSlider, dialog;
//INIT PAGE 

function login() {
   var fv = new FormValidation(document.getElementById('login-form'));
   //fv.setShouldBeALPHANUMERICAL([1]);
   fv.setShouldBeEMAIL([0]);

   if(!fv.validate()) {
      event.preventDefault();
   }
}

function goToRegisterSection() {
   changeLocation("#register-s");
   dialog.changeState();
}

function initPage() {
   mainSlider = new Slider("main-slider");
   var docFragment = []; // contains all gathered nodes

   var form = document.createElement('FORM');
   form.setAttribute("class", "login-form");
   form.setAttribute("action", "login.php");
   form.setAttribute("method", "POST");
   form.setAttribute("id", "login-form");

   var input = document.createElement('INPUT');
   input.setAttribute("name", "email");
   input.setAttribute("type", "email");
   input.required = true;
   input.setAttribute("placeholder", "email");
   input.className = "input alert";
   form.appendChild(input);

   var br = document.createElement('BR');
   form.appendChild(br);

   var input_0 = document.createElement('INPUT');
   input_0.setAttribute("name", "pwd");
   input_0.setAttribute("type", "password");
   input_0.required = true;
   input_0.setAttribute("placeholder", "password");
   input_0.className = "input alert";

   form.appendChild(input_0);

   var br_0 = document.createElement('BR');
   form.appendChild(br_0);

   var button = document.createElement('BUTTON');
   form.appendChild(button);
   var text_0 = document.createTextNode("Log in");
   button.appendChild(text_0);
   button.className = "button alert right";

   var button_0 = document.createElement('BUTTON');
   button_0.className = "button alert left";
   button_0.setAttribute("type", "button");
   
   button_0.addEventListener("click", goToRegisterSection);
   form.appendChild(button_0);
   var text_1 = document.createTextNode("Sign up");
   button_0.appendChild(text_1);

   docFragment.push(form);

   dialog = new DialogCard("", "img/log-in-128.png", "Insert your email and password", "");
   dialog.setDescriptionWithDOMElement(docFragment);
   
   dialog._picture.style.height = "128px";
   dialog._picture.style.width = "128px";

   document.getElementById("login-button").addEventListener("click", dialog.changeState.bind(dialog));

   form.addEventListener("submit", login);

   mainSlider.setTimer(SlidingDirection.RIGHT, 4500);
   window.console.log(slide);
}

//APPLE    
function slide(direction) {
   mainSlider.slide(SlidingDirection[direction]);

   clearInterval(mainSlider._timer);
   mainSlider.setTimer(mainSlider._timerDirection, mainSlider._timerDelay);
}


window.onload = initPage;