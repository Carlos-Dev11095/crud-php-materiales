<?php
include('config/conexion.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql_check = "SELECT stock FROM materiales WHERE id = $id";
    $resultado = $conn->query($sql_check);
    
    if ($resultado->num_rows == 1) {
        $material = $resultado->fetch_assoc();
        
        if ($material['stock'] == 0) {
            $sql_delete = "DELETE FROM materiales WHERE id = $id";
            if ($conn->query($sql_delete) === TRUE) {
                header("Location: index.php?msg=eliminado");
            } else {
                header("Location: index.php?error=db_error");
            }
        } else {
            header("Location: index.php?msg=no_stock");
        }
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
$conn->close();
exit();
?>