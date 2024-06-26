<!DOCTYPE html>
<html lang="es">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Federico Magnarelli">
    <meta name="description" content="Esquema simple para la gestion de productos">
    <title>Editar Productos</title>
</head>
<body>
    <h1>Editar Producto</h1>
    <form id="editProd" method="post">
        <input type="hidden" id="id" name="id" value="<?= $producto['id']; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= $producto['nombre']; ?>">
        <br>
        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" value="<?= $producto['precio']; ?>">
        <br>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">Volver al inicio</a>
</body>
</html>