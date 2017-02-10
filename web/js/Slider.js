/////////////////////////////// Classe Slider

var SlidingDirection = {
   LEFT: 1,
   RIGHT: -1
};

function Slider(slider_id) {
   this._element = document.getElementById(slider_id);
   this._element.style.left = "0";
   this._slides = this._element.getElementsByTagName("li");
   this._currentOffset = 0;
   this._timer = undefined;
   this._timerDelay = undefined;
   this._timerDirection = undefined;
   this._mouseOutSlide = true;
}

Slider.prototype.getNumberOfSlides = function () {
   return this._slides.length;
};

Slider.prototype.getLastSlideOffset = function () {
   return (-100 * this.getNumberOfSlides()) + 100;
};

Slider.prototype.slide = function (d) { //direction can be -1 right +1 left
   var direction = d || this._timerDirection;

   this._currentOffset = parseInt(this._element.style.left);
   var nextOffset = this._currentOffset + (direction * 100);


   if ((this._currentOffset === this.getLastSlideOffset()) && (direction === SlidingDirection.RIGHT)) {
      this._element.style.left = "0";

   } else if ((this._currentOffset === 0) && (direction === SlidingDirection.LEFT)) {
      this._element.style.left = this.getLastSlideOffset() + "%";
   } else {
      this._element.style.left = nextOffset + "%";
   }

};

Slider.prototype.mouseOverSlide = function () {
   if (this._mouseOutSlide) {
      clearInterval(this._timer);
      this._mouseOutSlide = false;
   }
};

Slider.prototype.mouseOutSlide = function () {
   if (this._mouseOutSlide === false) {
      this._mouseOutSlide = true;
      this.setTimer(this._timerDirection, this._timerDelay);
   }
};

Slider.prototype.setTimer = function (direction, delay) {
   this._timerDelay = delay;
   this._timerDirection = direction;
   this._timer = setInterval(this.slide.bind(this), delay);

   for (var i = 0; i < this.getNumberOfSlides(); i++) {
      this._slides[i].addEventListener("mouseover", this.mouseOverSlide.bind(this));
      this._slides[i].addEventListener("mouseout", this.mouseOutSlide.bind(this));
   }
};