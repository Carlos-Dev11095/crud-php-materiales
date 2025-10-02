<?php
include('config/conexion.php');

// 1. Obtener el término de búsqueda
// Usa el operador de fusión null (??) para obtener el valor o una cadena vacía si no existe
$busqueda = $_GET['nombre'] ?? ''; 

// 2. Consulta SQL base
$sql = "SELECT id, nombre, unidad_medida, precio, stock, (precio * stock) AS total_producto FROM materiales";

// 3. Modificar la consulta si hay un término de búsqueda
if (!empty($busqueda)) {
    // Usamos LIKE y % para buscar coincidencias parciales
    // Utilizamos real_escape_string para evitar inyección SQL básica
    $sql .= " WHERE nombre LIKE '%" . $conn->real_escape_string($busqueda) . "%'";
}

// 4. Ordenar los resultados
$sql .= " ORDER BY id DESC";

// Ejecutar la consulta
$resultado = $conn->query($sql);

$mensaje = $_GET['msg'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Catálogo de Materiales de Construcción</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .buscador { margin-bottom: 20px; }
        table { width: 90%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Catálogo de Materiales</h1>
    
    <?php if ($mensaje == 'creado'): ?>
        <p style="color: green;">✅ Material agregado exitosamente.</p>
    <?php endif; ?>

    <div class="buscador">
        <form action="index.php" method="GET">
            <input type="text" name="nombre" placeholder="Buscar material por nombre..." 
                   value="<?= htmlspecialchars($busqueda) ?>">
            <button type="submit">Buscar</button>
            <?php if (!empty($busqueda)): ?>
                <a href="index.php">Limpiar Búsqueda</a>
            <?php endif; ?>
        </form>
    </div>
    
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
                <td colspan="6">
                    <?php if (!empty($busqueda)): ?>
                        No se encontraron materiales que coincidan con "<?= htmlspecialchars($busqueda) ?>".
                    <?php else: ?>
                        No hay materiales registrados.
                    <?php endif; ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>