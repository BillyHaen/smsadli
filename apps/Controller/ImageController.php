<?php

class ImageController extends NG\Controller {
    protected $config;
    protected $cache;
    protected $session;
    protected $cookie;
    
    public function init() {
        $this->config = $this->view->config = \NG\Registry::get('config');
        $this->session = $this->view->session = new \NG\Session();
        $this->cookie = $this->view->cookie = new \NG\Cookie();
        $this->cache = $this->view->cache = new \NG\Cache();
    }
    
    public function IndexAction() {
        $requests = \NG\Route::getRequests();
        
        $session = $this->session;
        $cookie = $this->cookie;
        $cache = $this->cache;
    }
}

?>
