<?php

namespace izv\model;

use izv\database\Database;
use izv\managedata\Bootstrap;

class ModelP {

    private $db;
    private $datosVista = array();

    function __construct() {
        $bs = new Bootstrap();
        //$this->db = new Database();
        $this->db = $bs->getEntityManager();
    }
    
    function __destruct() {
        //$this->db->close();
    }
    
    function add(array $array) {
        foreach($array as $indice => $valor) {
            $this->set($indice, $valor);
        }
    }

    function get($name) {
        if(isset($this->datosVista[$name])) {
            return $this->datosVista[$name];
        }
        return null;
    }

    function getDatabase() {
        return $this->db;
    }

    function getViewData() {
        return $this->datosVista;
    }

    function set($name, $value) {
        $this->datosVista[$name] = $value;
        return $this;
    }
}