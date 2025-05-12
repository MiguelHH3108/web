<?php

include(__DIR__ .'/../Conexion.php'); 

if ($_POST['funcion'] == "TablaClientes") {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $recordsPerPage = isset($_POST['recordsPerPage']) ? intval($_POST['recordsPerPage']) : 50;
    $offset = ($page - 1) * $recordsPerPage;

    $sqlCount = "SELECT COUNT(*) AS total FROM clientes";
    $resultCount = mysqli_query($conn, $sqlCount);
    $totalRecords = mysqli_fetch_assoc($resultCount)['total'];

    $sql = "SELECT * FROM clientes LIMIT $offset, $recordsPerPage";
    $result = mysqli_query($conn, $sql);

    $tablaC = "";
    while ($clientes = mysqli_fetch_assoc($result)) {
        $tablaC .= "<tr>
                        <td>".$clientes['Nombre']."</td>
                        <td>".$clientes['ApellidoP']."</td>
                        <td>".$clientes['ApellidoM']."</td>
                        <td>".$clientes['Telefono']."</td>
                        <td>".$clientes['Ciudad']."</td>
                        <td>".$clientes['Colonia']."</td>
                        <td>".$clientes['Calle']."</td>
                        <td>".$clientes['Num_ext']."</td>
                        <td>".$clientes['Num_int']."</td>
                        <td>".$clientes['Caso']."</td>
                        <td><button class='btn btn-warning editar' idregistros='".$clientes['IdClien']."'><i class='bi bi-pencil-square'></i></button> 
                        <button class='btn btn-danger eliminar' idregistros='".$clientes['IdClien']."'><i class='bi bi-trash-fill'></i></button></td>
                    </tr>";
    }

    $response = array(
        'html' => $tablaC,
        'totalRecords' => $totalRecords
    );

    echo json_encode($response);
    exit();
}

