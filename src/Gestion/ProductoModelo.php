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
    public function obtenerTodos($pagina = 1, $productosPorPagina = 5, $search = null) {
        $productos = $this->leer();
        if($search) {
            $productos = array_filter($productos, function($producto) use ($search) {
                return strpos($producto['nombre'], $search) !== false || 
                       strpos((string)$producto['precio'], $search) !== false || 
                       strpos($producto['created_at'], $search) !== false;
            });
        }

        $inicio = ($pagina - 1) * $productosPorPagina;

        //Retorno en partes con array slice los datos paginados
        return array_slice($productos, $inicio, $productosPorPagina);
    }

    public function obtenerCantidadTotal($search = null) {
        $productos = $this->leer();
        if($search) {
            $productos = array_filter($productos, function($producto) use ($search) {
                return strpos($producto['nombre'], $search) !== false || 
                       strpos((string)$producto['precio'], $search) !== false || 
                       strpos($producto['created_at'], $search) !== false;
            });
        }

        //Retornamos el total de los productos
        return count($productos);
    }

    //------------------------------------------------------------------------------------------------------------

    public function obtenerPorId($id) {
        $productos = $this->leer();
        foreach ($productos as $producto) {
            if ($producto['id'] == $id) {
                return $producto;
            }
        }

        return null;
    }

    //Funcion para obntener el ultimo elemento del archivo json para poder identificar el ultimo elemento insertado
    public function obtenerUltimoElemento() {
        $productos = $this->leer();
        return end($productos);
    }

    public function insertar($producto) {
        $productos = $this->leer();
        $producto['id'] = count($productos) + 1;
        $productos[] = $producto;
        $this->guardar($productos);
    }

    public function actualizar($id, $productoAct) {
        $productos = $this->leer();
        for($i = 0; $i < count($productos); $i++) {
            if($productos[$i]['id'] == $id) {
                $productoAct['id'] = $id;
                $productos[$i] = $productoAct;
                break;
            }
        }
        $this->guardar($productos);
        return "Producto actualizado exitosamente";
    }

    public function borrar($id) {
        $productos = $this->leer();
        $productos = array_filter($productos, function($producto) use ($id){
            return $producto['id'] != $id;
        });
        $this->guardar($productos);
    }
}