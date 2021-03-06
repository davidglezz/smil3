/*jslint browser: true*/
/*global History*/
"use strict";

var staterouter = (function () {
    function normalizePath(path) {
        if (path[0] !== '/') {
            console.warn(path + ' debe empezar por /');
            path = '/' + path;
        }
        return path;
    }

    function Router() {
        var self = this;
        self.routes = {};

        self.route = function (path, func) {
            path = normalizePath(path);
            self.routes[path] = func;
            return self;
        };

        self.navigate = function (path, state, title) {
            path = normalizePath(path);
            History.pushState(state, title, path);
            return self;
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
                        // Translate groups to parameters
                        var func = self.routes[route];  // if (typeof func == 'function')
                        //console.log('Route ' + route  + ' matched, arguments: ' + match.slice(1));
                        func.apply(state, match.slice(1));
                        break;
                    }
                }
            }
            return self;
        };

        self.back = function () {
            History.back();
            return self;
        };

        self.go = function (steps) {
            History.go(steps);
            return self;
        };

        function onStateChange() {
            console.log('statechange: ' + History.getState().url);
            self.perform();
        }
        History.Adapter.bind(window, 'statechange', onStateChange);
    }

    return {
        Router: Router
    };
}());

// vim: set sts=4 sw=4 et:
