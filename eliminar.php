<?php
include('config/conexion.php');

$id = 0;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql_check = "SELECT stock FROM materiales WHERE id = $id";
    $resultado = $conn->query($sql_check);
    
    if ($resultado && $resultado->num_rows == 1) {
        $material = $resultado->fetch_assoc();
        
        if ($material['stock'] == 0) {
            
            $sql_delete = "DELETE FROM materiales WHERE id = $id";
            if ($conn->query($sql_delete) === TRUE) {
                // Éxito
                header("Location: index.php?msg=eliminado");
            } else {
                // Error de DB
                header("Location: index.php?msg=db_error"); // Usamos 'msg' para consistencia con otros mensajes
            }
        } else {
            // Rechazar: Hay existencias
            header("Location: index.php?msg=no_stock");
        }
    } else {
        // Material no encontrado (ID no válido)
        header("Location: index.php?msg=not_found"); 
    }
} else {
    // Redirigir si no se proporcionó un ID
    header("Location: index.php");
}

$conn->close();
exit();
?>