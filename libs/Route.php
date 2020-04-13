<?php

/**
 * NG Framework
 * Version 0.1 Beta
 * Copyright (c) 2012, Nick Gejadze
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included 
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR 
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace NG;

require_once ROOT . DS . LIBS . DS . NG . "Uri.php";

/**
 * Route
 * @package NG
 * @subpackage library
 * @version 0.1
 * @copyright (c) 2012, Nick Gejadze
 */
class Route {

    /**
     * $instance
     * Holds Class Instance
     * @access protected 
     * @var object
     */
    private static $instance;

    /**
     * $controller
     * Holds controller name
     * @access protected 
     * @var string
     */
    protected $controller;

    /**
     * $action
     * Holds action name
     * @access protected 
     * @var string
     */
    protected $action;

    /**
     * $routes
     * Holds routes
     * @access protected 
     * @var array
     */
    protected $routes;

    /**
     * $routeSelected
     * identifies if route is selected
     * @access protected 
     * @var bool
     */
    protected $routeSelected;

    /**
     * $defaultController
     * Default Controller, Default value "Index"
     * @access protected 
     * @var string
     */
    protected $defaultController = "Index";

    /**
     * $defaultAction
     * Default Action, Default value "Index"
     * @access protected 
     * @var string
     */
    protected $defaultAction = "Index";

    /**
     * $request
     * Holds all requests
     * @access public
     * @var array
     */
    public $request = array();

    protected $language = "Index";

    /**
     * init()
     * if $instance is not set starts new \NG\Route and return instance
     * @access public
     * @return object
     */
    public static function init() {
        if (self::$instance === null):
            self::$instance = new Route;
        endif;
        return self::$instance;
    }

    /**
     * setController()
     * Sets Controller Object
     * @access private
     * @param string $controller
     * @return void
     */
    private function setController($controller) {
        if (isset($controller) AND !empty($controller)):
            $new_control = self::syncController($controller);
            self::init()->controller = ucfirst(strtolower($new_control));
        else:
            self::init()->controller = self::init()->defaultController;
        endif;
    }
    
    /**
     * setAction()
     * Sets Action Object
     * @access private
     * @param string $action
     * @return void
     */
    private function setAction($action) {
        if (isset($action) AND !empty($action)):
            self::init()->action = ucfirst(strtolower($action));
        else:
            self::init()->action = self::init()->defaultAction;
        endif;
    }

    /**
     * setRequest()
     * Sets Request Object
     * @access private
     * @param string $key
     * @param string $value
     * @return void
     */
    private function setRequest($key, $value) {
        self::init()->request[$key] = $value;
    }

    /**
     * addRoute()
     * Adds Route to Application
     * @access public
     * @param array $route
     * @return boolean
     */
    public static function addRoute($route) {
        if (is_array($route) and isset($route['route'])):
            self::init()->routes[] = $route;
            return true;
        elseif (is_array($route)):
            foreach ($route as $singleRoute):
                if (isset($singleRoute['route'])):
                    self::init()->routes[] = $singleRoute;
                endif;
            endforeach;
            return true;
        endif;
        return false;
    }

    /**
     * determineRoute()
     * is route is not selected and route is added, determines route
     * @access private
     * @return bool
     */
    private static function determineRoute() {
        if (!isset(self::init()->routeSelected)):
            $routes = self::init()->routes;
            if (isset($routes) and is_array($routes)):
                $pathArray = \NG\Uri::init()->getPathChunks();
                foreach ($routes as $route):
                    if (!isset(self::init()->routeSelected)):
                        $routeArray = explode('/', trim($route['route'], '/'));
                        if (is_array($pathArray) and !empty($routeArray[0]) and count($routeArray) == count($pathArray)):
                            if (isset($route['defaults']['controller'])):
                                self::init()->setController($route['defaults']['controller']);
                                self::init()->routeSelected = true;
                            endif;
                            if (isset($route['defaults']['action'])):
                                self::init()->setAction($route['defaults']['action']);
                                self::init()->routeSelected = true;
                            endif;
                            foreach ($routeArray as $routeKey => $routeSegment):
                                if (preg_match('/^:[\w]{1,}$/', $routeSegment)):
                                    switch ($routeSegment):
                                        case":controller":
                                            self::init()->setController($pathArray[$routeKey]);
                                            self::init()->routeSelected = true;
                                            break;
                                        case ":action":
                                            self::init()->setAction($pathArray[$routeKey]);
                                            self::init()->routeSelected = true;
                                            break;
                                        default:
                                            self::init()->setRequest(substr($routeSegment, 1), $pathArray[$routeKey]);
                                            self::init()->routeSelected = true;
                                    endswitch;
                                endif;
                            endforeach;
                        endif;
                    endif;
                endforeach;
            endif;
        endif;
        return self::init()->routeSelected;
    }

