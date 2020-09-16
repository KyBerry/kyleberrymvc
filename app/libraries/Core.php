<?php
/*
    * App Core Class
    * Creates URL & loads core controller
    * URL FORMAT - /controller/method/params
*/

class Core
{
    protected $current_controller = 'Pages';
    protected $current_method = 'index';
    protected $params = [];

    // constructor
    public function __construct()
    {
        // print_r($this->get_url());
        $url = $this->get_url();

        /* 
           * Look in controllers for first array value
           * Checking if controller file exists
           * ucwords() - capitalizes first word 
        */
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // If exists, set as current_controller
            $this->current_controller = ucwords($url[0]);

            // Uset 0 Index
            unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->current_controller . '.php';

        // Instantiate controller class
        $this->current_controller = new $this->current_controller;



        // Check for method - second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->current_controller, $url[1])) {
                $this->current_method = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }



        // Get parameters
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->current_controller, $this->current_method], $this->params);
    }

    public function get_url()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url =  explode('/', $url);
            return $url;
        }
    }
}
