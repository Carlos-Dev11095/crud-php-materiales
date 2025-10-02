<?php
// Incluye el archivo de conexión
include('config/conexion.php');

$busqueda = $_GET['nombre'] ?? ''; // Obtener término de búsqueda (si existe)
$mensaje = $_GET['msg'] ?? '';    // Obtener mensaje de estado

// Consulta SQL con filtro de búsqueda
$sql = "SELECT id, nombre, unidad_medida, precio, stock, (precio * stock) AS total_producto 
        FROM materiales";

if (!empty($busqueda)) {
    // Usamos LIKE para buscar coincidencias parciales, y real_escape_string para seguridad
    $sql .= " WHERE nombre LIKE '%" . $conn->real_escape_string($busqueda) . "%'";
}

$sql .= " ORDER BY id DESC"; // Ordenar por ID descendente

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Listado de Materiales de Construcción</title>
    <style>
        /* Estilos básicos para la prueba */
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .message-success { color: green; font-weight: bold; }
        .message-error { color: red; font-weight: bold; }
        .search-form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Catálogo de Materiales de Construcción</h1>
    
    <?php if ($mensaje == 'creado'): ?>
        <p class="message-success">✅ Material agregado exitosamente.</p>
    <?php elseif ($mensaje == 'eliminado'): ?>
        <p class="message-success">🗑️ Material eliminado exitosamente.</p>
    <?php elseif ($mensaje == 'modificado'): ?>
        <p class="message-success">✏️ Material modificado exitosamente.</p>
    <?php elseif ($mensaje == 'no_stock'): ?>
        <p class="message-error">❌ ERROR: El material no puede ser eliminado. Aún tiene existencias.</p>
    <?php endif; ?>

    <form class="search-form" action="index.php" method="GET">
        <input type="text" name="nombre" placeholder="Buscar por nombre..." value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">Buscar</button>
        <?php if (!empty($busqueda)): ?>
            <a href="index.php">Limpiar Búsqueda</a>
        <?php endif; ?>
    </form>
    
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
                    <a href="eliminar.php?id=<?= $fila['id'] ?>" 
                       onclick="return confirm('¿Seguro que deseas eliminar? (SOLO si el stock es 0)')">
                        Eliminar
                    </a>
                </td>
            </tr>
            <?php 
                endwhile; 
            else: 
            ?>
            <tr>
                <td colspan="6">No se encontraron materiales.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>