<?php

namespace izv\model;

use izv\database\Doctrine;
use izv\data\Usuario;
use izv\data\Categoria;
use izv\data\Link;
use izv\tools\Mail;
use izv\tools\Util;
use izv\managedata\ManageCity;
use izv\tools\Pagination;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use izv\app\App;

class AjaxModel extends Model {
    
    function registrar($correo, $alias, $nombre, $clave, $apellido){
        
        $resultado = 0;
        
        $usuario = new Usuario();
        
        $usuario->setNombre($nombre);
        $usuario->setApellidos($apellido);
        $usuario->setAlias($alias);
        $usuario->setClave(Util::encriptar($clave));
        $usuario->setCorreo($correo);
        
        try{
            $doctrine = new Doctrine();
            $gestor = $doctrine->getEntityManager();
            
            $gestor->persist($usuario);
            $gestor->flush();
            
            Mail::sendActivation($usuario);
            
            $resultado = 1;
            
        } catch (\Exception $e){
            
        }
        
        return $resultado;
    }
    
    function activarUsuario($id, $code){
        $resultado = 0;
        
        $sendedMail = \Firebase\JWT\JWT::decode($code, App::JWT_KEY, array('HS256'));
        
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('id' => $id));
        if($usuario !== null) {
            $usuario->setActivo('1');
            try{
                $gestor->flush();
                $resultado = 1;
            } catch (\Exception $e){
                
            }
        }
        
        return $resultado;
        
    }
    
    function coincide($clave, $email){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = false;
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $email));

        if($usuario !== null) {
            if( Util::verificarClave($clave, $usuario->getClave()) && $usuario->getActivo() != 0 ){
                $resultado = true;
            }
        }
        
        return $resultado;
    }
    
    function obtenerClave($clave, $email){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = null;
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $email));

        if($usuario !== null) {
            if( Util::verificarClave($clave, $usuario->getClave()) && $usuario->getActivo() != 0 ){
                $resultado = $usuario->getClave();
            }
        }
        
        return $resultado;
    }
    
    function crearCategoria($nombre, $correo, $clave){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = 0;
        
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $correo));

        if($usuario !== null) {
            if( $clave == $usuario->getClave() && $usuario->getActivo() != 0 ){
                $resultado = $usuario->getId();
                $categoria = new Categoria();
                $categoria->setNombre($nombre);
                $categoria->setUsuario($usuario);
                try{
                    $gestor->persist($categoria);
                    $gestor->flush();
                    $resultado = 1;    
                } catch (\Exception $e){
                    
                }
                
            }
        }
        
        return $resultado;
    }
    
    function borrarCategoria($id){
        
        $resultado = 0;
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $categoria = $gestor->getRepository('izv\data\Categoria')->findOneBy(array('id' => $id));
            
        if($categoria != null){
            $links = $gestor->getRepository('izv\data\Link')->findBy(array('categoria' => $id));
                
            foreach($links as $link){
                $gestor->remove($link);
            }
            $gestor->flush();
                
            $categoria = $gestor->getRepository('izv\data\Categoria')->findOneBy(array('id' => $id));
            
            try{
                $gestor->remove($categoria);
                $gestor->flush();
                $resultado = 1;
            }catch (\Exception $e){
                
            }
        }
        
        return $resultado;
        
    }
    
    function crearEnlace($nombre, $comentario, $href, $categoria, $correo, $clave){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = 0;
        
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $correo));

        if($usuario !== null) {
            if( $clave == $usuario->getClave() && $usuario->getActivo() != 0 ){
                
                $cat = $gestor->getRepository('izv\data\Categoria')->findOneBy(array('id' => $categoria));
                
                if($cat != null){
                    $link = new Link();
        
                    $link->setTitle($nombre);
                    $link->setComentario($comentario);
                    $link->setCategoria($cat);
                    $link->setHref($href);
                    $link->setUsuario($usuario);
                    
                    try{
                        $gestor->persist($link);
                        $gestor->flush();
                        $resultado = 1;
                        
                    } catch (\Exception $e){
                    }
                }
            }
        }
        
        return $resultado;
        
    }
    
    function borrarEnlace($id){
        $resultado = 0;
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $link = $gestor->getRepository('izv\data\Link')->findOneBy(array('id' => $id));
        
        try{
            $gestor->remove($link);
            $gestor->flush();
            $resultado = 1;
        } catch(\Exception $e){
            
        }
        
        return $resultado;
    }
    
    function obtenerCategoriasUsuario($correo, $clave){
        
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = null;
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $correo));

        if($usuario !== null) {
            if( $clave == $usuario->getClave() && $usuario->getActivo() != 0 ){
                $resultado = $usuario->getId();
            }
        }
        
        $categorias = null;
        
        if(!$resultado == null){
            $categorias = $gestor->getRepository('izv\data\Categoria')->findBy(array('usuario' => $resultado));
        }
        
        return $categorias;
    }
    
    function obtenerEnlacesUsuario($correo, $clave){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = null;
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $correo));

        if($usuario !== null) {
            if( $clave == $usuario->getClave() && $usuario->getActivo() != 0 ){
                $resultado = $usuario->getId();
            }
        }
        
        $links = null;
        
        if(!$resultado == null){
            $links = $gestor->getRepository('izv\data\Link')->findBy(array('usuario' => $resultado));
        }
        
        return $links;
    }
    
}