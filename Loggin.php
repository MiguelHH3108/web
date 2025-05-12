<?php
session_start();

include "Conexion.php";

if(!isset($_POST['Usuario']) || !isset($_POST['Contra'])) {
    $_SESSION['error'] = "Por favor complete todos los campos";
    header("Location: Index.php");
    exit();
}
if($stmt = $conn->prepare("SELECT IdU, Contraseña FROM Usuarios WHERE Usuario = ?")) {
    $stmt->bind_param('s', $_POST['Usuario']);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0) {
        $stmt->bind_result($id, $contraseña);
        $stmt->fetch();

        if($_POST["Contra"] === $contraseña) {
            session_regenerate_id(true);
            
            $_SESSION['Usuario'] = TRUE;
            $_SESSION["name"] = $_POST["Usuario"];
            $_SESSION["IdU"] = $id;
            $_SESSION['last_activity'] = time();
            
            header("Location: Admin.php");
            exit();
        } else {
            $_SESSION['error'] = "Credenciales incorrectas";
            header("Location: Index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado";
        header("Location: Index.php");
        exit();
    }
    
    $stmt->close();
} else {
    $_SESSION['error'] = "Error en la consulta";
    header("Location: Index.php");
    exit();
}

$conn->close();
?>