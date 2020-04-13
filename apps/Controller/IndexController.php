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
 * IndexController
 * @package NG
 * @subpackage library
 * @version 0.1
 * @copyright (c) 2012, Nick Gejadze
 */
 
class IndexController extends NG\Controller {
    protected $config;
    protected $cache;
    protected $session;
    protected $cookie;
    protected $helper;
    
    public function init() {
        $this->config = $this->view->config = \NG\Registry::get('config');
        $this->session = $this->view->session = new \NG\Session();
        $this->cookie = $this->view->cookie = new \NG\Cookie();
        $this->cache = $this->view->cache = new \NG\Cache();
        $this->helper = $this->view->helper = new Helper();
    }
    
    public function IndexAction() {
        $requests = \NG\Route::getRequests();
        
        $session = $this->session;
        $cookie = $this->cookie;
        $cache = $this->cache;
        
        $viewDescription = 'Description';
        $viewDescription = htmlentities(strip_tags($viewDescription));
        
        $this->view->viewTitle = 'Home';
        $this->view->viewKeywords = 'NG, Framework';
        $this->view->viewDescription = $viewDescription;
        $this->view->viewImage = '';
    }
}

?>
