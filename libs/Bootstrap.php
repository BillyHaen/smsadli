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

require_once (ROOT . DS . LIBS . DS . NG . 'Autoloader.php');

/**
 * Bootstrap
 * @package NG
 * @subpackage library
 * @version 0.1
 * @copyright (c) 2012, Nick Gejadze
 */
class Bootstrap {

    /**
     * $_controllerLoaded
     * Holds boolean value of controller loaded status
     * @access protected
     * @var boolean
     */
    protected $_controllerLoaded = false;

    /**
     * __construct()
     * Instantiates new Autoloader and all methods
     * @see \Autoloader();
     * @see initMethods()
     * @access public
     * @return void
     */
    public function __construct() {
        new \Autoloader();
        $this->initMethods();
    }

    /**
     * initMethods()
     * Calls Every Class method with starts with "_" OR "__"
     * @access private
     * @return void
     */
    private function initMethods() {
        foreach (get_class_methods($this) as $method):
            if (substr($method, 0, 1) == "_" and substr($method, 0, 2) !== "__"):
                call_user_func(array($this, $method));
            endif;
        endforeach;
    }

    /**
     * _initStorage()
     * Instantiates \NG\Registry
     * @see \NG\Registry
     * @access private
     * @return void
     */
    private function _initStorage() {
        \NG\Registry::init();
    }
    
    /**
     * _loadController()
     * Loads application controller
     * @see \NG\Route
     * @throws \NG\Exception
     * @access private
     * @return void
     */
    private function _loadController() {
        if (!$this->_controllerLoaded):
            $className = \NG\Route::getController() . "Controller";
            $requests = \NG\Route::getRequests();
            
            if (class_exists($className)):
                $app = new $className;
            else:
                \NG\Route::redirect(\NG\Uri::baseUrl() . "error/notfound", "404");
                exit();
            endif;
            $this->_controllerLoaded = true;
            $method = \NG\Route::getAction() . "Action";
            if (method_exists($app, $method)):
                call_user_func(array($app, $method));
            else:
                if (DEVELOPMENT_ENVIRONMENT):
                    throw new \NG\Exception(sprintf('The required method "%s" does not exist for %s', $method, $className));
                    exit();
                endif;
            endif;
        endif;
    }

}
