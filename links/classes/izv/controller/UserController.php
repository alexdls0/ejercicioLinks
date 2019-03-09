<?php

namespace izv\controller;

use izv\tools\Session;
use izv\app\App;
use izv\model\Model;
use izv\tools\Reader;

class UserController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
        $this->getModel()->set('titulo', 'Bienvenido usuario');
        $this->getModel()->set('twigFolder', 'twigtemplates/twig');
        $this->getModel()->set('twigFile', '_sessionuser_landing.html');
    }
    
    function main() {
        $data['lista'] = array();
        if(!isset($_SESSION['email']) || !isset($_SESSION['password'])){
            header('Location: ' . App::BASE . 'index');
            exit();
        }else{
            $_POST = array();
            $r = $this->getModel()->obtenerUsuario($_SESSION['email'], $_SESSION['password']); 
            if($r != null){
                $this->getModel()->set('titulo', 'Bienvenido '.$r['nombre']);
                $this->getModel()->set('user', $r);        
                
                $links = $this->getModel()->obtenerLinksUsuario($_SESSION['email'], $_SESSION['password']); 
                
                foreach($links as $link) {
                    $data['lista'][]= array('titulo' => $link->getTitle(), 'enlace' => $link->getHref()
                                    , 'comentario' => $link->getComentario(), 'categoria' => $link->getCategoria()->getNombre()
                                    , 'id' => $link->getId());
                }
                
                $this->getModel()->set('lista', $data['lista']);
                
                $categorias = $this->getModel()->obtenerCategoriasUsuario($_SESSION['email'], $_SESSION['password']); 
                if(count($categorias)>0){
                    foreach($categorias as $categoria) {
                        $data['categorias'][]= array('id' => $categoria->getId(), 'nombre' => $categoria->getNombre());
                    }
                    
                    $this->getModel()->set('categorias', $data['categorias']);    
                }else{
                    $this->getModel()->set('categorias', '');    
                }
                
            }
        }
    }
    
}