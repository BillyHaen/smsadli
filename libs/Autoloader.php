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

/**
 * AutoLoader
 * @package NG
 * @subpackage library
 * @version 0.1
 * @copyright (c) 2012, Nick Gejadze
 */
class Autoloader {

    /**
     * __construct()
     * Calls methods in sequence 
     * @see setReporting()
     * @see removeMagicQuotes()
     * @see unregisterGlobals()
     * @see autoload()
     * @access public
     * @return void
     */
    public function __construct() {
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->autoload();
    }

    /**
     * autoload()
     * uses __autoload megic method
     * @see http://php.net/manual/en/language.oop5.autoload.php
     * @access private
     * @return void
     */
    private function autoload() {
        function __autoload($class) {
            $class = str_replace('NG\\', '', $class);
            if (file_exists(ROOT . DS . LIBS . DS . str_replace('\\', DS, $class) . '.php')):
                require_once ROOT . DS . LIBS . DS . str_replace('\\', DS, $class) . '.php';
            elseif (file_exists(ROOT . DS . LIBS . DS . ucfirst(strtolower($class)) . '.php')):
                require_once(ROOT . DS . LIBS . DS . ucfirst(strtolower($class)) . '.php');
            elseif (file_exists(ROOT . DS . APPS . DS . CONTROLLER . DS . $class . '.php')):
                require_once(ROOT . DS . APPS . DS . CONTROLLER . DS . $class . ".php");
            elseif (file_exists(ROOT . DS . APPS . DS . MODEL . DS . $class . '.php')):
                require_once(ROOT . DS . APPS . DS . MODEL . DS . $class . '.php');
            endif;
        }
    }

    /**
     * init()
     * static representation of class
     * @access public
     * @return \Autoloader
     */
    public static function init() {
        return new Autoloader();
    }

    /**
     * setReporting()
     * Determines Environment and sets error reporting
     * for production errors are stored in <ROOT> / TMP / Logs / error.log
     * @access private
     * @return void
     */
    private function setReporting() {
        if (DEVELOPMENT_ENVIRONMENT == true):
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        else:
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', ROOT . DS . 'TMP' . DS . 'Logs' . DS . 'error.log');
        endif;
    }

    /**
     * removeMagicQuotes()
     * if get_magic_quotes_gpc() stripSlashes $_GET, $_POST and $_COOKIE Variables
     * @see stripSlashes()
     * @see http://php.net/manual/en/function.get-magic-quotes-gpc.php
     * @access private
     * @return void
     */
    private function removeMagicQuotes() {
        if (get_magic_quotes_gpc()) {
            $_GET = $this->stripSlashes($_GET);
            $_POST = $this->stripSlashes($_POST);
            $_COOKIE = $this->stripSlashes($_COOKIE);
        }
    }

    /**
     * stripSlashes()
     * Strips Slashes From Variable
     * @see stripSlashesDeep()
     * @param type $value
     * @return type
     */
    private function stripSlashes($value) {
        $value = is_array($value) ? array_map(array($this,'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }

    /**
     * stripSlashesDeep()
     * recursive method of striping Slashes
     * @access private
     * @param string|array|object $value
     * @return string|array|object
     */
    private function stripSlashesDeep($value) {
        if (is_array($value)):
            $value = array_map(array($this,'stripSlashesDeep'), $value);
        elseif (is_object($value)):
            $vars = get_object_vars($value);
            foreach ($vars as $key => $data):
                $value->{$key} = $this->stripSlashesDeep($data);
            endforeach;
        else:
            $value = stripslashes($value);
        endif;
        return $value;
    }

    /**
     * unregisterGlobals()
     * Unsets global variables
     * @access private
     * @return void
     */
    private function unregisterGlobals() {
        if (ini_get('register_globals')) :
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) :
                foreach ($GLOBALS[$value] as $key => $var):
                    if ($var === $GLOBALS[$key]) :
                        unset($GLOBALS[$key]);
                    endif;
                endforeach;
            endforeach;
        endif;
    }

}
