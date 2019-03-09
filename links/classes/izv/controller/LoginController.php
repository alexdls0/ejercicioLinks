<?php

namespace izv\controller;

use izv\app\App;
use izv\model\Model;
use izv\tools\Session;
use izv\tools\Util;
use izv\database\Database;
use izv\data\Usuario;
use izv\tools\Reader;

class LoginController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
    }
    
    function main() {
        if(isset($_SESSION['email']) && isset($_SESSION['password'])){
            var_export($_SESSION);
            $r = $this->getModel()->coincideUsuario($_SESSION['password'], $_SESSION['email']);
            
            if($r){
                header('Location: ' . App::BASE . 'user');
                exit();    
            }else{
                unset($_SESSION['email']);
                unset($_SESSION['password']);
                header('Location: ' . App::BASE . 'index');
                exit();
            }
        }
    }
       
}