    /**
     * getController()
     * Returns Controller
     * @access public
     * @return string
     */
    public static function getController() {
        self::determineRoute();
        if (!isset(self::init()->controller)):
            $path = \NG\Uri::init()->getPathArray();
            $controller = @key($path);
            self::init()->setController($controller);
        endif;
        return self::init()->controller;
    }

    /**
     * getAction()
     * Returns Action
     * @access public
     * @return string
     */
    public static function getAction() {
        self::determineRoute();
        if (!isset(self::init()->action)):
            $path = \NG\Uri::init()->getPathArray();
            $action = @reset($path);
            self::init()->setAction($action);
        endif;
        return self::init()->action;
    }

    /**
     * getRequests()
     * Returns requests
     * @access public
     * @return array
     */
    public static function getRequests() {
        self::determineRoute();
        if (!self::init()->request):
            $path = \NG\Uri::init()->getPathArray();
            if (is_array($path) and !empty($path)):
                foreach (array_slice($path, 1) as $key => $value):
                    self::init()->setRequest($key, $value);
                endforeach;
            endif;
        endif;
        return self::init()->request;
    }
    
    /**
     * redirect
     * If headers is not sent add status header and redirects
     * @param string $url
     * @param int $status
     * @return boolean
     */
    public static function redirect($url = '/', $status = '302') {
        if (headers_sent()):
            return false;
        endif;
        if (is_numeric($status)):
            switch ($status):
                case '301':
                    $msg = '301 Moved Permanently';
                    break;
                case '307':
                    $msg = '307 Temporary Redirect';
                    break;
                case '401':
                    $msg = '401 Access Denied';
                    break;
                case '403':
                    $msg = '403 Request Forbidden';
                    break;
                case '404':
                    $msg = '404 Not Found';
                    break;
                case '405':
                    $msg = '405 Method Not Allowed';
                    break;
                case '302':
                default:
                    $msg = '302 Found';
                    break;
            endswitch;
        endif;        
        if (isset($msg)):
            header('HTTP/1.1 ' . $msg);
        endif;        
        header("Location: ". $url);
        exit();
    }

    public static function getLanguage() {
        self::determineRoute();
        if (!isset(self::init()->language)):
            $path = \NG\Uri::init()->getPathArray();
            $controller = @key($path);
            self::init()->setController($controller);
        endif;
        return self::init()->language;
    }
    
    private static function syncController($controller){
        $arr_route_path = array(
            "index",
            "asset",
            defined("ROUTE_PATH_1") ? ROUTE_PATH_1 : "",
            defined("ROUTE_PATH_2") ? ROUTE_PATH_2 : "",
            defined("ROUTE_PATH_3") ? ROUTE_PATH_3 : "",
            defined("ROUTE_PATH_4") ? ROUTE_PATH_4 : "",
            defined("ROUTE_PATH_5") ? ROUTE_PATH_5 : ""
        );
        
        $arr_route_name = array(
            "index",
            "asset",
            defined("ROUTE_NAME_1") ? ROUTE_NAME_1 : "",
            defined("ROUTE_NAME_2") ? ROUTE_NAME_2 : "",
            defined("ROUTE_NAME_3") ? ROUTE_NAME_3 : "",
            defined("ROUTE_NAME_4") ? ROUTE_NAME_4 : "",
            defined("ROUTE_NAME_5") ? ROUTE_NAME_5 : ""
        );
        
        $new_control = $controller;
        
        for ($i = 0; $i < count($arr_route_path); $i++){
            $item_route_name = $arr_route_name[$i];
            $item_route_path = $arr_route_path[$i];
            if (strtolower($new_control) == strtolower($item_route_path)){
                $new_control = $item_route_name;
                break;
            }
        }
        return $new_control;
    }
}
