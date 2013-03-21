var include = function()
{
    var head = document.getElementsByTagName('head')[0];
    var loaded = [];

    var load = function(url, type, callback, id)
    {
        if (loaded.indexOf(url) != -1)
            return callback();

        loaded.push(url);

        var script = document.createElement('script')
        script.type = type;

        if (id) script.id = id;

        if (script.readyState) //IE
        {
            script.onreadystatechange = function()
            {
                if (script.readyState == 'loaded' || script.readyState == 'complete')
                {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        }
        else  //Others
        {
            script.onload = callback;
        }

        script.src = url;
        head.appendChild(script);
    }

    this.js = function(url, callback)
    {
        load(url, 'text/javascript', callback);
    }

    this.tmpl = function(url, id, callback)
    {
        load(url, 'text/x-jquery-tmpl', callback, id);
    }

    this.css = function(url)
    {
        var style = document.createElement("link")
        style.setAttribute("rel", "stylesheet")
        style.setAttribute("type", "text/css")
        style.setAttribute("href", url)
        head.appendChild(style);
    }

    return this;

}();
