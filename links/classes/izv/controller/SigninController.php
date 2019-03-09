<?php

namespace izv\controller;

use izv\app\App;
use izv\model\Model;
use izv\tools\Session;

class SigninController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
    }
    
    function main() {
        if(isset($_SESSION['email']) || isset($_SESSION['password'])){
            header('Location: ' . App::BASE . 'index');
            exit();
        }
    }
    
}