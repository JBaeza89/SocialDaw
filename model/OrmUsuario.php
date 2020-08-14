<?php
namespace model;
use \dawfony\Klasto;
class OrmUsuario
{
    public function existeLogin($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT login FROM usuario WHERE login = ?";
        return $bd->queryOne($sql, $params);
    }

    public function registrarUsuario($usuario) {
        $bd = Klasto::getInstance();
        $login = $usuario->login;
        $contrasenha = $usuario->contrasenha;
        $rol = 0;
        $nombre = $usuario->nombre;
        $email = $usuario->email;
        $params = [$login, $contrasenha, $rol, $nombre, $email];
        $sql = "INSERT INTO usuario VALUES (?, ?, ?, ?, ?)";
        return $bd->execute($sql, $params);
    }

    public function recibirContrasenha($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT password FROM usuario WHERE login = ?";
        return $bd->queryOne($sql, $params);
    }

    public function obtenerUsuario($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT login, rol_id, nombre, email FROM usuario WHERE login = ?";
        return $bd->queryOne($sql, $params, "model\Usuario");
    }

    public function obtenerSeguidores($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT COUNT(usuario_login_seguidor) as cuenta FROM sigue WHERE usuario_login_seguido = ?";
        return $bd->queryOne($sql, $params);
    }

    public function obtenerSeguidos($login){
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT COUNT(usuario_login_seguido) as cuenta FROM sigue WHERE usuario_login_seguidor = ?";
        return $bd->queryOne($sql, $params);
    }

    public function loSigues($logUsuario, $logPerfil) {
        $bd = Klasto::getInstance();
        $params = [$logUsuario, $logPerfil]; 
        $sql = "SELECT usuario_login_seguidor, usuario_login_seguido FROM sigue WHERE 
        usuario_login_seguidor = ? AND usuario_login_seguido = ?"; 
        return count($bd->query($sql, $params));
    }

    public function seguir($login, $loginASeguir) {
        $bd = Klasto::getInstance();
        $params = [$login, $loginASeguir];
        $sql = "INSERT INTO sigue(usuario_login_seguidor, usuario_login_seguido) VALUES(?,?)";
        $bd->execute($sql, $params);        
    }

    public function dejarDeSeguir($login, $loginADejar) {
        $bd = Klasto::getInstance();
        $params = [$login, $loginADejar];
        $sql = "DELETE FROM sigue WHERE usuario_login_seguidor=? AND usuario_login_seguido=?";
        $bd->execute($sql, $params);
    }

}