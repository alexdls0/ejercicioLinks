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



class UserModel extends Model {
    
    
    //función para comprobar que existe una coincidencia de usuario con la base de datos
    function obtenerUsuario($correo, $clave){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = null;
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $correo));

        if($usuario !== null) {
            if( $clave == $usuario->getClave() && $usuario->getActivo() != 0 ){
                
                $alias = '-----';
                if(!is_null($usuario->getAlias())){
                        $alias = $usuario->getAlias();
                }
                
                $resultado = array('nombre' => $usuario->getNombre(), 'correo' => $usuario->getCorreo(), 
                'alias' => $alias);
            }
        }
        
        return $resultado;
    }
    
    function obtenerLinksUsuario($correo, $clave){
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
}