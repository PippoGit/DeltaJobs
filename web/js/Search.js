function Search() {
   this._searchBar = document.getElementById("search-bar");
   this._searchWrapper = document.getElementById("search");
   this._searchResultList = document.getElementById("result-list");
   this._moreResult = document.getElementById("show-more-search-result");

   this._searchWrapperState = 0; //0 hidden, 1 visible

   this._searchBar.addEventListener("keypress", this.keyDownHandler.bind(this));
   document.getElementById("close-search").addEventListener("click", this.changeWrapperState.bind(this));
}

Search.prototype.changeWrapperState = function () {
   if (this._searchWrapperState) {
      this._searchWrapper.style.display = "none";
      this._searchBar.value = "";
      this.cleanSearchList();
   } else {
      this._searchWrapper.style.display = "block";
   }
   this._searchWrapperState = !this._searchWrapperState;
};

Search.prototype.addResultToList = function (result) {
   var docFragment = document.createDocumentFragment(); // contains all gathered nodes

   var li = document.createElement('LI');
   li.setAttribute("class", "search-element");

   //XXX CSP will forbid inline JavaScript and event handlers. Use addEventHandler instead!
   li.addEventListener("click", function(){ window.location=result['link'];  });
   docFragment.appendChild(li);

   var img = document.createElement('IMG');
   img.setAttribute("alt", result.name);
   img.setAttribute("src", result.picture);
   li.appendChild(img);

   var span = document.createElement('SPAN');
   span.setAttribute("class", "name");
   li.appendChild(span);
   var text = document.createTextNode(result.name);
   span.appendChild(text);

   var span_0 = document.createElement('SPAN');
   span_0.setAttribute("class", "role");
   li.appendChild(span_0);
   var text_0 = document.createTextNode(result.role);
   span_0.appendChild(text_0);

   this._searchResultList.appendChild(docFragment);
};

Search.prototype.cleanSearchList = function() {
   removeAllChildrenOf(this._searchResultList);
};

Search.prototype.asyncSearch = function () {
   var query = this._searchBar.value, that = this;
   aHandler = new Ajax("../deltabase/sys/api/search.php", {limit:3, q:query});
   this.cleanSearchList();

   aHandler.done((function(response){
                     var len = response.result_list.length;
                     if(len) {
                        for(var i=0; i< len; i++) {
                           console.log(response.result_list[i]);
                           this.addResultToList(response.result_list[i]);
                        }
                        this._moreResult.style.display = "block";
                        
                        // if(len > 3) {
                        //    this._moreResult.textContent = "Show more result";
                        //    this._moreResult.className = "show-more";
                        // }

                     }
                     else {
                        this._moreResult.textContent = "Nothing was found...";
                        this._moreResult.className = "nothing-wasfound";
                     }
                  }).bind(this));

   aHandler.post();
};

Search.prototype.keyDownHandler = function (event) {
   var key = (event.which) ? event.which : event.keyCode;
   if (key === 13 && this._searchBar.value) {
      if(!this._searchWrapperState) {
         this.changeWrapperState();
      }
      this._moreResult.textContent = "";
      this.asyncSearch();
   }
};