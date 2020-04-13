<?php

class AssetController extends NG\Controller {
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
        
        $this->view->setLayout(false);
        $this->view->setNoRender(true);
    }
    
    public function IndexAction() {
        $helper = $this->helper;
        $config = $this->config;
        $requests = \NG\Route::getRequests();
        $compactMode = $config['COMPACT_MODE'];
        
        $param1 = "";
        $param2 = "";
        $param3 = "";
        $param4 = "";
        
        $assets_path = '';
        $file_path = '';
        $file_name = '';
        $file_ext = '';
        $result = '';
        
        if (isset($requests['param1'])){
            $param1 = $requests['param1'];
            if (isset($requests['param2'])){
                $param2 = $requests['param2'];
                if (isset($requests['param3'])){
                    $param3 = $requests['param3'];
                    if (isset($requests['param4'])){
                        $param4 = $requests['param4'];
                    }
                }
            }
        }
        
        if ($param1 AND $param2){
            $assets_path = ROOT . DS . ASSETS . DS . $param1;
            $arr_file = explode('.', $param2);
            $file_path = $assets_path . DS . $param2;
            $file_name = $arr_file[0];
            
            if (count($arr_file) > 1) $file_ext = end($arr_file);
            
            header('Cache-Control: max-age=2592000');
            switch($file_ext){
                case 'css':
                    header('Content-Type: text/css');
                break;
                case 'js':
                    header('Content-Type: text/javascript');
                break;
                case 'jpg':
                    header('Content-Type: image/jpeg');
                break;
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                break;
                case 'png':
                    header('Content-Type: image/png');
                break;
                case 'ico':
                    header('Content-Type: image/x-icon');
                break;
                case 'xml':
                    header('Content-Type: text/xml');
                break;
                case 'json':
                    header('Content-Type: application/json');
                break;
            }
        }
        
        if ($compactMode == 2){
            if (file_exists($file_path)){
                switch($file_ext){
                    case 'css':
                    case 'js':
                    case 'json':
                    case 'xml':
                        ob_start();
                        include ($file_path);
                        $content = ob_get_clean();
                        $result = $helper->removeWhiteSpace($content);
                        exit($result);
                    break;
                    case 'jpg':
                    case 'jpeg':
                        $result = $this->CompressImage($file_path, 75);
                        exit($result);
                    break;
                    case 'png':
                        $result = $this->CompressImage($file_path, 9);
                        exit($result);
                    break;
                    case 'ico':
                        $result = $file_path;
                        readfile($result);
                    break;
                }
            }
        } else {
            readfile($file_path);
        }
    }
    
    private function CompressImage($source, $quality = 9){
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, NULL, $quality);
            imagesavealpha($image, true);
            imagealphablending($image, false);
            imagedestroy($image);
        } else if ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($source);
            imagesavealpha($image, true);
            imagealphablending($image, true);
            imagepng($image, NULL, $quality, NULL);
            imagedestroy($image);
        }
    }
}

?>
