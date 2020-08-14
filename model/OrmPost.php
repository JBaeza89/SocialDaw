<?php
namespace model;
use \dawfony\Klasto;
class OrmPost
{
    public function obtenerTodosLosPosts($page) {
        global $config;
        $limit = $config["post_per_page"];
        $offset = ($page -1) * $limit;
        $posts = Klasto::getInstance()->query(
            "SELECT `id`, `fecha`, `resumen`, `texto`, `foto`, `categoria_post_id`, `usuario_login`"
                . " FROM `post`"
                . " ORDER BY `fecha` DESC"
                . " LIMIT $limit OFFSET $offset",
            [],
            "model\Post"
        );
        return $posts;
    }

    public function contarTodosLosPosts() {
        return Klasto::getInstance()->queryOne("SELECT count(*) as cuenta FROM `post`")["cuenta"];
    }

    public function obtenerPostSeguidos($login, $page) {
        global $config;
        $limit = $config["post_per_page"];
        $offset = ($page -1) * $limit;
        $posts = Klasto::getInstance()->query(
            "SELECT `id`, `fecha`, `resumen`, `texto`, `foto`, `categoria_post_id`, `usuario_login`"
                . " FROM `post` JOIN `sigue` ON post.usuario_login = sigue.usuario_login_seguido"
                . " WHERE sigue.usuario_login_seguidor = ?"
                . " ORDER BY `fecha` DESC"
                . " LIMIT $limit OFFSET $offset",
            [$login],
            "model\Post"
        );
        return $posts;
    }

    public function contarPostsSeguidos($login) {
        return Klasto::getInstance()->queryOne(
            "SELECT count(*) as cuenta"
                . " FROM `post` JOIN `sigue` ON post.usuario_login = sigue.usuario_login_seguido"
                . " WHERE sigue.usuario_login_seguidor = ?",
            [$login]
        )["cuenta"];
    }

    public function obtenerPostsPorUsuario($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT id, fecha, resumen, texto, foto, categoria_post_id, usuario_login FROM post WHERE usuario_login = ? ORDER BY `fecha` DESC";
        return $bd->query($sql, $params, "model\Post");
    }

    public function annadirPost($post) {
        $bd = Klasto::getInstance();
        $params = [$post->fecha, $post->resumen, $post->texto, $post->foto, $post->categoria_post_id, $post->usuario_login];
        $sql = "INSERT INTO post(fecha, resumen, texto, foto, categoria_post_id, usuario_login) VALUES (?, ?, ?, ?, ?, ?)";
        return $bd->execute($sql, $params);
    }

    public function obtenerPostsPorId($id) {
        $bd = Klasto::getInstance();
        $params = [$id];
        $sql = "SELECT id, fecha, resumen, texto, foto, categoria_post_id, usuario_login FROM post WHERE id = ? ORDER BY `fecha` DESC";
        return $bd->queryOne($sql, $params, "model\Post");
    }

    public function obtenerCategoria($id) {
        $bd = Klasto::getInstance();
        $params = [$id];
        $sql = "SELECT descripcion FROM categoria_post WHERE id = ?";
        return $bd->queryOne($sql, $params);
    }

    public function darOQuitarLike($postid, $login) {
        $db = Klasto::getInstance();
        $num = $db->execute(
            "DELETE FROM `like` WHERE post_id = ? AND usuario_login = ?",
            [$postid, $login]
        );
        if ($num > 0) {
            return false; // Ya no tiene like
        }
        $db->execute(
            "INSERT INTO `like`(post_id, usuario_login) VALUES(?,?)",
            [$postid, $login]
        );
        return true; // Sí tiene like
    }

    public function contarLikes($postid) {
        return Klasto::getInstance()->queryOne(
            "SELECT count(*) as cuenta FROM `like` WHERE post_id = ?",
            [$postid]
        )["cuenta"];
    }

    public function tieneLike($postid, $login) {
        return (Klasto::getInstance()->queryOne(
            "SELECT count(*) as cuenta FROM `like` WHERE post_id = ? and usuario_login = ?",
            [$postid, $login]
        )["cuenta"]) > 0;
    }
}