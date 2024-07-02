<?php
require 'vendor/autoload.php';

use GestionProductos\Controlador\ProductoControlador;

$controlador = new ProductoControlador();

$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : null;


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        return match ($_POST['action']) {
            'insertar' => $controlador->insertar($_POST),
            'editar' => $controlador->editar($_POST['id'], $_POST),
            'eliminar' => $controlador->eliminar($_POST['id']),
            default => $controlador->index($pagina, $search),
        };
    }
} else if(isset($_GET['action'])) {
    return match ($_GET['action']) {
        'crear' => $controlador->crear(),
        'insertar' => $controlador->insertar($_POST),
        'editar' => $controlador->editar($_GET['id']),
        'eliminar' => $controlador->eliminar($_GET['id']),
        default => $controlador->index($pagina, $search),
    };
} else {
    $controlador->index($pagina, $search);
}