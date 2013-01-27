
"use strict";

var Staterouter = function(theRoutes) {
	
	function normalizePath(path) { // comprobar que nunca es llamada esta funcion
		if (path[0] !== '/') {
			path = '/' + path;
			console.log(path + ' no empezaba por /');
		}
		return path;
	}
	
	var self = this;
	self.routes = theRoutes || {};

	self.route = function (path, func) {
		self.routes[normalizePath(path)] = func;
		return self;
	};

	self.navigate = function (path, state, title) {
		History.pushState(state, title, normalizePath(path));
	};

	self.perform = function () {
		var state = History.getState(),
		// Get pathname part of URL, which is what we'll be matching
		link = document.createElement("a");
		link.href = state.url;
		var url = normalizePath(link.pathname);
		for (var route in self.routes) {
			if (self.routes.hasOwnProperty(route)) {
				// Replace :[^/]+ with ([^/]+), f.ex. /persons/:id/resource -> /persons/([^/]+)/resource
				var rx = new RegExp('^' + route.replace(/:\w+/g, '(\\w+)') + '$');
				var match = rx.exec(url);
				//console.log("Route " + route + ", " + url + ", match: " + match);
				if (match !== null) {
					self.routes[route].apply(state, match.slice(1));
					break;
				}
			}
		}
	};

	self.back = History.back;
	self.go = History.go;

	History.Adapter.bind(window, 'statechange', self.perform);
		
	return self;
}


