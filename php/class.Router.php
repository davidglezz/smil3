<?php

/**
 * Router class
 *
 * This file contains class Router which in charge to call proper controller from "extensions" folder based on the URL
 * @author David Gonzalez <davidgg666@gmail.com>
 * @version 0.1
 * @package smil3
 * @since 21/3/2013
 */
class Router
{
    private $method;
    private $url_elements;
    private $parameters;

    public function __construct()
    {
        $this->method = strtolower($_SERVER["REQUEST_METHOD"]);

        //process uri and remove the query string
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $uri = $uri[0];

        //remove beginning/ending slashes
        if (substr($uri, 0, 1) == '/')
            $uri = substr($uri, 1);
        if (substr($uri, -1, 1) == '/')
            $uri = substr($uri, 0, -1);

        $this->url_elements = explode('/', $uri);

        //parseIncomingParams
        $parameters = array();

        // pull the GET vars
        if (isset($_SERVER['QUERY_STRING']))
            parse_str($_SERVER['QUERY_STRING'], $parameters);

        // now how about PUT/POST bodies? These override what we got from GET
        $body = file_get_contents('php://input');

        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : false;

        switch ($content_type)
        {
            case 'application/json':
                $body_params = json_decode($body);
                if ($body_params)
                {
                    foreach ($body_params as $param_name => $param_value)
                    {
                        $parameters[$param_name] = $param_value;
                    }
                }
                // $this->format = 'json';
                break;
            case 'application/x-www-form-urlencoded':
                $postvars = null;
                parse_str($body, $postvars);

                foreach ($postvars as $field => $value)
                    $parameters[$field] = $value;

                // $this->format = 'html';
                break;
            default:
                // we could parse other supported formats here
                break;
        }
        $this->parameters = $parameters;
    }

    public function dispatch()
    {
        $ns = $this->url_elements;
        if (count($ns) == 1 && $ns[0] == '')
        {
            $file_name = EXTENSIONPATH . 'index.php';
            $class_name = 'IndexController';
        }
        else
        {
            $file_name = EXTENSIONPATH . $ns[0] . '.php';
            $class_name = implode('_', $ns) . 'Controller';
        }

        if (!is_file($file_name))
            return false;

        include_once($file_name);

        if (!class_exists($class_name))
            return false;

        $controller = new $class_name;
        if (!method_exists($controller, $this->method))
            return false;

        return $controller->{$this->method}($this->parameters);
    }

}

?>
