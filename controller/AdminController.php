<?php
namespace controller;
use \model\OrmAdmin;
class AdminController extends Controller {

    public function borrarUsuario($login) {
        global $URL_PATH;
        $orm = new OrmAdmin;
        if ($_SESSION["rol"] == 1) {
            $orm->borrarPerfil($login);
        }        
        header("Location: $URL_PATH/");
    }

    public function borrarPost($id) {
        global $URL_PATH;
        $orm = new OrmAdmin;
        if ($_SESSION["rol"] == 1) {
            $orm->borrarPost($id);
        } 
        header("Location: $URL_PATH/");
    }
}

