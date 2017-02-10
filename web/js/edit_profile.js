function initPage() {
   document.getElementById('edit-form').addEventListener("submit", edit);
   var country = document.getElementById("country");

   country.value = USER_COUNTRY;
}


function edit() {
   var fv = new FormValidation(document.getElementById('edit-form'));

   fv.setShouldBeALPHANUMERICAL([0,1,2]);
   fv.setShouldBeNumberInRange1_100([3,4,5,6,7,8,9,11,12,13,14,15,16,17,18]);

   if(MODE=='c') {
      fv.setShouldBeNumberInRange1_5([3]);      
   }

   fv.setSpecificValidation(function() {
      var select = this.form_.getElementsByTagName("select");
      var opt = document.getElementsByName("languages[]");

      if(select[0].selectedIndex === 0) {
         alert("You have to select a country.");
         return false;
      }
      if(select[1].selectedIndex === 0) {
         alert("You have to select a favourite job.");
         return false;
      }
      var languagesSelected = false;
      for(var i=0; i<opt.length; i++) {
         if(opt[i].checked) {
            languagesSelected = true;
         }
      }
      if(!languagesSelected) {
         alert("You have to select at least a language.");
         return false;
      }
      return true;
   });

   if(!fv.validate()) {
      event.preventDefault();
   }
}

window.onload = initPage;

