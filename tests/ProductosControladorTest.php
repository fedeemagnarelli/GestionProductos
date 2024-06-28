<?php

use GestionProductos\Controlador\ProductoControlador;
use GestionProductos\Gestion\ProductoModelo;
use PHPUnit\Framework\TestCase;

class ProductosControladorTest extends TestCase {
    private $controlador;
    private $modelo;

    protected function setUp() : void {
        $this->controlador = new ProductoControlador();
        $this->modelo = new ProductoModelo();
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    public function testInsertar() {
        $producto = [
            'token' => $_SESSION['token'],
            'nombre' => 'Producto de Test', 
            'precio' => 2996,
        ];
        $this->controlador->insertar($producto);
        $producto_insertado = $this->modelo->obtenerUltimoElemento();

        $this->assertNotEmpty($this->modelo->obtenerTodos());
        $this->assertEquals($producto['nombre'], $producto_insertado['nombre']);
    }

    public function testActualizar() {
        $producto_actualizar = $this->modelo->obtenerUltimoElemento();
        $id = $producto_actualizar['id'];
        $this->controlador->actualizar($id, [
            'token' => $_SESSION['token'],
            'nombre' => 'Producto de Test Actualizado', 
            'precio' => 3513,
        ]);
        $producto_actualizado = $this->modelo->obtenerUltimoElemento();

        $this->assertEquals($id, $producto_actualizado['id']);
        $this->assertEquals($producto_actualizado['nombre'], 'Producto de Test Actualizado');
    }

    public function testEliminar() {
        $producto_eliminar = $this->modelo->obtenerUltimoElemento();
        $id = $producto_eliminar['id'];
        $this->controlador->eliminar($id);

        $this->assertEmpty($this->modelo->obtenerPorId($id));
    }
}