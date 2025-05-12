<?php
@session_start();

include "Conexion.php";

if(!isset($_POST['Usuario']) || !isset($_POST['Contra'])){
    header("Location: Index.php");
    exit();
}

if($stmt = $conn->prepare("SELECT IdU,Contraseña FROM Usuarios WHERE Usuario = ?")){
    $stmt->bind_param('s',$_POST['Usuario']);
    $stmt->execute();
}

$stmt->store_result();
if($stmt->num_rows > 0){
    $stmt->bind_result($id,$contraseña);
    $stmt->fetch();

    if($_POST["Contra"] === $contraseña){
        session_regenerate_id();
        $_SESSION['Usuario'] = TRUE;
        $_SESSION["name"] = $_POST["Usuario"];
        $_SESSION["IdU"] = $id;
        header("Location: Admin.php");
    }

    if($_POST["Contra"] !== $contraseña){
        header("Location: Admin.php");
    }

}else{
    header("Location: Admin.php");
  
}
 
$stmt->close();
?>