<?php
$host = "localhost";
$usuario = "root";       
$password = "c4rl0511095";          
$db_nombre = "construccion"; 

$conn = new mysqli($host, $usuario, $password, $db_nombre);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>