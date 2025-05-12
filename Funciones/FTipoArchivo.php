<?php
include(__DIR__ . '/../Conexion.php');

if ($_POST['funcion'] == "TablaTipoDoc") {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $recordsPerPage = isset($_POST['recordsPerPage']) ? intval($_POST['recordsPerPage']) : 50;
    $offset = ($page - 1) * $recordsPerPage;

    $sqlCount = "SELECT COUNT(*) AS total FROM TipoDocumentos";
    $resultCount = mysqli_query($conn, $sqlCount);
    $totalRecords = mysqli_fetch_assoc($resultCount)['total'];

    $sql = "SELECT * FROM TipoDocumentos LIMIT $offset, $recordsPerPage";
    $resultD = mysqli_query($conn, $sql);

    $tablaTipoDoc = "";

    while ($docu = mysqli_fetch_assoc($resultD)) {
        $tablaTipoDoc .= "<tr>
                            <td>" . $docu['Nombre'] . "</td>
                            <td><button class='btn btn-warning editar' idregistros='" . $docu['IdTD'] . "'><i class='bi bi-pencil-square'></i></button></td>
                            <td><button class='btn btn-danger eliminar' idregistros='" . $docu['IdTD'] . "'><i class='bi bi-trash-fill'></i></button></td>
                        </tr>";
    }

    $response = array(
        'html' => $tablaTipoDoc,
        'totalRecords' => $totalRecords
    );

    echo json_encode($response);
    exit();
}

if ($_POST['funcion'] == 'BuscarTD') {
    $terminoBusqueda = $_POST['termino'];

    $sql = "SELECT * FROM TipoDocumentos WHERE Nombre LIKE  '%$terminoBusqueda%'";

    $result = mysqli_query($conn, $sql);
    
    $tablaTipoDoc = "";

    while ($docu = mysqli_fetch_assoc($result)) {
        $tablaTipoDoc .= "<tr>
                            <td>" . $docu['Nombre'] . "</td>
                            <td><button class='btn btn-warning editar' idregistros='" . $docu['IdTD'] . "'>Editar</button></td>
                            <td><button class='btn btn-danger eliminar' idregistros='" . $docu['IdTD'] . "'>Eliminar</button></td>
                        </tr>";
    }

    $response = array(
        'html' => $tablaTipoDoc
    );

    echo json_encode($response);
    exit();
}

if ($_POST['funcion'] == 'Guardar') {
    $TipDoc = $_POST['nombre'];

    $sqlValidacion = "SELECT * FROM TipoDocumentos WHERE Nombre = '$TipDoc'";
    $result = mysqli_query($conn, $sqlValidacion);

    if (mysqli_num_rows($result) > 0) {
        echo "El tipo de documento '$TipDoc' ya existe";
    } else {
        $sqlTipoDoc = "INSERT INTO TipoDocumentos (Nombre) VALUES ('$TipDoc')";
        if (mysqli_query($conn, $sqlTipoDoc)) {
            echo "Tipo de documento '$TipDoc' guardado";
        } else {
            echo "Error al guardar el tipo de documento";
        }
    }
}

if($_POST['funcion'] == 'Editar'){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    $sqlUpdate = "UPDATE TipoDocumentos SET Nombre = '$nombre' WHERE IdTD = '$id'";

    if(mysqli_query($conn, $sqlUpdate) === TRUE){
        echo "Actualizado";
    } else {
        echo "Error: ".mysqli_error($conn);
    }
}

if ($_POST['funcion'] == 'Eliminar') {
    $idTipoDoc = $_POST['IdTD'];  

    $sqlDelete = "DELETE FROM TipoDocumentos WHERE IdTD = '$idTipoDoc'";

    if(mysqli_query($conn, $sqlDelete) === TRUE){
        echo "Eliminado";
    } else {
        echo "Error: ".mysqli_error($conn);
    }
}

if ($_POST['funcion'] == 'Modal') {

    if ($_POST['tipo'] == 'Editar'){
        $id = $_POST['id'];
        $sql = "SELECT Nombre FROM TipoDocumentos WHERE IdTD = '$id'";
        $resultedi = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($resultedi);

        $nombre = isset($row['Nombre']) ? $row['Nombre'] : "";
    }

    echo "
        <div class='row'>
            <div class='col-sm-12'>
                <div class='mb-3'>
                    <label for='TipoDoc' class='form-label'>Nombre tipo de documento</label>
                    <input type='text' id='TipoDoc' name='TipoDoc' class='form-control' value='".$row['Nombre']."'>
                </div>
            </div>
        </div>
    ";
    exit();
}
?>