if ($_POST['funcion'] == "BuscarClientes") {
    $terminoBusqueda = $_POST['termino']; 

    $sql = "SELECT c.IdClien, p.Nombre, p.ApellidoP, p.ApellidoM, p.Telefono, d.Ciudad, d.Colonia, d.Calle, d.Num_ext, d.Num_int, cs.Caso 
            FROM Cliente c
            JOIN Persona p ON c.PersonaFK = p.IdPer
            JOIN Direccion d ON p.Direccion = d.IdDire
            JOIN Caso cs ON c.CasoFK = cs.IdCaso
            WHERE p.Nombre LIKE '%$terminoBusqueda%' 
               OR p.ApellidoP LIKE '%$terminoBusqueda%' 
               OR p.ApellidoM LIKE '%$terminoBusqueda%' 
               OR p.Telefono LIKE '%$terminoBusqueda%' 
               OR d.Ciudad LIKE '%$terminoBusqueda%' 
               OR d.Colonia LIKE '%$terminoBusqueda%' 
               OR d.Calle LIKE '%$terminoBusqueda%' 
               OR cs.Caso LIKE '%$terminoBusqueda%'";

    $result = mysqli_query($conn, $sql);

    $tablaC = "";
    while ($clientes = mysqli_fetch_assoc($result)) {
        $tablaC .= "<tr>
                        <td>".$clientes['Nombre']."</td>
                        <td>".$clientes['ApellidoP']."</td>
                        <td>".$clientes['ApellidoM']."</td>
                        <td>".$clientes['Telefono']."</td>
                        <td>".$clientes['Ciudad']."</td>
                        <td>".$clientes['Colonia']."</td>
                        <td>".$clientes['Calle']."</td>
                        <td>".$clientes['Num_ext']."</td>
                        <td>".$clientes['Num_int']."</td>
                        <td>".$clientes['Caso']."</td>
                        <td>
                            <button class='btn btn-warning editar' idregistros='".$clientes['IdClien']."'>Editar</button> 
                            <button class='btn btn-danger eliminar' idregistros='".$clientes['IdClien']."'>Eliminar</button>
                        </td>
                    </tr>";
    }

    $response = array(
        'html' => $tablaC
    );

    echo json_encode($response);
    exit();
}

    if ($_POST['funcion'] == "Guardar") {

        $nombre = $_POST['nombre'];
        $ApellidoP = $_POST['ApellidoP'];
        $ApellidoM = $_POST['ApellidoM'];
        $Telefono = $_POST['Telefono'];
        $Ciudad = $_POST['Ciudad'];
        $Colonia = $_POST['Colonia'];
        $Calle = $_POST['Calle'];
        $Num_ext = $_POST['Num_ext'];
        $Num_int = $_POST['Num_int'];
        $Caso = $_POST['Caso'];

    $sqlValiCliente = "SELECT Nombre,ApellidoP,ApellidoM,Telefono,Ciudad,Colonia,Calle,Num_ext,Num_int,Caso FROM Clientes WHERE Nombre = '$nombre' 
    AND ApellidoP = '$ApellidoP' AND ApellidoM = '$ApellidoM' AND Telefono = '$Telefono' AND Ciudad = '$Ciudad' AND Colonia = '$Colonia' AND Calle = '$Calle' 
    AND Num_ext = '$Num_ext' AND Num_int = '$Num_int' AND Caso = '$Caso'";
    $result = mysqli_query($conn, $sqlValiCliente);

    if (mysqli_num_rows($result) > 0) {
        echo "El cliente ya existe";
    }else{

        $sql_direccion = "INSERT INTO Direccion (Ciudad, Colonia, Calle, Num_ext, Num_int) VALUES ('$Ciudad', '$Colonia', '$Calle', '$Num_ext', '$Num_int')";
        
        if (mysqli_query($conn, $sql_direccion)) {
            $id_direccion = mysqli_insert_id($conn);

            $sql_persona = "INSERT INTO Persona (Nombre, ApellidoP, ApellidoM, Telefono, Direccion) VALUES ('$nombre', '$ApellidoP', '$ApellidoM', '$Telefono', '$id_direccion')";
            
            if (mysqli_query($conn, $sql_persona)) {
                $id_persona = mysqli_insert_id($conn);

                $sql_caso = "INSERT INTO Caso (Caso) VALUES ('$Caso')";

                if (mysqli_query($conn, $sql_caso)) {
                    $id_caso = mysqli_insert_id($conn);

                    $sql_cliente = "INSERT INTO Cliente (PersonaFK, CasoFK) VALUES ('$id_persona', '$id_caso')";

                    if (mysqli_query($conn, $sql_cliente)) {
                        echo "Guardado correctamente.";
                    } else {
                        echo "Error al insertar en Cliente: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error al insertar en Caso: " . mysqli_error($conn);
                }
            } else {
                echo "Error al insertar en Persona: " . mysqli_error($conn);
            }
        } else {
            echo "Error al insertar en Dirección: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}

    if($_POST['funcion']=='Editar'){
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $ApellidoP = $_POST['ApellidoP'];
        $ApellidoM = $_POST['ApellidoM'];
        $Telefono = $_POST['Telefono'];
        $Ciudad = $_POST['Ciudad'];
        $Colonia = $_POST['Colonia'];
        $Calle = $_POST['Calle'];
        $Num_ext = $_POST['Num_ext'];
        $Num_int = $_POST['Num_int'];
        $Caso = $_POST['Caso'];

        $sqlUpdate = "UPDATE Cliente JOIN Persona ON Cliente.PersonaFK = Persona.IdPer JOIN Direccion ON Persona.Direccion = Direccion.IdDire JOIN Caso ON Cliente.CasoFK = Caso.IdCaso SET Persona.Nombre = '$nombre', Persona.ApellidoP = '$ApellidoP', Persona.ApellidoM = '$ApellidoM', Persona.Telefono = '$Telefono', Direccion.Ciudad = '$Ciudad', Direccion.Colonia = '$Colonia', Direccion.Calle = '$Calle', Direccion.Num_ext = '$Num_ext', Direccion.Num_int = '$Num_int', Caso.Caso = '$Caso' WHERE Cliente.IdClien = '$id'";

        if(mysqli_query($conn, $sqlUpdate) === TRUE){
        echo "Actualizado";
        } else {
            echo "Error: ".mysqli_error($conn);
        }
    }

    if ($_POST['funcion'] == "Eliminar") {
        $id_cliente = $_POST['IdClien'];  
    
        $sql_delete = "DELETE Cliente, Persona, Direccion, Caso FROM Cliente JOIN Persona ON Cliente.PersonaFK = Persona.IdPer JOIN Direccion ON Persona.Direccion = Direccion.IdDire JOIN Caso ON Cliente.CasoFK = Caso.IdCaso WHERE Cliente.IdClien = '$id_cliente'";
    
        if ($conn->query($sql_delete) === TRUE) {
            echo "Eliminado";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    

    if($_POST['funcion']=="Modal"){
        if($_POST['tipo']=="Editar"){
            $id = $_POST['id'];
            $sql = "SELECT * FROM Clientes WHERE IdClien = '$id'";

            $resultado = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($resultado);

        }

        $Modal = "
        <div class = 'row'>

            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Nombre'>Nombre</b>
                    <input type='text' id='Nombre' class='form-control' value='".$row['Nombre']."'>
                </div>    
            </div>

            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'ApellidoP'>Apellido Paterno</b>
                    <input type='text' id='ApellidoP' class='form-control' value='".$row['ApellidoP']."'>
                </div>
            </div> 
            
            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'ApellidoM'>Apellido Materno</b>
                    <input type='text' id='ApellidoM' class='form-control' value='".$row['ApellidoM']."'>
                </div>
            </div>
            
            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Telefono'>Teléfono</b>
                    <input type='number' id='Telefono' class='form-control' maxlength='10' value='".$row['Telefono']."' oninput='this.value=this.value.slice(0,10)'>
                </div>
            </div>

            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Ciudad'>Ciudad</b>
                    <input type='text' id='Ciudad' class='form-control' value='".$row['Ciudad']."'>
                </div>
            </div>    

            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Colonia'>Colonia</b>
                    <input type='text' id='Colonia' class='form-control' value='".$row['Colonia']."'>
                </div>
            </div>
            
            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Calle'>Calle</b>
                    <input type='text' id='Calle' class='form-control' value='".$row['Calle']."'>
                </div>
            </div>
            
            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Num_ext'>Num. Exterior</b>
                    <input type='text' id='Num_ext' class='form-control' value='".$row['Num_ext']."'>
                </div>
            </div>
            
            <div class = 'col-md-4'>
                <div class = 'form-group'>
                    <b for = 'Num_int'>Num. Interior</b>
                    <input type='text' id='Num_int' class='form-control' value='".$row['Num_int']."'>
                </div>
            </div>    

            <div class = 'col-md-15'>
                <div class = 'form-group'>
                    <b for = 'Caso'>Caso</b>
                    <input type='text' id='Caso' class='form-control' value='".$row['Caso']."'>
                </div>
            </div>    

        </div>";

        echo $Modal;
        exit();
    }
?>