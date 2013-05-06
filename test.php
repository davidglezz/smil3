<?php

    $str = 'Ejemplo  de publicacion con #hash y @menciones a @amigos de #oviedo.';
    $patterns = array();
    $replace = array();

    //parse URL
    preg_match_all("/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/", $str, $urls);
    foreach ($urls[0] as $url)
    {
        $patterns[] = $url;
        $replace[] = '<a href="' . $url . '" >' . $url . '</a>';
    }

    //parse hashtag
    preg_match_all("/[#]+([a-zA-Z0-9_]+)/", $str, $hashtags);
    var_dump($hashtags);
    foreach ($hashtags[1] as $hashtag)
    {
        $patterns[] = '#' . $hashtag;
        $replace[] = '<a href="http://search.twitter.com/search?q=' . $hashtag . '" >#' . $hashtag . '</a>';
    }

    //parse mention
    preg_match_all("/[@]+([a-zA-Z0-9_]+)/", $str, $usernames);
    foreach ($usernames[1] as $username)
    {
        $patterns[] = '@' . $username;
        $replace[] = '<a href="http://twitter.com/' . $username . '" >@' . $username . '</a>';
    }


    var_dump($patterns);


?>