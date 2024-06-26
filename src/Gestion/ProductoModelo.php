<?php

namespace GestionProductos\Gestion;

use Exception;

class ProductoModelo {
    private $archivo;
    public function __construct() {
        $this->archivo = __DIR__.'/../Datos/productos.json';
    }

    private function leer() {
        if (!file_exists($this->archivo)) {
            return [];
        }

        $json = @file_get_contents($this->archivo);
        if($json === false) {
            throw new Exception('Error al leer el archivo de productos');
        }

        return json_decode($json, true);
    }

    private function guardar($productos) {
        if(@file_put_contents($this->archivo, json_encode($productos)) === false) {
            throw new Exception('Error al escribir el archivo de productos');
        }
    }

    //Dentro de estas funciones voy a crear la paginacion otra opcion podria haber sido el uso de DataTables o algun framework para la paginacion.
    public function obtenerCantidadTotal($search = null) {
        $productos = $this->leer();
        if($search){
            $productos = array_filter($productos, function($producto) use ($search){
                return strpos(strtolower($producto['nombre']), strtolower($search)) !== false;
            });
        }
        return count($productos);
    }

    public function obtenerTodos($pagina = 1, $productosPorPagina = 5, $search = null) {
        $productos = $this->leer();
        if($search){
            $productos = array_filter($productos, function($producto) use ($search){
                return strpos(strtolower($producto['nombre']), strtolower($search)) !== false;
            });
        }
        $inicio = ($pagina - 1) * $productosPorPagina;

        //Retorno en partes con array slice los datos paginados
        return array_slice($productos, $inicio, $productosPorPagina);
    }

    //------------------------------------------------------------------------------------------------------------

    public function obtenerPorId($id) {
        $productos = $this->leer();
        foreach ($productos as $producto) {
            var_dump($producto['id']);
            if ($producto['id'] == $id) {
                return $producto;
            }
        }

        return null;
    }

    public function insertar($producto) {
        $productos = $this->leer();
        $producto['id'] = count($productos) + 1;
        $productos[] = $producto;
        $this->guardar($productos);
    }

    public function actualizar($id, $productoAct) {
        $productos = $this->leer();
        foreach ($productos as &$producto) {
            if ($producto['id'] == $id) {
                $producto = array_merge($producto, $productoAct);
                $this->guardar($producto);
                return;
            }
        }
    }

    public function borrar($id) {
        $productos = $this->leer();
        $productos = array_filter($productos, function($producto) use ($id){
            return $producto['id'] != $id;
        });
        $this->guardar($productos);
    }
}