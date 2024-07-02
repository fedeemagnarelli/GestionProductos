<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Federico Magnarelli">
    <meta name="description" content="Esquema simple para la gestion de productos">
    <title>Crear Productos</title>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="src/js/main.js"></script> -->
</head>
<body>
    <h1>Crear Producto</h1>
    <form id="crearProd" method="post">
        <input type="hidden" name="action" value="crear">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" required>
        <br>
        <button type="submit">Guardar</button>
        <!-- <button type="button" onclick="crearProducto($('#crearProd').serialize())">Guardar</button> -->
    </form>
    <a href="index.php">Volver al inicio</a>
</body>
</html>