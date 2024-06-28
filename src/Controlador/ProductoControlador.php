<?php

namespace GestionProductos\Controlador;

use Exception;
use GestionProductos\Gestion\ProductoModelo;

class ProductoControlador {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new ProductoModelo();
        //Si no esta logueado se le niega el acceso.
        /* if(!$this->validarSesion()) {
            throw new Exception("Acceso denegado");
        } */
    }

    //Generacion de token CSRF en la creacion de productos
    public function token() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['token'] = bin2hex(random_bytes(32));

        //Variable creada para testear la info de los logs.
        $_SESSION['username'] = 'fedee';
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
        session_start();
        $this->token();
        include __DIR__.'/../Vistas/crear.php';
    }

    public function insertar($producto) {
        session_start();
        //Variable creada para testear la info de los logs.
        if(isset($_SESSION['username'])) {
            $_SESSION['username'] = 'fedee';
        }

        if (!isset($producto['token']) || $producto['token'] !== $_SESSION['token']) {
            throw new Exception("CSRF token inválido.");
        }

        $validacion = $this->validacionDatos($producto);
        if($validacion !== true) {
            throw new Exception($validacion);
        }

        $producto['created_at'] = date('Y-m-d H:i:s');

        foreach ($producto as &$datos) {
            if(!$this->validadorInj($datos)){
                throw new Exception("Error en la validacion del campo: ".$datos);
            }
        }

        try {

            //Si todo sale bien insertamos el producto.
            $this->modelo->insertar($producto);
            $this->logs('insert', 'el usuario'.$_SESSION['username'].' agrego un producto');
            echo "<script>
                    alert('Se cargo correctamente el producto');
                </script>";
            header("Location: index.php");

        } catch (Exception $e) {
            echo "Hubo un error al insertar: " . ($e->getMessage());
        }

        
    }

    public function editar($id) {
        session_start();
        $this->token();
        $producto = $this->modelo->obtenerPorId($id);
        include __DIR__.'/../Vistas/editar.php';
    }

    public function actualizar($id, $producto) {
        session_start();
        //Variable creada para testear la info de los logs.
        if(isset($_SESSION['username'])) {
            $_SESSION['username'] = 'fedee';
        }

        if (!isset($producto['token']) || $producto['token'] !== $_SESSION['token']) {
            throw new Exception("CSRF token inválido.");
        }

        $validacion = $this->validacionDatos($producto);
        if($validacion !== true) {
            throw new Exception($validacion);
        }

        foreach ($producto as &$datos) {
            if(!$this->validadorInj($datos)){
                throw new Exception("Error en la validacion del campo: ".$datos);
            }            
        }

        $producto['created_at'] = date('Y-m-d H:i:s');

        try { 

            //Si todo sale bien actualizamos el producto.
            $this->modelo->actualizar($this->validadorInj($id), $producto);
            $this->logs('actualizar', 'el usuario'.$_SESSION['username'].' actualizo un producto');
            echo "<script>
                    alert('Producto editado correctamente');
                </script>"; 
            header("Location: index.php");

        }   catch (Exception $e) {
            echo "Hubo un error al actualizar: " . ($e->getMessage());
        }   
    }

    public function eliminar($id) {
        //Variable creada para testear la info de los logs.
        if(isset($_SESSION['username'])) {
            $_SESSION['username'] = 'fedee';
        }

        try {

            $this->modelo->borrar($this->validadorInj($id));
            $this->logs('eliminar', 'el usuario'.$_SESSION['username'].' elimino un producto');
            echo "<script>
                    alert('Producto eliminado correctamente');
                </script>";
            header("Location: index.php");

        } catch (Exception $e) {
            echo "Hubo un error al eliminar: " . ($e->getMessage());
        }
    }

    //Funcion de grabacion de los logs
    public function logs($accion, $desc) {
        $log = "[".date('Y-m-d H:i:s')."] - ".strtoupper($accion)." - ".strtoupper($desc)."\n";
        file_put_contents('../logs/detalles.log', $log, FILE_APPEND);
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contorlador = new ProductoControlador();

    //Uso return match en vez de hacer un switch con break para mas simplicidad
    return match($_GET['action']) {
        'insertar' => $contorlador->insertar($_POST),

        'editar' => $contorlador->actualizar($_POST['id'], $_POST),

        'eliminar' => $contorlador->eliminar($_POST['id']),
        
        default => $contorlador->index(),
    };
}