<?php

namespace izv\view;

use izv\model\ModelP;
use izv\tools\Util;

class ViewP {

    private $model;

    function __construct(ModelP $model) {
        $this->model = $model;
    }
    
    function getModel() {
        return $this->model;
    }

    function render($accion) {
        $datos = $this->getModel()->getViewData();
        return Util::varDump($datos);
    }
}