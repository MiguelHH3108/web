<?php
    include(__DIR__ .'/../Conexion.php'); 

    if($_POST['funcion'] == "TablaPagos"){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $recordsPerPage = isset($_POST['recordsPerPage']) ? intval($_POST['recordsPerPage']) : 50;
        $offset = ($page - 1) * $recordsPerPage;

        $sqlCount = "SELECT COUNT(*) AS total FROM Pagos";
        $resultCount = mysqli_query($conn,$sqlCount);
        $totalRecords = mysqli_fetch_assoc($resultCount)['total'];

        $sqlPagos = "SELECT * FROM Pagos LIMIT $offset, $recordsPerPage";
        $resultPagos = mysqli_query($conn,$sqlPagos);

        $tablaPagos = "";
        while($pagos = mysqli_fetch_assoc($resultPagos)){
            $tablaPagos .= "<tr> 
                            <td>".$pagos['Nombre']."</td>
                            <td>".$pagos['ApellidoP']."</td>   
                            <td>".$pagos['ApellidoM']."</td>
                            <td>$ ".$pagos['PagoPendiente']."</td>
                            <td><button class = 'btn btn-warning editar' idregistros = '".$pagos['IdPago']."'><i class='bi bi-pencil-square'></i></button>
                            <button class = 'btn btn-danger eliminar' idregistros ='".$pagos['IdPago']."'><i class='bi bi-trash-fill'></i></button>
                            </td>
                        </tr>";
        }

        $response = array(
            'html' => $tablaPagos,
            'totalRecords' => $totalRecords
        );
        echo json_encode($response);
        exit();

    }

    if($_POST['funcion'] == 'BuscarPagos'){
        $terminobusqueda = $_POST['termino'];

        $sqlPagos = "SELECT * FROM Pagos
        WHERE Nombre LIKE '%$terminobusqueda%' 
        OR  ApellidoP LIKE '%$terminobusqueda%' 
        OR  ApellidoM LIKE '%$terminobusqueda%'
        OR  PagoPendiente LIKE '%$terminobusqueda%' ";

        $resultPagos = mysqli_query($conn,$sqlPagos);

        $tablaPagos = "";
        while($pagos = mysqli_fetch_assoc($resultPagos)){
            $tablaPagos .= "<tr> 
                            <td>".$pagos['Nombre']."</td>
                            <td>".$pagos['ApellidoP']."</td>   
                            <td>".$pagos['ApellidoM']."</td>
                            <td>$ ".$pagos['PagoPendiente']."</td>
                            <td><button class = 'btn btn-warning editar' idregistros = '".$pagos['IdPago']."'>Editar</button>
                            <button class = 'btn btn-danger eliminar' idregistros ='".$pagos['IdPago']."'>Eliminar</button>
                            </td>
                        </tr>";
        }
        $response = array(
            'html' => $tablaPagos,
        );

        echo json_encode($response);
        exit();
    }

    if($_POST['funcion'] == 'GuardarPago'){
        $cliente = $_POST['cliente'];
        $pago = $_POST['Pago'];

        $verificarPago = "SELECT * FROM pago WHERE ClienteFK = '$cliente'";
        $resultadoVerificar = mysqli_query($conn, $verificarPago);
        
        if(mysqli_num_rows($resultadoVerificar) > 0){
            echo "El cliente ya tiene un pago registrado.";
            exit();
        }
        
        $sqlPago = "INSERT INTO pago(ClienteFK, PagoPendiente) 
                    VALUES ('$cliente', '$pago')";
        
        if(mysqli_query($conn, $sqlPago)){
            echo "Pago registrado correctamente.";
        } else {
            echo "Error al registrar el pago: " . mysqli_error($conn);
        }
        
        mysqli_close($conn);
        exit();
    }

    if($_POST['funcion'] == 'Editar'){
        $id = $_POST['IdPago'];
        $clienteedit = $_POST['cliente'];
        $pagoedit = $_POST['Pago']; 
    
        $sqlupdatepago = "UPDATE pago SET ClienteFK = '$clienteedit', PagoPendiente = '$pagoedit' WHERE IdPago = '$id'";

        if($conn->query($sqlupdatepago) === TRUE){
            echo "Pago actualizado correctamente.";
        } else {
            echo "Error al actualizar el pago: " . mysqli_error($conn);
        }
        exit();
    }

    if($_POST['funcion'] == 'Eliminar'){
        $id = $_POST['IdPago'];
        
        $sqldeletepago = "DELETE FROM pago WHERE IdPago = '$id'";
    
        if($conn->query($sqldeletepago) === TRUE){
            echo "Pago eliminado correctamente.";
        } else {
            echo "Error al eliminar el pago: " . mysqli_error($conn);
        }
        exit();
    }

    if($_POST['funcion'] == 'ModalPago'){
        if($_POST['tipo'] == 'Editar'){
            $id = $_POST['id'];
            $sql = "SELECT * FROM Pagos WHERE IdPago = '$id'";
            
            $result = mysqli_query($conn, $sql);
            
            if($result && mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
            } else {
                echo "Error al obtener los datos del pago: " . mysqli_error($conn);
                exit();
            }

        }

        $ModalPago = "
        <div class = 'row'>
        
                <div class='col-sm-7'>
                    <div class='form-group'>
                        <label for='cliente'>Cliente</label>
                            <select class='form-control' id='cliente' name='cliente' required>
                                <option value='".$row['Nombre']."' >Selecciona un cliente</option>";

                                $clienteselect = "SELECT cliente.IdClien, persona.Nombre, persona.ApellidoP, persona.ApellidoM 
                                                FROM cliente 
                                                JOIN persona ON cliente.PersonaFK = persona.IdPer";
                                $resulCliSelect = mysqli_query($conn, $clienteselect);

                                while ($rowP = mysqli_fetch_assoc($resulCliSelect)) {
                                   
                                    $nombreCompleto = $rowP['IdClien'] . " " . $rowP['Nombre'] . " " . $rowP['ApellidoP'] . " " . $rowP['ApellidoM'];

                                    $ModalPago .= "<option value='".$rowP['IdClien']."'".($rowP['IdClien'] == $clienteedit ? ' selected' : '').">".$nombreCompleto."</option>";
                                }

                            $ModalPago .= "
                            </select>
                    </div>
                </div>

                <div class = 'col-12'>
                    <label for = 'Pago' class = 'form-label'>Pago</label>
                    <input type = 'number' name = 'Pago' id = 'Pago' class = 'form-control' value = '".$row['PagoPendiente']."'>          
                </div>
        </div>        
        ";

        echo $ModalPago;
        exit();
    }

    
?>