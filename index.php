<?php
require 'vendor/autoload.php';

use GestionProductos\Controlador\ProductoControlador;
use GestionProductos\Gestion\ProductoModelo;

/* $mod = new ProductoModelo();
var_dump($mod->obtenerTodos());
exit; */

$controlador = new ProductoControlador();
if(isset($_GET['action'])) {
    return match ($_GET['action']) {
        'crear' => $controlador->crear(),
        'insertar' => $controlador->insertar($_POST),
        'editar' => $controlador->editar($_GET['id']),
        default => $controlador->index(),
    };
} else {
    $controlador->index();
}