<?php
class Helper{
    protected $config;
    protected $session;
    
    public function __construct() {
        $this->session = new \NG\Session;
        $this->config = \NG\Registry::get('config');
    }
    
    public function getArrayValue($array = null, $key = null, $default = ""){
        $result = $default;
        if ($array !== null){
            if (is_array($array)){
                if (array_key_exists($key, $array)){
                    $result = $array[$key];
                }
            }
        }
        return $result;
    }
    
    public function removeWhiteSpace($text){
        $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
        $text = preg_replace('/([\s])\1+/', ' ', $text);
        $text = str_replace(array('{ ', ' {', ' { '), '{', $text);
        $text = str_replace(array(' }', '} ', ' { '), '}', $text);
        $text = str_replace(array(' =', '= ', ' = '), '=}', $text);
        $text = str_replace(array(' ,', ', ', ' , '), ',', $text);
        $text = str_replace(array(' :', ': ', ' : '), ':', $text);
        $text = str_replace(array(' ;', '; ', ' ; '), ';', $text);
        $text = trim($text);
        return $text;
    }
}