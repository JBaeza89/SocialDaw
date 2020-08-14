<?php
require 'vendor/autoload.php';
require 'cargarconfig.php'; 

use NoahBuscher\Macaw\Macaw;
use controller\PruebaController;

session_start();

Macaw::get($URL_PATH . '/', "controller\PostController@listado");

Macaw::get($URL_PATH . '/Listado/Pag/(:any)', "controller\PostController@listado");

Macaw::get($URL_PATH . '/Registrate', "controller\UsuarioController@registro");

Macaw::post($URL_PATH . '/Registrate', "controller\UsuarioController@comprobarRegistro");

Macaw::get($URL_PATH . '/Login', "controller\UsuarioController@login");

Macaw::post($URL_PATH . '/Login', "controller\UsuarioController@comprobarLogin");

Macaw::get($URL_PATH . '/CerrarSesion', "controller\UsuarioController@cerrarSesion");

Macaw::get($URL_PATH . '/Perfil/(:any)', "controller\UsuarioController@perfil");

Macaw::get($URL_PATH . '/AddPost', "controller\PostController@addPost");

Macaw::post($URL_PATH . '/AddPost', "controller\PostController@comprobarPost");

Macaw::get($URL_PATH . '/Seguidos', "controller\PostController@postSeguidos");

Macaw::get($URL_PATH . '/Siguiendo/Pag/(:any)', "controller\PostController@postSeguidos");

Macaw::get($URL_PATH . '/Perfil/(:any)/seguir', "controller\UsuarioController@seguirUsuario");

Macaw::get($URL_PATH . '/Perfil/(:any)/noseguir', "controller\UsuarioController@dejarSeguirUsuario");

Macaw::post($URL_PATH . '/Post/(:num)/comentario', "controller\ComentarioController@nuevoComentario");

Macaw::get($URL_PATH . '/Post/(:any)', "controller\PostController@visualizarPost");

Macaw::get($URL_PATH . '/Api/Like/(:any)', "controller\ApiController@likeClicked");

Macaw::get($URL_PATH . '/Admin/borrarusuario/(:any)', "controller\AdminController@borrarUsuario");

Macaw::get($URL_PATH . '/Admin/borrarpost/(:any)', "controller\AdminController@borrarPost");

Macaw::error(function() {
  echo '404 :: Not Found';
});

Macaw::dispatch();