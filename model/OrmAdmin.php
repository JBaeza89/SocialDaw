<?php
namespace model;
use \dawfony\Klasto;
class OrmAdmin {

    public function borrarSeguimientos($login) {
        $bd = Klasto::getInstance();
        $params = [$login, $login];
        $sql = "DELETE FROM sigue WHERE usuario_login_seguidor=? OR usuario_login_seguido=?";
        $bd->execute($sql, $params);
    }

    public function borrarLikes($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "DELETE FROM `like` WHERE usuario_login = ? ";
        $bd->execute($sql, $params);
        $sql = "DELETE FROM `like` WHERE post_id IN (SELECT id FROM post WHERE usuario_login = ?)";
        $bd->execute($sql, $params);        
    }

    public function borrarComentarios($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "DELETE FROM comenta WHERE usuario_login = ? ";
        $bd->execute($sql, $params);
        $sql = "DELETE FROM comenta WHERE post_id IN (SELECT id FROM post WHERE usuario_login = ?)";
        $bd->execute($sql, $params);
    }

    public function borrarPosts($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "DELETE FROM post WHERE usuario_login = ? ";
        $bd->execute($sql, $params);
    }

    public function borrarUsuario($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "DELETE FROM usuario WHERE login = ? ";
        $bd->execute($sql, $params);
    }


    public function borrarPerfil($login) {
        $bd = Klasto::getInstance();
        $bd->startTransaction();
        $this->borrarSeguimientos($login);
        $this->borrarLikes($login);
        $this->borrarComentarios($login);
        $this->borrarPosts($login);
        $this->borrarUsuario($login);
        $bd->commit();
    }

    public function borrarPost($id) {
        $bd = Klasto::getInstance();
        $bd->startTransaction();
        $params = [$id];
        $sql = "DELETE FROM `like` WHERE post_id = ?";  
        $bd->execute($sql, $params);  
        $sql = "DELETE FROM comenta WHERE post_id = ?";  
        $bd->execute($sql, $params);    
        $sql = "DELETE FROM post WHERE id = ? ";
        $bd->execute($sql, $params);
        $bd->commit();
    }
}