<?php
include('config/conexion.php');
$sql = "SELECT id, nombre, unidad_medida, precio, stock, (precio * stock) AS total_producto FROM materiales ORDER BY id DESC";
$resultado = $conn->query($sql);
$mensaje = $_GET['msg'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Listado de Materiales de Construcción</title>
    <style>
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Catálogo de Materiales</h1>
    
    <?php if ($mensaje == 'creado'): ?>
        <p style="color: green;">✅ Material agregado exitosamente.</p>
    <?php endif; ?>

    <p><a href="crear.php">➕ Agregar Nuevo Material</a></p>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Total Producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($resultado && $resultado->num_rows > 0):
                while($fila = $resultado->fetch_assoc()): 
            ?>
            <tr>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= htmlspecialchars($fila['unidad_medida']) ?></td>
                <td>$<?= number_format($fila['precio'], 2) ?></td>
                <td><?= htmlspecialchars($fila['stock']) ?></td>
                <td>$<?= number_format($fila['total_producto'], 2) ?></td>
                <td>
                    <a href="editar.php?id=<?= $fila['id'] ?>">Modificar</a> |
                    <a href="eliminar.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar? Solo si Stock es 0.')">Eliminar</a>
                </td>
            </tr>
            <?php 
                endwhile; 
            else: 
            ?>
            <tr>
                <td colspan="6">No hay materiales registrados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>