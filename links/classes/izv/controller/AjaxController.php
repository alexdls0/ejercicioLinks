<?php

namespace izv\controller;

use izv\tools\Session;
use izv\app\App;
use izv\model\Model;
use izv\tools\Reader;

class AjaxController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
        $this->getModel()->set('titulo', 'Bienvenido usuario');
        $this->getModel()->set('twigFolder', 'twigtemplates/twig');
        $this->getModel()->set('twigFile', '_sessionuser_landing.html');
    }
    
    function main() {
    }
    
    function registrarUsuario(){
        $nombre = Reader::read('nombreRegistrar');
        $apellido = Reader::read('apellidoRegistrar');
        $alias = Reader::read('aliasRegistrar');
        $email = Reader::read('emailRegistrar');
        $clave = Reader::read('claveRegistrar');
        $claveRep = Reader::read('claveRepRegistrar');
        
        //Comprobamos que el nombre sea solo alfabeto, sin espacios y no mayor a 50 caracteres
        if(strlen($nombre) > 50 || !ctype_alpha($nombre)){
           header('Location: ' . App::BASE . 'index');
           exit();
        }
        
        //Comprobamos que el apellido sea solo alfabeto y no mayor a 45 caracteres
        if(strlen($apellido) > 45 || !ctype_alpha($apellido)){
           header('Location: ' . App::BASE . 'index');
           exit();
        }
        
        //Comprobamos que el alias no tenga mas de 30 caracteres y no tenga espacios
        if($alias != null){
            if(strlen($alias) > 30 || strpos($alias, ' ')){
                header('Location: ' . App::BASE . 'index');
                exit();
            }
        }
        
        //Comprobamos que la clave tenga al menos 8 caracteres, no pase los 40, sin espacios y que contenga numeros y letras
        if(strlen($clave) < 8 || strlen($clave) > 40|| ctype_digit($clave) || ctype_alpha($clave) || strpos($clave, ' ')){
            header('Location: ' . App::BASE . 'index');
            exit();
        }
        
        if($claveRep != $clave){
            header('Location: ' . App::BASE . 'index');
            exit();
        }
        
        //pasamos toda esta informacion al modelo de sign para que añada al usuario a la base de datos
        //además ese método se encargará de enviar un correo si todo es correcto
        $r = $this->getModel()->registrar($email, $alias, $nombre, $clave, $apellido);
        
        $this->getModel()->set('registrarUsuario', $r);
    }
    
    function activar(){
        $id = Reader::read('id');
        $code = Reader::read('code');
        $r = $this->getModel()->activarUsuario($id, $code);
        header('Location: ' . App::BASE . 'index');
        exit();
    }
    
    function iniciarSesion(){
        $resultado = 0;
        $correo = Reader::read('emaillogin');
        $clave = Reader::read('clavelogin');
       
        $r = $this->getModel()->coincide($clave, $correo);
        
        if($r){
            $_SESSION['email'] = $correo;
            $_SESSION['password'] = $this->getModel()->obtenerClave($clave, $correo);
            if($_SESSION['password'] != null){
                $resultado = 1;
            }
        }
        
        $this->getModel()->set('iniciarSesion', $resultado);
        
    }
    
    function cerrarSesion(){
        session_start();
        unset($_SESSION['email']);
        unset($_SESSION['password']);
    }
    
    function crearCategoria(){
        $nombre = Reader::read('categorianombre');
        $r = $this->getModel()->crearCategoria($nombre, $_SESSION['email'], $_SESSION['password']);
        
        $this->getModel()->set('crearCategoria', $r);
        
    }
    
    function borrarCategoria(){
        $id = Reader::read('idcatborrar');
        $r = $this->getModel()->borrarCategoria($id);
        
        $this->getModel()->set('borrarCategoria', $r);
    }

    function crearEnlace(){
        $nombre = Reader::read('enlacenombre');
        $comentario = Reader::read('enlacecomentario');
        $href = Reader::read('enlacehref');
        $categoria = Reader::read('enlacecategoria');
        $this->getModel()->set('sesion', $nombre);
        
        $r = $this->getModel()->crearEnlace($nombre, $comentario, $href, $categoria, $_SESSION['email'], $_SESSION['password']);
        $this->getModel()->set('crearEnlace', $r);
    }

    function borrarEnlace(){
        $id = Reader::read('idlinkborrar');
        if($id >0){
            $r = $this->getModel()->borrarEnlace($id);
        
            $this->getModel()->set('borrarEnlace', $r);    
        }
        
    }
    
    function listadoCategorias(){
        if(isset($_SESSION['email']) && isset($_SESSION['password'])){
            $data['lista']= array();
            $categorias = $this->getModel()->obtenerCategoriasUsuario($_SESSION['email'], $_SESSION['password']); 
            
            foreach($categorias as $categoria) {
                $data['lista'][]= array('id' => $categoria->getId(), 'nombre' => $categoria->getNombre());
            }
            
            $this->getModel()->set('listadoCategorias', $data['lista']);
        }else{
            header('Location: ' . App::BASE . 'index');
            exit();
        }
    }
    
    function listadoEnlaces(){
        if(isset($_SESSION['email']) && isset($_SESSION['password'])){
            $data['lista']= array();
            $enlaces = $this->getModel()->obtenerEnlacesUsuario($_SESSION['email'], $_SESSION['password']); 
           
            foreach($enlaces as $enlace) {
                $data['lista'][]= array('id' => $enlace->getId(), 'idcategoria' => $enlace->getCategoria()->getNombre(),
                'titulo' => $enlace->getTitle(), 'enlace' => $enlace->getHref(), 'comentario' => $enlace->getComentario(),);
            }
            
            $this->getModel()->set('listadoEnlaces', $data['lista']);
        }else{
            header('Location: ' . App::BASE . 'index');
            exit();
        }
           
    }
    
}