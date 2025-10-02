<?php
include('config/conexion.php');

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $unidad = $conn->real_escape_string($_POST['unidad_medida']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    
    $sql = "UPDATE materiales SET 
            nombre = '$nombre', 
            unidad_medida = '$unidad', 
            precio = $precio, 
            stock = $stock 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?msg=modificado");
        exit();
    } else {
        $mensaje = "Error al modificar material: " . $conn->error;
    }
} 

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql_select = "SELECT * FROM materiales WHERE id = $id";
    $resultado = $conn->query($sql_select);
    
    if ($resultado->num_rows === 1) {
        $material = $resultado->fetch_assoc();
    } else {
        header("Location: index.php?error=no_existe");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Modificar Material</title>
    </head>
<body>
    <h1>✏️ Modificar Material: <?= htmlspecialchars($material['nombre']) ?></h1>
    
    <?php if (!empty($mensaje)): ?>
        <p style="color: red;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?= $material['id'] ?>">

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($material['nombre']) ?>" required>
        </div>
        <div>
            <label for="unidad_medida">Unidad:</label>
            <input type="text" id="unidad_medida" name="unidad_medida" value="<?= htmlspecialchars($material['unidad_medida']) ?>" required>
        </div>
        <div>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0.01" value="<?= htmlspecialchars($material['precio']) ?>" required>
        </div>
        <div>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="0" value="<?= htmlspecialchars($material['stock']) ?>" required>
        </div>
        
        <button type="submit">Guardar Cambios</button>
    </form>
    
    <p><a href="index.php">Volver al Catálogo</a></p>
</body>
</html>
<?php $conn->close(); ?>