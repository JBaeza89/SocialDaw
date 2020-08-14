<?php
namespace controller;
use \model\Usuario;
use \model\OrmUsuario;
use \model\OrmPost;
class UsuarioController extends Controller {

    public function registro() {
        $title = "Registro";
        $errorLogin = "";
        $errorContrasenha = "";
        $login = "";
        $nombre = "";
        $email = "";
        echo \dawfony\Ti::render("view/RegistroView.phtml", compact('title', 'login', 'nombre', 'email', 'errorLogin', 'errorContrasenha'));
    }

    public function comprobarRegistro() {
        $usuario = new Usuario;
        $orm = new OrmUsuario;
        $error = false;
        $errorLogin = "";
        $errorContrasenha = "";
        $login = "";
        $nombre = "";
        $email = "";
        $title = "Registro";
        if ($orm->existeLogin($_POST["login"]) || $_POST["login"] == "") {
            $error = true;
            $errorLogin = $_POST["login"] == ""?"Login no puede estar vacio" : "El login ya existe";
        } else {
            $login = $_POST["login"];
            $usuario->login = $login;
        }
        
        if ($_POST["contrasenha"] == $_POST["repiteContrasenha"]) {
            $usuario->contrasenha = password_hash($_POST["contrasenha"], PASSWORD_DEFAULT);
        } else {
            $error = true;
            $errorContrasenha = "No coinciden las contraseñas";
        }
        $nombre = $_POST["nombre"];
        $usuario->nombre = $nombre;
        $email = $_POST["email"];
        $usuario->email = $email;
        if (!$error) {
            $filasInsertadas = $orm->registrarUsuario($usuario);
            $title = "Registro";
            echo \dawfony\Ti::render("view/RegistroView.phtml", compact('title', 'filasInsertadas'));
        } else {
            echo \dawfony\Ti::render("view/RegistroView.phtml", compact('title', 'login', 'nombre', 'email', 'errorLogin', 'errorContrasenha'));
        }
    }

    public function login() {
        $title = "Login";
        echo \dawfony\Ti::render("view/LoginView.phtml", compact('title'));
    }

    public function comprobarLogin() {
        global $URL_PATH;
        $login = $_POST["login"];
        $orm = new OrmUsuario;
        $title = "Login";
        $contrasenhaValida = $orm->recibirContrasenha($login);
        if (password_verify($_POST["contrasenha"], $contrasenhaValida["password"])) {
            $title = "Listado";
            $_SESSION["login"] = $login;
            $_SESSION["rol"] = $login == "admin" ? 1 : 0;
            header("Location: $URL_PATH/");
        } else {
            $error = "Lo sentimos, el usuario o la contraseña no son correctos";
            echo \dawfony\Ti::render("view/LoginView.phtml", compact('title', 'error'));
        }
    }

    public function cerrarSesion() {
        global $URL_PATH;
        unset($_SESSION["rol"]);
        unset($_SESSION["login"]);
        header("Location: $URL_PATH/");
    }

    public function perfil($login) {
        $title = "Perfil de $login";
        $ormPost = new OrmPost;
        $ormUsuario = new OrmUsuario;
        $posts = $ormPost->obtenerPostsPorUsuario($login);
        foreach ($posts as $post) {
            $post->categoria = $ormPost->obtenerCategoria($post->categoria_post_id)["descripcion"];
        }
        $usuario = $ormUsuario->obtenerUsuario($login);
        $seguidores = $ormUsuario->obtenerSeguidores($login);
        $siguiendo = $ormUsuario->obtenerSeguidos($login);
        $loSigues = isset($_SESSION["login"]) ? $ormUsuario->loSigues($_SESSION["login"], $login) > 0 : false;
        echo \dawfony\Ti::render("view/PerfilView.phtml", compact('title', 'login', 'posts', 
        'usuario', 'seguidores', 'siguiendo', 'loSigues'));
    }

    public function seguirUsuario($usuario) {
        global $URL_PATH;
        if (!isset($_SESSION["login"])) {
            throw new \Exception("usuario sin login no tiene boton....paiaso");
        }
        $orm = new OrmUsuario;
        $orm->seguir($_SESSION["login"], $usuario);
        header("Location: $URL_PATH/Perfil/$usuario");
    }

    public function dejarSeguirUsuario($usuario) {
        global $URL_PATH;
        if (!isset($_SESSION["login"])) {
            throw new \Exception("usuario sin login no tiene boton....paiaso");
        }
        $orm = new OrmUsuario;
        $orm->dejarDeSeguir($_SESSION["login"], $usuario);
        header("Location: $URL_PATH/Perfil/$usuario");
    }
}