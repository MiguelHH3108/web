<?php
include(__DIR__ .'/../Conexion.php');

$id = $_GET['IdDoc'];

$sql = "SELECT * FROM documentos WHERE IdDoc = '$id'";
$resultado = mysqli_query($conn, $sql);

if (mysqli_num_rows($resultado) == 1) {
    $fila = mysqli_fetch_assoc($resultado);
    $archivo = $fila['Archivo'];
    $ruta_archivo = "../Funciones/Documentos/" . $archivo;

    if (file_exists($ruta_archivo)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $archivo . '"');
        readfile($ruta_archivo);
    } else {
        echo "El archivo no existe en el servidor.";
    }
} else {
    echo "El archivo no se encontró en la base de datos.";
}
?>