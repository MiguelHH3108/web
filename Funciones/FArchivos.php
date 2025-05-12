<?php
include(__DIR__ .'/../Conexion.php');

    if($_POST['funcion'] == "TablaDocumentos"){
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $recordsPerPage = isset($_POST['recordsPerPage']) ? intval($_POST['recordsPerPage']) : 50;
        $offset = ($page - 1) * $recordsPerPage;

        $sqlCount = "SELECT COUNT(*) AS total FROM Archivos";
        $resultCount = mysqli_query($conn,$sqlCount);
        $totalRecords = mysqli_fetch_assoc($resultCount)['total'];

        $sql = "SELECT * FROM Archivos ORDER BY IdDoc LIMIT $offset, $recordsPerPage";
        $resultD = mysqli_query($conn,$sql);

        $tablaDoc = "";

        while ($docu = mysqli_fetch_assoc($resultD)){
            $tablaDoc .= "<tr>
                             <td>".$docu['Titulo']."</td>
                             <td>".$docu['Nombre']."</td>
                             <td>".$docu['ApellidoP']."</td>
                             <td>".$docu['ApellidoM']."</td>
                             <td>".$docu['Tipo Documento']."</td>
                             <td>".$docu['Descripcion']."</td>
                             <td>".$docu['Documento']."</td>
                             <td>
                                <a target='_blank' href='Funciones/Documentos/".$docu['Documento']."' class='btn btn-primary'>
                                    <i class='bi bi-download'></i>
                                </a>
                            </td>
                             <td><button class='btn btn-warning editar' idDocumentos='".$docu['IdDoc']."'><i class='bi bi-pencil-square'></i></button></td>
                             <td><button class='btn btn-danger eliminar' idDocumentos='".$docu['IdDoc']."'><i class='bi bi-trash-fill'></i></button></td>
                         </tr>";
        }

        $response = array(
            'html' => $tablaDoc,
            'totalRecords' => $totalRecords
        );

        echo json_encode($response);
        exit();

    }  

    if($_POST['funcion'] == 'BuscarArchivo'){
        $terminoBusqueda = $_POST['termino'];

        $sql = "SELECT * FROM Archivos  
        WHERE Titulo LIKE '%$terminoBusqueda%' 
        OR Nombre LIKE '%$terminoBusqueda%' 
        OR ApellidoP LIKE '%$terminoBusqueda%' 
        OR ApellidoM LIKE '%$terminoBusqueda%' 
        OR Nombre LIKE '%$terminoBusqueda%'
        OR Descripcion LIKE '%$terminoBusqueda%'
        OR Documento LIKE '%$terminoBusqueda%'";

        $result = mysqli_query($conn, $sql);

        $tablaDoc = "";
        while ($docu = mysqli_fetch_assoc($result)){
            $tablaDoc .= "<tr>
                             <td>".$docu['Titulo']."</td>
                             <td>".$docu['Nombre']."</td>
                             <td>".$docu['ApellidoP']."</td>
                             <td>".$docu['ApellidoM']."</td>
                             <td>".$docu['Tipo Documento']."</td>
                             <td>".$docu['Descripcion']."</td>
                             <td>".$docu['Documento']."</td>
                             <td>
                                <a target='_blank' href='Funciones/Documentos/".$docu['Documento']."' class='btn btn-primary'>
                                    <i class='bi bi-download'></i>
                                </a>
                            </td>
                             <td><button class='btn btn-warning editar' idDocumentos='".$docu['IdDoc']."'>Editar</button></td>
                             <td><button class='btn btn-danger eliminar' idDocumentos='".$docu['IdDoc']."'>Eliminar</button></td>
                         </tr>";
        }
        $response = array(
            'html' => $tablaDoc,
        );

        echo json_encode($response);
        exit();
    }

    if ($_POST['funcion'] == 'GuardarDatos') {
        if(isset($_FILES['Archivo'])){
            extract($_POST);
            $Titulo = mysqli_real_escape_string($conn, $_POST['Titulo']);
            $ClienteFK = mysqli_real_escape_string($conn, $_POST['cliente']);
            $TipoDoc = mysqli_real_escape_string($conn, $_POST['TipoDoc']);
            $Descripcion = mysqli_real_escape_string($conn, $_POST['Descripcion']);
        
            $sqlValidacion = "SELECT * FROM documentos WHERE Titulo = '$Titulo'";
            $result = mysqli_query($conn, $sqlValidacion);
        
            if (mysqli_num_rows($result) > 0) {
                echo "Este documento ya existe";
                exit();
            }else{
                $carpeta = "Documentos/";
                $nombre_archivo = basename($_FILES['Archivo']['name']);
                $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    
                if($extension == 'pdf' || $extension == 'doc' || $extension == 'docx'){
    
                    if(move_uploaded_file($_FILES['Archivo']['tmp_name'], $carpeta . $nombre_archivo)){
                        $sqlDoc = "INSERT INTO Documentos(Titulo, ClienteFK, TipoDocumentoFK, Descripcion, Documento) VALUES ('$Titulo', '$ClienteFK', '$TipoDoc', '$Descripcion', '$nombre_archivo')";
    
                        $resulDoc = mysqli_query($conn, $sqlDoc);
    
                        if($resulDoc){
                            echo "Insertado correctamente";
                        }else{
                            echo "Error al guardar el documento: " . mysqli_error($conn);
                        }
    
                    }else{
                        echo "Error al subir el archivo";
                    }
    
                }else{
                    echo "Solo se permiten archivos PDF y WORD";
                }
            }
        }
    }

    if($_POST['funcion'] == 'Editar'){
        $idDocumentos = isset($_POST['idDocumentos']) ? $_POST['idDocumentos'] : null;
        
        if(!$idDocumentos) {
            echo "Error: ID de documento no proporcionado";
            exit();
        }
    
        $Titulo = mysqli_real_escape_string($conn, $_POST['Titulo']);
        $ClienteFK = mysqli_real_escape_string($conn, $_POST['cliente']);
        $TipoDoc = mysqli_real_escape_string($conn, $_POST['TipoDoc']);
        $Descripcion = mysqli_real_escape_string($conn, $_POST['Descripcion']);
    
        if(isset($_FILES['Archivo']) && $_FILES['Archivo']['error'] == UPLOAD_ERR_OK){
            $carpeta = "Documentos/";
            $nombre_archivo = basename($_FILES['Archivo']['name']);
            $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    
            if($extension == 'pdf' || $extension == 'doc' || $extension == 'docx'){
                if(move_uploaded_file($_FILES['Archivo']['tmp_name'], $carpeta . $nombre_archivo)){
                    
                    $sqlDoc = "UPDATE Documentos SET 
                              Titulo='$Titulo', 
                              ClienteFK='$ClienteFK', 
                              TipoDocumentoFK='$TipoDoc', 
                              Descripcion='$Descripcion', 
                              Documento='$nombre_archivo' 
                              WHERE IdDoc='$idDocumentos'";
                }else{
                    echo "Error al subir el archivo";
                    exit();
                }
            }else{
                echo "Solo se permiten archivos PDF y WORD";
                exit();
            }
        }else{
            $sqlDoc = "UPDATE Documentos SET 
                      Titulo='$Titulo', 
                      ClienteFK='$ClienteFK', 
                      TipoDocumentoFK='$TipoDoc', 
                      Descripcion='$Descripcion' 
                      WHERE IdDoc='$idDocumentos'";
        }
    
        $resulDoc = mysqli_query($conn, $sqlDoc);
    
        if($resulDoc){
            echo "Actualizado correctamente";
        }else{
            echo "Error al actualizar el documento: " . mysqli_error($conn);
        }
        exit();
    }

    if($_POST['funcion'] == 'Eliminar'){
        $id = $_POST['IdDoc'];
        $sqlD = "DELETE FROM Documentos WHERE IdDoc='$id'";
        
        if($conn -> query($sqlD) === TRUE){
            echo "Eliminado";
        }else{
            echo "Error al eliminar el documento: " . mysqli_error($conn);
        }
    }

    if($_POST['funcion'] == 'ModalArchivos'){

        if($_POST['tipo']=='Editar'){

        $id = $_POST['id'];

        $sql = "SELECT * FROM Archivos WHERE IdDoc = '$id'";

        $resultEdit = mysqli_query($conn,$sql);

        if($resultEdit && mysqli_num_rows($resultEdit) > 0) {
            $row = mysqli_fetch_assoc($resultEdit);
        } else {
            echo "Error: No se encontraron datos.";
            exit();
        }

        }

        $ModalDoc = "
        <div class='row'>
            <div class='col-sm-5'>
                <div class='mb-3'>
                    <label for='Titulo' class='form-label'>Titulo</label>
                    <input type='text' id='Titulo' name='Titulo' class='form-control' value='".$row['Titulo']."' required>
                </div>
            </div>

            <div class='col-sm-7'>
                <div class='form-group'>
                    <label for='cliente'>Cliente</label>
                    <select class='form-control' id='cliente' name='cliente' required>
                        <option value='".$row['cliente']."'>Seleccione cliente</option>";

                        $clienteselect = "SELECT cliente.IdClien, persona.Nombre, persona.ApellidoP, persona.ApellidoM 
                                        FROM cliente 
                                        JOIN persona ON cliente.PersonaFK = persona.IdPer";
                        $resulCliSelect = mysqli_query($conn, $clienteselect);

                        while ($rowc = mysqli_fetch_assoc($resulCliSelect)) {
                            $nombreCompleto = $rowc['IdClien']."". $rowc['Nombre'] . " " . $rowc['ApellidoP'] . " " . $rowc['ApellidoM'];
                            $ModalDoc .= "<option value='".$rowc['IdClien']."' $seleccionado>".$nombreCompleto."</option>";
                        }

                    $ModalDoc .= "
                    </select>
                </div>
            </div>

            <div class='col-sm-8'>
                <div class='form-group'>
                    <label for='TipoDoc'>Tipo documento</label>
                    <select class='form-control' id='TipoDoc' name='TipoDocumentoFK'>
                        <option value=''>Seleccione tipo de documento</option>";

                        $tipodocselect = "SELECT IdTD, Nombre FROM TipoDocumentos ORDER BY IdTD";
                        $resulTdselect = mysqli_query($conn, $tipodocselect);

                        while ($rowtd = mysqli_fetch_assoc($resulTdselect)) {
                            $ModalDoc .= "<option value='".$rowtd['IdTD']."'".($rowtd['Nombre'] == $resulTdselect ? 'selected' : '').">".$rowtd['Nombre']."</option>";
                        }

    $ModalDoc .= "
                    </select>
                </div>
            </div>

            <div class='col-12'>
                <label for='Descripcion' class='form-label'>Descripcion</label>
                <input type='text' name='Descripcion' id='Descripcion' class='form-control' value='".$row['Descripcion']."'>
            </div>
            
            <div class='col-12'>
                <label for='Archivo' class='form-label'>Archivo (WORD & PDF)</label>
                <input type='file' name='archivo' id='Archivo' class='form-control'>
            </div>
        </div>
    </div>";

    echo $ModalDoc;
    exit();
    }
?>