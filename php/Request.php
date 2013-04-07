<?php

/**
 * Description of Request
 *
 * @author David Gonzalez Garcia
 */
class Request
{

    public $url_elements;
    public $verb;
    public $parameters;
    public $format = 'json';

    public function __construct()
    {
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->url_elements = explode('/', $_SERVER['REDIRECT_URL']); //PATH_INFO - REQUEST_URI
        $this->parseIncomingParams();

        if (isset($this->parameters['format']))
            $this->format = $this->parameters['format'];

        return true;
    }

    public function parseIncomingParams()
    {
        $parameters = array();

        // first of all, pull the GET vars
        if (isset($_SERVER['QUERY_STRING']))
            parse_str($_SERVER['QUERY_STRING'], $parameters);

        // now how about PUT/POST bodies? These override what we got from GET
        $body = file_get_contents("php://input");

        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : false;

        switch ($content_type)
        {
            case "application/json":
                $body_params = json_decode($body);
                if ($body_params)
                {
                    foreach ($body_params as $param_name => $param_value)
                    {
                        $parameters[$param_name] = $param_value;
                    }
                }
                $this->format = "json";
                break;
            case "application/x-www-form-urlencoded":
                parse_str($body, $postvars);
                foreach ($postvars as $field => $value)
                {
                    $parameters[$field] = $value;
                }
                $this->format = "html";
                break;
            default:
                // we could parse other supported formats here
                break;
        }
        $this->parameters = $parameters;
    }

}

?>
