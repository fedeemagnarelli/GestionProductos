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
            'nombre' => 'Producto de Test',
            'precio' => 1513,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->modelo->insertar($producto);

        $productos = $this->modelo->obtenerTodos();
        $producto_insertado = $this->modelo->obtenerUltimoElemento();

        $this->assertNotEmpty($productos);
        $this->assertGreaterThan(0, count($productos));
        $this->assertEquals($producto['nombre'], $producto_insertado['nombre']);
    }

    public function testActualizar() {
        $producto_actualizar = $this->modelo->obtenerUltimoElemento();
        $producto_actualizar['nombre'] = 'Producto test actualizado';

        $this->modelo->actualizar($producto_actualizar['id'], $producto_actualizar);
        $producto_actualizado = $this->modelo->obtenerPorId($producto_actualizar['id']);

        $this->assertEquals('Producto test actualizado', $producto_actualizado['nombre']);
    }

    //En este test vamos a eliminar el mismo producto que creamos y actualizamos anteriormente.
    public function testEliminar() {
        $producto_eliminar = $this->modelo->obtenerUltimoElemento();
        if(str_contains($producto_eliminar['nombre'], 'test')) {
            $id = $producto_eliminar['id'];
            $this->modelo->borrar($id);
        }

        $this->assertNull($this->modelo->obtenerPorId($id));
    }
}