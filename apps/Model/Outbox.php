<?php
class Outbox{
    protected $config;
    protected $session;
    protected $database;
    protected $query;
    protected $table;
    
    public function __construct() {
        $this->session = new \NG\Session;
        $this->config = \NG\Registry::get('config');
        $this->database = \NG\Registry::get('database');
        $this->query = new \NG\Query();
        $this->table = 'outbox';
    }
    
    public function insert($data){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->insert($table, $data);
        $result = $database->query($sql);
        return $result;
    }
    
    public function update($id, $data){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->update($table, $data)->where("id = ?", $id);
        $result = $database->query($sql);
        return $result;
    }
    
    public function delete($id){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->delete()->from($table)->where("id = ?", $id);
        $result = $database->query($sql);
        return $result;
    }
    
    public function fetch($status = 0){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        if ($status == 0 || $status == 1){
            $sql = $query->select()->from($table)->where("status = ?", $status);
        } else {
            $sql = $query->select()->from($table);
        }
        $result = $database->fetchAll($sql);
        
        return $result;
    }
    
    public function get($id){
        $database = $this->database;
        $query = $this->query;
        $table = $this->table;
        $sql = $query->select()->from($table)->where("id = ?", $id);
        $result = $database->fetchRow($sql);
        
        return $result;
    }
    
    public function ping(){
        $database = $this->database;
        $result = $database->ping();
        return $result;
    }
}
?>