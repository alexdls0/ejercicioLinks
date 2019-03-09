<?php

namespace izv\mvc;

class Router {

    private $rutas, $ruta;
    
    function __construct($ruta) {
        $this->rutas = array(
            'index' => new Route('LoginModel', 'LoginView', 'LoginController'),
            'registrar' => new Route('SignModel', 'SignView', 'SigninController'),
            'ajax' => new Route('AjaxModel', 'AjaxView', 'AjaxController'),
            'user' => new Route('UserModel', 'UserView', 'UserController'),
            'categorias' => new Route('CategoryModel', 'CategoryView', 'CategoryController'),
        );
        $this->ruta = $ruta;
    }

    function getRoute() {
        $ruta = $this->rutas['index'];
        if(isset($this->rutas[$this->ruta])) {
            $ruta = $this->rutas[$this->ruta];
        }
        return $ruta;
    }
}