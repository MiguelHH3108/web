<?php
    include(__DIR__ .'/../Conexion.php'); 

    if($_POST['funcion'] == "TablaUsuarios"){
 
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $recordsPerPage = isset($_POST['recordsPerPage']) ? intval($_POST['recordsPerPage']) : 50;
        $offset = ($page - 1) * $recordsPerPage;

        $sqlCount = "SELECT COUNT(*) AS total FROM Usuario";
        $resultCount = mysqli_query($conn, $sqlCount);
        $rowCount = mysqli_fetch_assoc($resultCount)['total'];

        $sqlUsers = "SELECT * FROM Usuario LIMIT $offset, $recordsPerPage";
        $resultUsers = mysqli_query($conn, $sqlUsers);

        $tablaU = "";
        while($usuarios = mysqli_fetch_assoc($resultUsers)){
            $tablaU .="
                <tr>
                    <td>".$usuarios['Usuario']."</td>
                    <td>".$usuarios['Contraseña']."</td>
                    <td>".$usuarios['Nombre']."</td>
                    <td>".$usuarios['ApellidoP']."</td>
                    <td>".$usuarios['ApellidoM']."</td>
                    <td>".$usuarios['Telefono']."</td>
                    <td>".$usuarios['Ciudad']."</td>
                    <td>".$usuarios['Colonia']."</td>
                    <td>".$usuarios['Calle']."</td>
                    <td>".$usuarios['Num_ext']."</td>
                    <td>".$usuarios['Num_int']."</td>
                    <td><button class = 'btn btn-warning editar' idregistros = '".$usuarios['IdU']."'><i class='bi bi-pencil-square'></i></button>
                    <button class = 'btn btn-danger eliminar' idregistros ='".$usuarios['IdU']."'><i class='bi bi-trash-fill'></i></button></td>
                </tr>";
        }

        $response = array(
            'html' => $tablaU,
            'totalRecords' => $rowCount,
        );

        echo json_encode($response);
        exit();
    }

    if($_POST['funcion'] == 'BuscarUsuario'){
        $terminobusqueda = $_POST['termino'];

        $sql = "SELECT * FROM Usuario 
        WHERE Usuario LIKE '%$terminobusqueda%'
        OR Contraseña LIKE '%$terminobusqueda%'
        OR Nombre LIKE '%$terminobusqueda%'
        OR ApellidoP LIKE '%$terminobusqueda%'
        OR ApellidoM LIKE '%$terminobusqueda%'
        OR Telefono LIKE '%$terminobusqueda%'
        OR Ciudad LIKE '%$terminobusqueda%'
        OR Colonia LIKE '%$terminobusqueda%'
        OR Calle LIKE '%$terminobusqueda%'
        OR Num_ext LIKE '%$terminobusqueda%'
        OR Num_int LIKE '%$terminobusqueda%'";

        $result = mysqli_query($conn,$sql);

        $tablaU = "";

        while($usuarios = mysqli_fetch_assoc($result)){
            $tablaU .="
                <tr>
                    <td>".$usuarios['Usuario']."</td>
                    <td>".$usuarios['Contraseña']."</td>
                    <td>".$usuarios['Nombre']."</td>
                    <td>".$usuarios['ApellidoP']."</td>
                    <td>".$usuarios['ApellidoM']."</td>
                    <td>".$usuarios['Telefono']."</td>
                    <td>".$usuarios['Ciudad']."</td>
                    <td>".$usuarios['Colonia']."</td>
                    <td>".$usuarios['Calle']."</td>
                    <td>".$usuarios['Num_ext']."</td>
                    <td>".$usuarios['Num_int']."</td>
                    <td><button class = 'btn btn-warning editar' idregistros = '".$usuarios['IdU']."'>Editar</button>
                    <button class = 'btn btn-danger eliminar' idregistros ='".$usuarios['IdU']."'>Eliminar</button></td>
                </tr>";
        }

        $response = array(
            'html' => $tablaU,
        );

        echo json_encode($response);
        exit();
    }

    if($_POST['funcion'] == "Guardar"){
        
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        $nombre = $_POST['nombre'];
        $ApellidoP = $_POST['ApellidoP'];
        $ApellidoM = $_POST['ApellidoM'];
        $Telefono = $_POST['Telefono'];
        $Ciudad = $_POST['Ciudad'];
        $Colonia = $_POST['Colonia'];
        $Calle = $_POST['Calle'];
        $Num_ext = $_POST['Num_ext'];
        $Num_int = $_POST['Num_int'];

        $sqlvaliUsu = "SELECT Usuario,Contraseña,Nombre,ApellidoP,ApellidoM,Telefono,Ciudad,Colonia,Calle,Num_ext,Num_int FROM Usuario WHERE Usuario = '$usuario' AND Contraseña = '$contraseña' 
        AND Nombre = '$nombre' AND ApellidoP = '$ApellidoP' AND ApellidoM = '$ApellidoM' AND Telefono = '$Telefono' AND Ciudad = '$Ciudad' AND Colonia = '$Colonia' AND Calle = '$Calle' AND Num_ext = '$Num_ext' 
        AND Num_int = '$Num_int'";

        $resultU = mysqli_query($conn,$sqlvaliUsu);
    
        if(mysqli_num_rows($resultU) > 0){
            echo "El usuario ya existe";
        }else{
            $sql_direccion = "INSERT INTO Direccion (Ciudad, Colonia, Calle, Num_ext, Num_int) VALUES ('$Ciudad', '$Colonia', '$Calle', '$Num_ext', '$Num_int')";

            if(mysqli_query($conn,$sql_direccion)){
                $id_direccion = mysqli_insert_id($conn);

                $sql_persona = "INSERT INTO Persona (Nombre, ApellidoP, ApellidoM, Telefono, Direccion) VALUES ('$nombre', '$ApellidoP', '$ApellidoM', '$Telefono', '$id_direccion')";

                if(mysqli_query($conn,$sql_persona)){
                    $id_persona = mysqli_insert_id($conn);

                    $sql_abogado = "INSERT INTO Abogado(PersonaFK) VALUES ('$id_persona')";

                    if(mysqli_query($conn,$sql_abogado)){
                        $id_abogado = mysqli_insert_id($conn);

                        $sql_usuario = "INSERT INTO Usuarios(Usuario,Contraseña,Abogado)VALUES('$usuario','$contraseña','$id_abogado')";

                        if(mysqli_query($conn,$sql_usuario)){
                            echo "Usuario creado correctamente";
                        }else{
                            echo "Error al crear al usuario";
                        }

                    }else{
                        echo "Error al crear abogado";
                    }

                }else{
                    echo "Error al insertar persona";
                }

            }else{
                echo "Error al insertar la dirección";
            }
        }
        mysqli_close($conn);
    }

    if($_POST['funcion'] == 'Editar'){
        $id = $_POST['id'];
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];
        $nombre = $_POST['nombre'];
        $ApellidoP = $_POST['ApellidoP'];
        $ApellidoM = $_POST['ApellidoM'];
        $Telefono = $_POST['Telefono'];
        $Ciudad = $_POST['Ciudad'];
        $Colonia = $_POST['Colonia'];
        $Calle = $_POST['Calle'];
        $Num_ext = $_POST['Num_ext'];
        $Num_int = $_POST['Num_int'];

        $sqlUpdateU = "UPDATE Usuarios JOIN Abogado ON Usuarios.Abogado = Abogado.IdAbo JOIN Persona ON Abogado.PersonaFK = Persona.IdPer JOIN Direccion ON Persona.Direccion = Direccion.IdDire SET Usuarios.Usuario = '$usuario',Usuarios.Contraseña = '$contraseña', Persona.Nombre = '$nombre', Persona.ApellidoP = '$ApellidoP', Persona.ApellidoM = '$ApellidoM', Persona.Telefono = '$Telefono', Direccion.Ciudad = '$Ciudad', Direccion.Colonia = '$Colonia', Direccion.Calle = '$Calle', Direccion.Num_ext = '$Num_ext', Direccion.Num_int = '$Num_int' WHERE Usuarios.IdU = '$id'";

        if(mysqli_query($conn,$sqlUpdateU) == TRUE){
            echo "Actualizado";
        }else{
            echo "Error";
        }
    }

    if($_POST['funcion'] == "Eliminar"){
        $id_user = $_POST['IdU'];

        $sql_deleteU = "DELETE Usuarios,Abogado,Persona,Direccion FROM Usuarios JOIN Abogado ON Usuarios.Abogado = Abogado.IdAbo JOIN Persona ON Abogado.PersonaFK = Persona.IdPer JOIN Direccion ON Persona.Direccion = Direccion.IdDire WHERE Usuarios.IdU = '$id_user'";

        if($conn->query($sql_deleteU) == TRUE){
            echo "Eliminado";
        }else{
            echo "Error: ". mysqli_error($conn);
        }
    }

    if($_POST['funcion'] == "Modal"){
        if($_POST['tipo'] == "Editar"){
            $id = $_POST['id'];
            $sqlU = "SELECT * FROM Usuario WHERE IdU = '$id'";

            $resultadoU = mysqli_query($conn,$sqlU);
            $row = mysqli_fetch_assoc($resultadoU);
        }

        $Modal = "
            <div class = 'row'>

                <div class = 'col-md-4'>
                    <div class = 'form-group'>
                        <b for = 'Usuario'>Usuario</b>
                        <input type = 'text' id = 'Usuario' class = 'form-control' value = '".$row['Usuario']."'>
                    </div>
                </div>

                <div class = 'col-md-4'>
                    <div class = 'form-group'>
                        <b for = 'Contraseña'>Contraseña</b>
                        <input type = 'text' id = 'Contraseña' class = 'form-control' value = '".$row['Contraseña']."'>
                    </div>
                </div>

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

            </div>
        ";

        echo $Modal;
        exit();
    }
   
?>