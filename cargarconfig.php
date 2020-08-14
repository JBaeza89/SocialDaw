<?php

spl_autoload_register(function($class) {
    require_once str_replace('\\','/',$class) . ".php";
});//Tan solo es un require


// obtener configuración
if (!$config=parse_ini_file("config.ini")) {
    die ("No hay fichero configuración");
}

// El path hasta la app en la URL
$URL_PATH = $config['url_path'];

//Cargar funciones
require_once "funciones.php";

// Inicializar BD
dawfony\Klasto::init($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

