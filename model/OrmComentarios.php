<?php
namespace model;
use \dawfony\Klasto;


class OrmComentarios {

    public function annadirComentario($comentario) {
        $bd = Klasto::getInstance();
        $params = [$comentario->post_id, $comentario->usuario_login, $comentario->fecha, $comentario->texto];
        $sql = "INSERT INTO comenta(post_id, usuario_login, fecha, texto) VALUES(?,?,?,?)";
        $bd->execute($sql, $params);
    }

    public function obtenerComentariosDePost($post_id) {
        $bd = Klasto::getInstance();
        $params = [$post_id];
        $sql = "SELECT post_id, usuario_login, fecha, texto FROM comenta WHERE post_id = ? ORDER BY fecha DESC";
        return $bd->query($sql, $params, 'model\Comentario');
    }

    public function cuentaComentarios($post_id) {
        $bd = Klasto::getInstance();
        $params = [$post_id];
        $sql = "SELECT COUNT(*) as cuenta FROM comenta WHERE post_id = ?";
        return $bd->queryOne($sql, $params)["cuenta"];
    }
}