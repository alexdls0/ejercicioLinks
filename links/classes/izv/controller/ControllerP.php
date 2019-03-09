<?php

namespace izv\controller;

use izv\app\App;
use izv\model\ModelP;
use izv\tools\Session;

class ControllerP {

    private $model;
    private $sesion;

    function __construct(ModelP $model) {
        $this->model = $model;
        $this->sesion = new Session(App::SESSION_NAME);
        $this->getModel()->set('urlbase', App::BASE);
    }
    
    function getModel() {
        return $this->model;
    }
    
    function getSesion() {
        return $this->sesion;
    }

    /* acciones */
    
    function main() {
        $this->getModel()->set('datos', 'datos que envía el método main');
    }

}