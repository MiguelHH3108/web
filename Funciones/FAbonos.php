<?php
    include(__DIR__ .'/../Conexion.php'); 

    if($_POST['funcion'] == 'TablaAbonos'){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $recordsPerPage = isset($_POST['recordsPerPage']) ? intval($_POST['recordsPerPage']) : 50;
        $offset = ($page - 1) * $recordsPerPage;

        $sqlC = "SELECT COUNT(*) AS total FROM Total";
        $resultC = mysqli_query($conn, $sqlC);
        $rowC = mysqli_fetch_assoc($resultC);  
        $totalRecords = $rowC['total'];      

        $sql = "SELECT * FROM Total LIMIT $offset, $recordsPerPage";
        $result = mysqli_query($conn, $sql);

        $tablaAbonos = "";
        while($ab = mysqli_fetch_assoc($result)){
            $tablaAbonos .="
                <tr>
                    <td>".$ab['Nombre']."</td>
                    <td>".$ab['ApellidoP']."</td>
                    <td>".$ab['ApellidoM']."</td>
                    <td>".$ab['PagoPendiente']."</td>
                    <td>".$ab['Abono']."</td>
                    <td>".$ab['Fecha_Abono']."</td>
                    <td>".$ab['Total']."</td>
                    <td><button class = 'btn btn-warning editar' idregistros = '".$ab['IdAbono']."'><i class='bi bi-pencil-square'></i></button>
                    <button class = 'btn btn-danger eliminar' idregistros ='".$ab['IdAbono']."'><i class='bi bi-trash-fill'></i></button>
                    </td>
                </tr>
            ";
        }

        $response = array(
            'html' => $tablaAbonos,
            'totalRecords' => $totalRecords
        );

        echo json_encode($response);
        exit();
    }

    if($_POST['funcion'] == 'BuscarAbono'){
        $terminobusqueda = $_POST['termino'];

        $sqlA = "SELECT * FROM Total
        WHERE Nombre LIKE '%$terminobusqueda%'
        OR ApellidoP LIKE '%$terminobusqueda%'
        OR ApellidoM LIKE '%$terminobusqueda%'
        OR PagoPendiente LIKE '%$terminobusqueda%'
        OR Abono LIKE '%$terminobusqueda%'
        OR Fecha_Abono LIKE '%$terminobusqueda%'
        OR Total LIKE '%$terminobusqueda%'";

        $resultA = mysqli_query($conn, $sqlA);
        $tablaAbonos = "";
        while($ab = mysqli_fetch_assoc($resultA)){
            $tablaAbonos .="
                <tr>
                    <td>".$ab['Nombre']."</td>
                    <td>".$ab['ApellidoP']."</td>
                    <td>".$ab['ApellidoM']."</td>
                    <td>".$ab['PagoPendiente']."</td>
                    <td>".$ab['Abono']."</td>
                    <td>".$ab['Fecha_Abono']."</td>
                    <td>".$ab['Total']."</td>
                    <td><button class = 'btn btn-warning editar' idregistros = '".$ab['IdAbono']."'><i class='bi bi-pencil-square'></i></button>
                    <button class = 'btn btn-danger eliminar' idregistros ='".$ab['IdAbono']."'><i class='bi bi-trash-fill'></i></button>
                    </td>
                </tr>
            ";
        }
        $response = array(
            'html' => $tablaAbonos
        );
        echo json_encode($response);
        exit();
    }

    if($_POST['funcion'] == 'GuardarAbono'){
        $clienteId = $_POST['cliente'];
        $abono = $_POST['Abono'];
        $fechaAbono = $_POST['FechaAbono'];

        $idAbono = $_POST['IdAbono'];
        $validarAbono = "SELECT * FROM  Total WHERE IdAbono = '$idAbono'";
        $resultValidarAbono = mysqli_query($conn, $validarAbono);

        if(mysqli_num_rows($resultValidarAbono) > 0){
            echo "Error: El abono ya existe.";
            exit();
        }else{
        
        $sqlPago = "SELECT IdPago FROM Pago WHERE ClienteFK = '$clienteId'";
        $resultPago = mysqli_query($conn, $sqlPago);
        
        if(mysqli_num_rows($resultPago) == 0) {
            echo "Error: No se encontró un pago asociado a este cliente.";
            exit();
        }
        
        $rowPago = mysqli_fetch_assoc($resultPago);
        $pagoFK = $rowPago['IdPago'];
        
        $sqlPendiente = "SELECT PagoPendiente FROM Pago WHERE IdPago = '$pagoFK'";
        $resultPendiente = mysqli_query($conn, $sqlPendiente);
        $rowPendiente = mysqli_fetch_assoc($resultPendiente);
        
        if($abono > $rowPendiente['PagoPendiente']) {
            echo "Error: El abono no puede ser mayor que el pago pendiente.";
            exit();
        }
        
        $sqlInsert = "INSERT INTO Abonos (Abono, Fecha_Abono, PagoFK) VALUES ('$abono', '$fechaAbono', '$pagoFK')";
        
        if(mysqli_query($conn, $sqlInsert)){
            $sqlUpdate = "UPDATE Pago SET PagoPendiente = PagoPendiente - '$abono' WHERE IdPago = '$pagoFK'";
            mysqli_query($conn, $sqlUpdate);
            
            echo "Abono guardado correctamente.";
        } else {
            echo "Error al guardar el abono: " . mysqli_error($conn);
        }
        exit();
        }
    }
    
    if($_POST['funcion'] == 'ObtenerPagoPendiente'){
        $clienteId = $_POST['clienteId'];
        
        $sql = "SELECT PagoPendiente FROM Pago WHERE ClienteFK = '$clienteId'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo $row['PagoPendiente'];
        } else {
            echo "0";
        }
        exit();
    }

    if($_POST['funcion'] == 'Editar'){
        $id = $_POST['IdAbono']; 
        $clientefk = $_POST['cliente'];
        $abono = $_POST['Abono'];   
        $fechaAbono = $_POST['FechaAbono'];

        $sqlOldAbono = "SELECT Abono, PagoFK FROM Abonos WHERE IdAbono = '$id'";
        $resultOldAbono = mysqli_query($conn, $sqlOldAbono);
        $oldAbono = mysqli_fetch_assoc($resultOldAbono);
        
        $diferencia = $oldAbono['Abono'] - $abono;
        
        $sqlUpdate = "UPDATE Abonos SET Abono = '$abono', Fecha_Abono = '$fechaAbono' WHERE IdAbono = '$id'";
        
        $sqlUpdatePago = "UPDATE Pago SET PagoPendiente = PagoPendiente + $diferencia WHERE IdPago = '{$oldAbono['PagoFK']}'";

        if(mysqli_query($conn, $sqlUpdate) && mysqli_query($conn, $sqlUpdatePago)){
            echo "Abono actualizado correctamente.";
        } else {
            echo "Error al actualizar el abono: " . mysqli_error($conn);
        }
        exit();
    }

    if($_POST['funcion'] == 'Eliminar'){
        $id = $_POST['IdAbono']; 
        
        $sqlAbono = "SELECT Abono, PagoFK FROM Abonos WHERE IdAbono = '$id'";
        $resultAbono = mysqli_query($conn, $sqlAbono);
        
        if(mysqli_num_rows($resultAbono) == 0) {
            echo "Error: No se encontró un abono asociado a este ID.";
            exit();
        }
        
        $rowAbono = mysqli_fetch_assoc($resultAbono);
        $pagoFK = $rowAbono['PagoFK'];
        $abono = $rowAbono['Abono'];
        
        $sqlDelete = "DELETE FROM Abonos WHERE IdAbono = '$id'";
        
        if(mysqli_query($conn, $sqlDelete)){
            $sqlUpdate = "UPDATE Pago SET PagoPendiente = PagoPendiente + $abono WHERE IdPago = '$pagoFK'";
            mysqli_query($conn, $sqlUpdate);
            
            echo "Abono eliminado correctamente.";
        } else {
            echo "Error al eliminar el abono: " . mysqli_error($conn);
        }
        exit();
    }
    
    if($_POST['funcion'] == 'ModalAbono'){
        
        if($_POST['tipo'] == 'Editar'){
            $id = $_POST['id'];
            $sqlE = "SELECT IdAbono,Nombre,ApellidoP,ApellidoM,PagoPendiente,Abono,Fecha_Abono FROM Total WHERE IdAbono = '$id'";

            $result = mysqli_query($conn, $sqlE);
            
            if($result && mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
            } else {
                echo "Error: No se encontró el abono." . mysqli_error($conn);
                exit();
            }
        }

        $ModalAbono = "
        <div class='row'>
            <div class='col-sm-7'>
                <div class='form-group'>
                    <label for='cliente'>Cliente</label>
                    <select class='form-control' id='cliente' name='cliente' required>
                        <option value='".$row['Nombre']."'>Seleccione cliente</option>";
    
                        $clienteselect = "SELECT Cliente.IdClien,cliente.IdClien, persona.Nombre, persona.ApellidoP, persona.ApellidoM 
                                        FROM cliente 
                                        JOIN persona ON cliente.PersonaFK = persona.IdPer";
                        $resulCliSelect = mysqli_query($conn, $clienteselect);
    
                        while ($rowP = mysqli_fetch_assoc($resulCliSelect)) {
                            $nombreCompleto = $rowP['IdClien'] . " " . $rowP['Nombre'] . " " . $rowP['ApellidoP'] . " " . $rowP['ApellidoM'];
                            $ModalAbono .= "<option value='".$rowP['IdClien']."'>".$nombreCompleto."</option>";
                        }
    
        $ModalAbono .= "
                    </select>
                </div>
            </div>

            <div class='col-md-4'>
                <div class='form-group'>
                    <label for='PagoPendiente'><b>Pago pendiente</b></label>
                    <input type='number' id='PagoPendiente' class='form-control' disabled>
                </div>
            </div>
    
            <div class='col-12'>
                <div class='form-group'>
                    <label for='Abono'>Abono</label>
                    <input type='number' name='Abono' id='Abono' class='form-control' value = '".$row['Abono']."'>
                </div>
            </div>

            <div class='col-12'>
                <div class='form-group'>
                    <label for='FechaAbono'>Fecha del abono</label>
                    <input type='date' name='FechaAbono' id='FechaAbono' class='form-control' value = '".$row['Fecha_Abono']."'>
                </div>
            </div>

        </div>";
    
        echo $ModalAbono;
        exit();
    }
?>