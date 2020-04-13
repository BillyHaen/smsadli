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
require_once (ROOT . DS . LIBS . DS . NG . 'Bootstrap.php');

use NG\Bootstrap as FrameworkBootstrap;

/**
 * Bootstrap
 * @package NG
 * @subpackage library
 * @version 0.1
 * @copyright (c) 2012, Nick Gejadze
 */
class Bootstrap extends FrameworkBootstrap {
    public function _initConfig() {
        $dataConfigs = NG\Configuration::loadINIFile(ROOT . DS . APPS . DS . 'Config' . DS . 'config.ini');
        $newConfigs = null;
        if ($dataConfigs != null){
            foreach($dataConfigs as $key => $value) {
                $configs[] = array('key' => $key, 'value' => $value);
            }
            
            foreach($dataConfigs as $key => $value) {
                $newvalue = $value;
                
                foreach($configs as $config) {
                    $newvalue = str_replace("{" . $config['key'] . "}", $config['value'], $newvalue);
                }

                if (!defined(strtoupper($key))) 
                define(strtoupper($key), $newvalue);
                
                if (strtoupper($key) == "SUB_DIR"){
                    $subdir = $newvalue;
                }
                $newConfigs[strtoupper($key)] = $value;
            }
            
            if (!empty($subdir)){
                if (!defined("PUBLIC_PATH")) define('PUBLIC_PATH', ROOT . "/" . $subdir);
            } else {
                if (!defined("PUBLIC_PATH")) define('PUBLIC_PATH', ROOT);
            }
        }
        
        \NG\Registry::set("config", $newConfigs);
    }
    
    public function _initRoute() {
        $route = \NG\Configuration::loadINIFile(ROOT . DS . APPS . DS . CONFIG . DS . 'route.ini');
        \NG\Route::addRoute($route['routes']['single']);
        \NG\Route::addRoute($route['routes']['first']);
        \NG\Route::addRoute($route['routes']['second']);
        \NG\Route::addRoute($route['routes']['third']);
        \NG\Route::addRoute($route['routes']['fourth']);
        \NG\Route::addRoute($route['routes']['fifth']);
        \NG\Route::addRoute($route['routes']['sixth']);
    }
    
    public function _initDB() {
        $dbconfig = NG\Configuration::loadINIFile(ROOT . DS . APPS . DS . 'Config' . DS . 'database.ini');
        $database = new \NG\Database($dbconfig);
        \NG\Registry::set("database", $database);
    }
}

?>
