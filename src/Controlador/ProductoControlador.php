<?php

namespace GestionProductos\Controlador;

use Exception;
use GestionProductos\Gestion\ProductoModelo;

class ProductoControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new ProductoModelo();
        /* if(!$this->validarSesion()) {
            throw new Exception("Acceso denegado");
        } */
    }

    //Validador de sesion
    private function validarSesion() {
        if(!isset($_SESSION["id"])) {
            echo "<script>
                alert('Debes iniciar sesion');
            </script>";
            header("Location: index.php");
            exit;
        }
        return true;
    }

    //Funcion de validacion injexion de datos
    public function validadorInj($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    //Validacion de campos
    public function validacionDatos($producto) {
        if(empty($producto["nombre"])){
            return "Nombre vacio";
        }
        if(empty($producto["precio"]) || !is_numeric($producto["precio"])){
            return "Precio vacio o invalido";
        }

        return true;
    }

    public function index($pagina = 1, $search = null) {
        $productosPorPagina = 5;
        $productos = $this->modelo->obtenerTodos($pagina, $productosPorPagina, $search);
        $totalProductos = $this->modelo->obtenerCantidadTotal($search);
        include __DIR__.'/../Vistas/index.php';
    }

    public function crear() {
        include __DIR__.'/../Vistas/crear.php';
    }

    public function insertar($producto) {
        $validacion = $this->validacionDatos($producto);
        if($validacion !== true) {
            throw new Exception($validacion);
        }

        $producto['created_at'] = date('Y-m-d H:i:s');
        $this->modelo->insertar($this->validadorInj($producto));
        $this->logs('insert', 'el usuario'.$_SESSION['username'].' agrego un producto');
        return "Producto cargado correctamente";
    }

    public function editar($id) {
        $producto = $this->modelo->obtenerPorId($id);
        include __DIR__.'/../Vistas/editar.php';
    }

    public function actualizar($id, $producto) {
        $validacion = $this->validacionDatos($producto);
        if($validacion !== true) {
            throw new Exception($validacion);
        }

        $this->modelo->actualizar($this->validadorInj($id), $this->validadorInj($producto));
        $this->logs('actualizar', 'el usuario'.$_SESSION['username'].' actualizo un producto');
        return "Producto editado correctamente";       
    }

    public function eliminar($id) {
        $this->modelo->borrar($id);
        $this->logs('eliminar', 'el usuario'.$_SESSION['username'].' elimino un producto');
        return "Producto eliminado correctamente";
    }

    //Funcion de grabacion de los logs
    public function logs($accion, $desc) {
        $log = "[".date('Y-m-d H:i:s')."] - ".strtoupper($accion)." - ".strtoupper($desc)."\n";
        file_put_contents(__DIR__.'/src/logs/detalles.log', $log, FILE_APPEND);
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contorlador = new ProductoControlador();

    //Uso return match en vez de hacer un switch con break para mas simplicidad
    return match($_POST['action']) {
        'insertar' => $contorlador->insertar($_POST),

        'actualizar' => $contorlador->actualizar($_POST['id'], $_POST),

        'eliminar' => $contorlador->eliminar($_POST['id']),
        
        default => $contorlador->index(),
    };
}