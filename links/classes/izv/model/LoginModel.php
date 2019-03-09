<?php

namespace izv\model;

use izv\database\Database;
use izv\data\Usuario;
use izv\app\App;
use izv\database\Doctrine;
use izv\tools\Mail;
use izv\tools\Util;
use izv\tools\Pagination;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class LoginModel extends Model {
    
    function coincideUsuario($clave, $email){
        $doctrine = new Doctrine();
        $gestor = $doctrine->getEntityManager();
        $resultado = false;
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $email));

        if($usuario !== null) {
            if( $clave == $usuario->getClave() && $usuario->getActivo() != 0 ){
                $resultado = true;
            }
        }
        
        return $resultado;
    }
    
}