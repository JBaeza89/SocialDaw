<?php
function sanitizar($str) {
    return htmlspecialchars(stripslashes(trim($str)));
}
function validarImagen($img) {
    global $URL_PATH;
    $target_dir = "media/";
    $target_file = $target_dir . basename($img["name"]);
    $extension = substr($img["name"], strrpos($img["name"], "."));
    $tipoImagen = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($tipoImagen == "jpg" || $tipoImagen == "png" || $tipoImagen == "jpeg" || $tipoImagen == "gif" ) {
        move_uploaded_file($img["tmp_name"], $target_file);
        return true;
    } else {
        return false;
    }
}