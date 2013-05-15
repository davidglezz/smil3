
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

    self.getRoute = function()
    {
        var link = document.createElement("a");
        link.href = History.getState().url;
        return link.pathname;
    }

    self.route = function (path, func) {
        self.routes[normalizePath(path)] = func;
        return self;
    };

    self.navigate = function (path, state, title) {
        History.pushState(state, title, normalizePath(path));
    };

    self.perform = function () {
        var state = History.getState(),
        link = document.createElement("a");
        link.href = state.url;
        var url = normalizePath(link.pathname);
        for (var route in self.routes)
        {
            if (self.routes.hasOwnProperty(route))
            {
                // Replace :[^/]+ with ([^/]+), f.ex. /persons/:id/resource -> /persons/([^/]+)/resource
                var rx = new RegExp('^' + route.replace(/:\w+/g, '(\\w+)') + '$');
                var match = rx.exec(url);
                if (match !== null)
                {
                    self.routes[route].apply(state, match.slice(1));
                    break;
                }
            }
        }
    };

    self.back = History.back;
    self.go = History.go;

    function onStateChange() {
        console.log('statechange: ' + History.getState().url);
        self.perform();
    }
    History.Adapter.bind(window, 'statechange', onStateChange);

    return self;
}


