function FormValidation(form) {
	this.form_ = form;
	
	this.existALPHANUMERICAL = /\w/;
	this.existNONALPHANUMERICAL = /\W/;
	this.existNONNUMERICAL = /\D/;
	this.existNUMERICAL = /\d/;
	this.exist5NUMERICAL = /^\d{5}$/;
	this.existEMAIL = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	this.existNumberInRange1_100 = /\b([1-9][0-9]?|100)\b?/; //REQUIRED
	this.existNumberInRange1_5 = /\b[1-5]\b?/; //REQUIRED

	this.shouldBeALPHANUMERICAL = []; 
	this.shouldBeNUMERICAL = [];
	this.shouldBeALPHABETICAL =  [];
	this.shouldBe5NUMBERS =  [];
	this.shouldBeEMAIL =  [];
	this.shouldBeNumberInRange1_100 = [];
	this.shouldBeNumberInRange1_5 = [];

	this.MESSAGES = [ "The following field does not have valid symbols: ",
	"The following field is not exclusively numerical: ",
	"The following field must have five digits: ",
	"The following field must have only letters: ",
	"The following field must be a valid email address: ",
	"The following field must have only number in range 1-100: ",
	"The following field must have only number in range 1-5: ",
	"OK"];

	this.fields = form.getElementsByTagName('input');

	this.specificValidation = function(){return true};
}

FormValidation.prototype.setSpecificValidation = function(f){
	this.specificValidation = f;
};
FormValidation.prototype.setShouldBeALPHANUMERICAL = function(array){
	this.shouldBeALPHANUMERICAL = array;
};
FormValidation.prototype.setShouldBeNUMERICAL = function(array){
	this.shouldBeNUMERICAL = array;
};
FormValidation.prototype.setShouldBeALPHABETICAL = function(array){
	this.shouldBeALPHABETICAL = array;
};
FormValidation.prototype.setShouldBe5NUMBERS = function(array){
	this.shouldBe5NUMBERS = array;
};
FormValidation.prototype.setShouldBeEMAIL = function(array){
	this.shouldBeEMAIL = array;
};
FormValidation.prototype.setShouldBeNumberInRange1_100 = function(array){
	this.shouldBeNumberInRange1_100 = array;
};
FormValidation.prototype.setShouldBeNumberInRange1_5 = function(array){
	this.shouldBeNumberInRange1_100 = array;
};

FormValidation.prototype.error = function(idmess, field) { 
	window.alert(this.MESSAGES[idmess] + field.name);
	field.focus(); 
	field.select();
};

FormValidation.prototype.isTrue = function(COND, ELEM, BOOL, MESS) {
	for (var i=0; i<ELEM.length; i++) {
		var j = ELEM[i];
		field = this.fields[j];
		if (COND.test(field.value) == BOOL) {
			this.error(MESS, field);
			return true;
		}
	}
	return false;
};

FormValidation.prototype.validate = function() {
	if (this.isTrue(this.existALPHANUMERICAL, this.shouldBeALPHANUMERICAL, false, 0))
		return false;
	if (this.isTrue(this.existNONALPHANUMERICAL, this.shouldBeALPHANUMERICAL, true, 0))
		return false;
	if (this.isTrue(this.existNONNUMERICAL, this.shouldBeNUMERICAL, true, 1))
		return false;
	if (this.isTrue(this.exist5NUMERICAL, this.shouldBe5NUMBERS, false, 2)) 
		return false;
	if (this.isTrue(this.existNUMERICAL, this.shouldBeALPHABETICAL, true, 3))
		return false;
	if (this.isTrue(this.existNONALPHANUMERICAL, this.shouldBeALPHABETICAL, true, 3))
		return false;
	if(this.isTrue(this.existEMAIL, this.shouldBeEMAIL, false, 4))
		return false;
	if(this.isTrue(this.existNumberInRange1_100, this.shouldBeNumberInRange1_100, false, 5))
		return false;
	if(this.isTrue(this.existNumberInRange1_5, this.shouldBeNumberInRange1_5, false, 6))
		return false;	
	return this.specificValidation();
};