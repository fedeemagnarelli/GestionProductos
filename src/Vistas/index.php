<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Federico Magnarelli">
    <meta name="description" content="Esquema simple para la gestion de productos">
    <title>Productos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="src/js/main.js"></script>
</head>
<body>
    <h1>Gestion de Productos</h1>
    <!-- Apartado busqueda -->
     <form id="searchForm">
         <input type="text" name="search" id="search" placeholder="Buscar producto...">
         <button type="submit">Buscar</button>
     </form>

    <!-- Apartado de productos -->
    <a href="index.php?action=crear">Crear nuevo producto</a>
    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Fecha Creacion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto) { ?>
                <tr>
                    <td><?= $producto['id'] ?></td>
                    <td><?= $producto['nombre'] ?></td>
                    <td><?= $producto['precio'] ?></td>
                    <td><?= $producto['created_at'] ?></td>
                    <td>
                        <a href="index.php?action=editar&id=<?= $producto['id'] ?>">Editar</a>
                        <a href="index.php?action=eliminar&id=<?= $producto['id'] ?>">Borrar</a>
                        <!-- <button class='deleteProd' value='<?= $producto['id'] ?>'>Borrar</button> -->
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Footer para la paginacion -->
     <div class="paginacion">
         <?php for ($i = 1; $i <= ceil($totalProductos / 5); $i++) { ?>
            <a href="index.php?pagina=<?= $i ?>&search=<?= htmlspecialchars($search) ?>">Pagina <?= $i ?></a>
         <?php } ?>
     </div>
</body>
</html>