function Ajax(url, data) { // formato data: {key: "valore"}
   this.setUrl(url);
   if (data.constructor.name === "FormData") {
      this._data = data;
   } else {
      this.setData(data);
   }

   //Done/Fail behaviour
   this._doneHandler = undefined;
   this._failHandler = undefined;

   //default fail behaviour 
   this.fail(function (response, status) {
      if (response.error_id === "101") { //Se è scaduta la sessione te ne vai!
         window.location = "../error.php?e=" + response.error_id;
      } else {
         window.alert("HTTP Status: " + status + " \n" + "Error code: " + response.error_id + " – '" + response.msg + "'");
      }
   });

   //XMLHTTPRequest
   this.http = new XMLHttpRequest();
}

//function done must have 1 parameter responseData
Ajax.prototype.done = function (f) {
   this._doneHandler = f;
};
Ajax.prototype.fail = function (f) {
   this._failHandler = f;
};
Ajax.prototype.setUrl = function (url) {
   this._url = url;
};
Ajax.prototype.setData = function (data) {
   var query = [];
   for (var key in data) {
      window.console.log(key);
      query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
   }
   this._data = query;
};

Ajax.prototype.perform = function (url, data, type) {
   this.http.open(type, url, true);
   this.http.onreadystatechange = this.stateChange.bind(this);

   if (type === 'POST' && data.constructor.name !== "FormData") {
      this.http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   }

   this.http.send(data);
};
Ajax.prototype.get = function () {
   this.perform(this._url + '?' + this._data.join('&'), null, "GET");
};
Ajax.prototype.post = function () {
   if (this._data.constructor.name === "FormData") {
      this.perform(this._url, this._data, "POST");
   } else {
      this.perform(this._url, this._data.join('&'), "POST");
   }
};

Ajax.prototype.stateChange = function () {
   if (this.http.readyState === 4 && this.http.status === 200) {
      window.console.log(this.http.responseText);
      var parsedResponse = JSON.parse(this.http.responseText);
      window.console.log(" - " + parsedResponse);
      if (parsedResponse.hasOwnProperty('error_id')) {
         this._failHandler(parsedResponse, this.http.status); //NEGATIVE RESULT
      } else {
         this._doneHandler(parsedResponse); //OK
      }
   } else if (this.http.readyState === 4 && this.http.status === 500) {
      window.location = "../error.php"; //CONNECTION OK, SERVER GENERIC ERROR
   } else if (!this.http.readyState) {
      this._failHandler(this.http.responseText, this.http.status); //CONNECTION FAIL
   }
};