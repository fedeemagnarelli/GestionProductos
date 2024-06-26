<?php

use GestionProductos\Gestion\ProductoModelo;
use PHPUnit\Framework\TestCase;

class ProductosCrudTest extends TestCase {

    private $modelo;

    protected function setUp(): void {
        $this->modelo = new ProductoModelo();
    }

    public function testLeerArchivo() {
        $productos = $this->modelo->obtenerTodos();
        $this->assertNotEmpty($productos);
    }
    
    public function testInsertar() {
        $producto = [
            'id' => 100,
            'nombre' => 'Test',
            'precio' => 1500,
            'created_at' => '2014-08-11T09:55:01 +03:00'
        ];

        $this->modelo->insertar($producto);

        $producto_insertado = $this->modelo->obtenerPorId(100);
        $productos = $this->modelo->obtenerTodos();

        $this->assertNotEmpty($productos);
        $this->assertGreaterThan(0, count($productos));
        $this->assertEquals($producto, $producto_insertado);
    }

    /* public function testActualizar() {
        $modelo = new ProductoModelo();
        $datos = [
            'id' => 1,
            'nombre' => 'Test',
            'precio' => 1500,
            'created_at' => '2014-08-11T09:55:01 +03:00'
        ];
        $modelo->insertar($datos);
        $insertado = $modelo->obtenerPorId(1);
        $this->assertNotEmpty($insertado);

        $prodActualizado = [
            'id' => 1,
            'nombre' => 'Producto act 1',
            'precio' => 3500,
            'created_at' => '2024-06-26T18:03:01 +03:00'
        ];

        $modelo->actualizar(1, $prodActualizado);

        $actualizado = $modelo->obtenerPorId(1);
        $this->assertNotEmpty($actualizado);
        $this->assertNotContainsEquals($datos, $actualizado);
    } */
}