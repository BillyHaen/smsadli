<?php
class ApiController extends NG\Controller {
    protected $config;
    protected $cache;
    protected $session;
    protected $cookie;
    
    public function init() {
        $this->config = $this->view->config = \NG\Registry::get('config');
        $this->session = $this->view->session = new \NG\Session();
        $this->cookie = $this->view->cookie = new \NG\Cookie();
        $this->cache = $this->view->cache = new \NG\Cache();
        
        $this->view->setLayout(false);
        $this->view->setNoRender(true);
    }
    
    public function IndexAction() {
        $result = null;
        $requests = \NG\Route::getRequests();
        
        $session = $this->session;
        $cookie = $this->cookie;
        $cache = $this->cache;
        
        $param1 = '';
        $param2 = '';
        $param3 = '';
        $param4 = '';
        
        if ($requests){
            if (isset($requests['param1'])){
                $param1 = $requests['param1'];
                $param1 = urldecode($param1);
            }
            if (isset($requests['param2'])){
                $param2 = $requests['param2'];
                $param2 = urldecode($param2);
            }
            if (isset($requests['param3'])){
                $param3 = $requests['param3'];
                $param3 = urldecode($param3);
            }
            if (isset($requests['param4'])){
                $param4 = $requests['param4'];
                $param4 = urldecode($param4);
            }
            
            $outbox = new Outbox();
            
            if ($param1){
                if ($param1 == 'sms'){
                    switch($param2){
                        case 'read':
                            $status = $param3;
                            $result = $outbox->fetch($status);
                        break;
                        case 'update':
                            $id = $param3;
                            $data = array('status' => 1);
                            $result = $outbox->update($id, $data);
                        break;
                        case 'insert':
                            $receiver = $param3;
                            $message = $param4;
                            $data = array(
                                'receiver' => $receiver,
                                'message' => $message,
                                'status' => 0
                            );
                            $result = $outbox->insert($data);
                        break;
                    }
                } else if ($param1 == 'ping'){
                    $ping = $outbox->ping();
                    if ($ping) $result = 1;
                }
            }
        }
        
        if ($result){
            $print_text = json_encode($result);
            header('Content-type: application/json');
            exit($print_text);
        }
    }
}
?>