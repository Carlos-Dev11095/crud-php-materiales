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
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"] { padding: 8px; width: 300px; }
    </style>
</head>
<body>
    <h1>➕ Agregar Nuevo Material</h1>
    
    <?php if (!empty($mensaje)): ?>
        <p style="color: red;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form action="crear.php" method="POST">
        <div>
            <label for="nombre">Nombre del Material:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="unidad_medida">Unidad de Medida (Ej: Saco, M3):</label>
            <input type="text" id="unidad_medida" name="unidad_medida" required>
        </div>
        <div>
            <label for="precio">Precio Unitario:</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0.01" required>
        </div>
        <div>
            <label for="stock">Stock (Existencias):</label>
            <input type="number" id="stock" name="stock" min="0" required>
        </div>
        
        <button type="submit">Guardar Material</button>
    </form>
    
    <p><a href="index.php">Volver al Catálogo</a></p>
</body>
</html>
<?php $conn->close(); ?>