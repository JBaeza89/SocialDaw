<?php
namespace controller;
use \model\Comentario;
use \model\OrmComentarios;
class ComentarioController extends Controller {

    public function nuevoComentario($id_post) {
        global $URL_PATH;
        $comentario = new Comentario;
        $comentario->post_id = $id_post;
        $comentario->usuario_login = $_SESSION["login"];
        $comentario->fecha = date('Y-m-d H:i:s');
        $comentario->texto = sanitizar($_POST["texto"]);
        (new OrmComentarios)->annadirComentario($comentario);
        header("Location: $URL_PATH/Post/$id_post");        
    }
}
