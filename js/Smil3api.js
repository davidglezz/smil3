var Smil3 = function()
{
    var errorHandlers = [
    function(data){
        console.log('unhandled error ' + data.error)
    }
    ];

    var request = function(method, data, callback, getData)
    {
        var ajaxInfo = {
            /*headers: {
                Accept : "application/json; charset=utf-8",
                "Content-Type": "text/plain; charset=utf-8"
            },*/
            //contentType: "text/plain; charset=utf-8"
            //accepts: {'application/json; charset=utf-8'},
            //username: '',
            //password: 'pedir primero al server',
            type: method,
            dataType: 'json',
            url: '/main.php',
            data: data,
            success: function(res)
            {
                if (res.error != null && res.error != 0)
                {
                    errorHandlers[typeof errorHandlers[res.error] == 'function' ? res.error : 0](res);
                }
                else
                {
                    delete res['error'];
                    callback(res);
                }
            }
        }

        if (getData)
            ajaxInfo.url += getData;

        $.ajax(ajaxInfo);
    };

    return {
        errorHandlers: errorHandlers,
        getStartInfo: function(callback)
        {
            request('get', {
                'do':'getStartInfo'
            }, callback);
        },

        login: function(data, callback)
        {
            request('post', data, callback, '?do=special&that=login');
        },

        logout: function(callback)
        {
            request('get', {
                'do':'logout'
            }, callback);
        },

        register: function(data, callback)
        {
            request('post', data, callback, '?do=special&that=register');
        },

        getPosts: function(params, callback)
        {
            var data = params || {};
            data['do'] = 'getPosts';

            request('get', data, callback);
        },

        publish: function(data, callback)
        {
            request('post', data, callback, '?do=publish');
        },

        getProfile: function(user, callback)
        {
            var data = {
                'do': 'getProfile',
                'user': user
            };

            request('get', data, callback);
        },

        follow: function(user, callback)
        {
            var data = {
                'do': 'follow',
                'uid': user
            };

            request('get', data, callback);
        },

        unfollow: function(user, callback)
        {
            var data = {
                'do': 'unfollow',
                'uid': user
            };

            request('get', data, callback);
        },

        search: function(query, callback)
        {
            var data = {
                'do': 'search',
                'q': query
            };

            request('get', data, callback);
        },

        searchAutocomplete: function(query, callback)
        {
            var data = {
                'do': 'searchAutocomplete',
                'q': query
            };

            request('get', data, callback, '?noerror&nodebug');
        },

        like: function(post, coment, callback)
        {
            var data = {
                'do': 'like',
                'post': post,
                'coment': coment
            };

            request('get', data, callback);
        },

        unlike: function(post, coment, callback)
        {
            var data = {
                'do': 'like',
                'post': post,
                'coment': coment
            };

            request('get', data, callback);
        }


    }

}();
