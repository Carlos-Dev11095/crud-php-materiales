<?php
include('config/conexion.php');

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $unidad = $conn->real_escape_string($_POST['unidad_medida']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    
    $sql = "INSERT INTO materiales (nombre, unidad_medida, precio, stock) 
            VALUES ('$nombre', '$unidad', $precio, $stock)";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?msg=creado"); 
        exit();
    } else {
        $mensaje = "Error al agregar material: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Agregar Nuevo Material</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>➕ Agregar Nuevo Material</h1>
        
        <?php if (!empty($mensaje)): ?>
            <p class="message-error"><?= $mensaje ?></p>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del Material:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="unidad_medida">Unidad de Medida (Ej: Saco, M3):</label>
                <input type="text" id="unidad_medida" name="unidad_medida" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio Unitario:</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock (Existencias):</label>
                <input type="number" id="stock" name="stock" min="0" required>
            </div>
            
            <div class="acciones-formulario">
                <button type="submit">Guardar Material</button>
                <a href="index.php" class="btn-secundario">Volver al Catálogo</a>
            </div>
            </form>
        
        </div>
</body>
</html>
<?php $conn->close(); ?>