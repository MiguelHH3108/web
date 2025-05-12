<?php
    @session_start();
    date_default_timezone_set("America/Mexico_City");

    $servername = "localhost";
    $database = "Abogado";
    $username = "root";
    $password = "12345";

    $conn = mysqli_connect($servername,$username,$password,$database);

    if(!$conn){
        die ("Error de conexión: " . mysqli_connect_error()); 
    }
?>