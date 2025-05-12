<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de documento</title>
    <link rel="stylesheet" href="Index.css">
    <link rel="icon" href="LOGO.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="js/jquery-3.7.1.min"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light" style="background-color: #5e5e5e;">
    <div class="container-fluid">
        <a class="navbar-brand" href="Admin.php">
        <img src="LOGO.jpg" alt="" width="60" height="40" class="d-inline-block align-text-top">
        Inicio
        </a>

        <div class="d-inline-block align-text-top">
            <span class="d-inline-block align-text-top" style="color: white;">
                Usuario: <?php echo htmlspecialchars($_SESSION['name']); ?>
            </span>
        </div>

        <div>
            <a href="Archivos.php" class="btn btn-success text-white">Agregar archivos</a>
        </div>

        <?php
            include('Conexion.php'); 
            $sql = "CALL TOTAL_TDOC";
            $resultado = $conn->query($sql);
            $fila = $resultado->fetch_assoc();
            $totalTipoDocumentos = $fila['total'];
        ?>

        <span class="badge bg-primary ms-3">Total tipo documentos: <?php echo $totalTipoDocumentos; ?></span>

        <div>
            <button type="button" class="btn btn-success" id = "NuevoTD" data-bs-toggle="modal" data-bs-target="#tipodocumento"> Agregar tipo documento </button>
        </div>

        <form class="d-flex" id="formBuscar">
            <input class="form-control me-2" type="search" id="inputBuscar" placeholder="Buscar" aria-label="Search" >
            <button class="btn btn-outline-success" type="button" id="btnBuscar" style="background-color: white;">
                <i class="bi bi-search"></i>
            </button>
        </form>

    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div>
            <h1 class="text-center text-primary">
               Tipo Archivos
            </h1>
        </div>
        <div class="col-12 text-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Tipo documento</th>
                        <th scope="col">Acciones</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id = "resultado_tipo_doc">

                </tbody>
            </table>
            <div class="row">
                    <div class="col-12 text-center">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center" id="pagination">
                                
                            </ul>
                        </nav>
                    </div>
            </div>
        </div>
    </div>
</div>    

<!-- Modal tipo documento -->
<div class="modal fade" id="tipodocumento" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h3 class="modal-title" id="TituloTD"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id = "ModalBodyTD">

                  
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary invisible" id="Edita_TD">Editar tipo documento</button>
                <button type="submit" class="btn btn-primary" id="GuardarTipoDoc">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<!-- Script tipo documento -->
<script>
$(document).ready(function(){
    TablaTipoDoc();

    $(document).on('click', '#btnBuscar', function() {
        var terminoBusqueda = $('#inputBuscar').val(); 
        buscarClientes(terminoBusqueda);
    });

    function buscarClientes(terminoBusqueda) {
        $.ajax({
            url: './Funciones/FTipoArchivo.php',
            type: 'POST',
            data: { funcion: 'BuscarTD', termino: terminoBusqueda },
            success: function(response) {
                var data = JSON.parse(response);
                $('#resultado_tipo_doc').html(data.html); 
            }
        });
    }

    $(document).on('click','#GuardarTipoDoc',function(){

        if($("#TipoDoc").val() == ""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo nombre es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#TipoDoc").focus();
                });
            return false;
        }

        $.ajax({
            url: './Funciones/FTipoArchivo.php',
            type: 'POST',
            data: {funcion: 'Guardar', 
            nombre: $("#TipoDoc").val()
            },

            success: function(response){
                if(response.includes("ya existe")){
                    Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response,
                            confirmButtonColor: '#3085d6',
                        });
                    return false;
                }

                Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#ModalBodyTD').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('tipodocumento'));
                        modal.hide();
                        TablaTipoDoc();
                    });
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar el tipo de documento.',
                        confirmButtonColor: '#3085d6',
                    });
                }
            
        });
    });

    $(document).on('click', "#NuevoTD", function(){
        modaltipo("Guardar", 0);
        $("#GuardarTipoDoc").show();
        $("#Edita_TD").hide();
        $("#TituloTD").text("Nuevo tipo documento");
    });

    $(document).on('click', "#Edita_TD", function(){
        var idregistros = $(this).attr('idregistros');
        var nuevoNombre = $("#TipoDoc").val();

        if(nuevoNombre == ""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo nombre es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
            $("#TipoDoc").focus();
                });
            return false;
        }

        $.ajax({
                url: './Funciones/FTipoArchivo.php',
                type: 'POST',
                data: {funcion: 'Editar', 
                    id: idregistros, 
                    nombre: nuevoNombre
                },
                success: function(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#ModalBodyTD').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('tipodocumento'));
                        modal.hide();
                        TablaTipoDoc();
                    });
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar el tipo de documento.',
                        confirmButtonColor: '#3085d6',
                    });
                }    
                
            });
    });

    $(document).on("click",".editar",function(){
            var idregistros = $(this).attr('idregistros');
            modaltipo("Editar",idregistros);
            $("#GuardarTipoDoc").hide();
            $("#Edita_TD").show().removeClass('invisible').attr('idregistros',idregistros);
            $("#TituloTD").text("Editar tipo documento");
            $('#tipodocumento').modal('show');
    });

    $(document).on('click', '.eliminar', function(){
        var idregistros = $(this).attr('idregistros');
        
        Swal.fire({
                title: '¿Eliminar?',
                text: "¿Quieres borrar el tipo documento?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './Funciones/FTipoArchivo.php',
                        type: 'POST',
                        data: {funcion: 'Eliminar', IdTD: idregistros},
                        success: function(response) {
                            Swal.fire(
                                'Eliminado!',
                                'El tipo documento ha sido eliminado.',
                                'success'
                            ).then(() => {
                                TablaTipoDoc();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar el tipo de documento.',
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    });
                }
            });
    });

    function modaltipo(tipo, id) {
        $.ajax({
            url: './Funciones/FTipoArchivo.php',
            type: 'POST',
            data: {funcion: "Modal", tipo: tipo, id: id},
            success: function(response){
                console.log(response);
                $('#ModalBodyTD').html(response);
            }
        });
    }

    var currentPage = 1;
    var recordsPerPage = 50; 

    function TablaTipoDoc(page){
        $.ajax({
            url: './Funciones/FTipoArchivo.php',
            type: 'POST',
            data: {funcion: 'TablaTipoDoc', page: page, recordsPerPage: recordsPerPage},
            success: function(response){
                var data = JSON.parse(response);
                $('#resultado_tipo_doc').html(data.html);
                generatePagination(data.totalRecords, recordsPerPage ,page);
            }
        });
    }

    function generatePagination(totalRecords, recordsPerPage, currentPage){
        var totalPages = Math.ceil(totalRecords / recordsPerPage);
        var paginationHtml = '';

        if (totalPages){
            paginationHtml += '<li class="page-item ' + (currentPage == 1 ? 'disabled' : '') + '"><a class="page-link" href="#" onclick="changePage(' + (currentPage - 1) + ')">Anterior</a></li>';

            for (var i = 1; i <= totalPages; i++) {
                paginationHtml += '<li class="page-item ' + (currentPage == i ? 'active' : '') + '"><a class="page-link" href="#" onclick="changePage(' + i + ')">' + i + '</a></li>';
            }

            paginationHtml += '<li class="page-item ' + (currentPage == totalPages ? 'disabled' : '') + '"><a class="page-link" href="#" onclick="changePage(' + (currentPage + 1) + ')">Siguiente</a></li>';
        }

        $("#pagination").html(paginationHtml);
    }

    window.changePage = function(page) {
        currentPage = page;
        TablaTipoDoc(page);
    };

    TablaTipoDoc(currentPage);

    $("#resultado_tipo_doc").on('click', '.btn-danger', function(){
        $.ajax({
            url: './Funciones/FTipoArchivo.php',
            type: 'POST',
            data: {funcion: 'Eliminar', id: $(this).attr('id')},
            success: function(response){
                TablaTipoDoc();
            }
        });
    });
});
</script>
</body>
</html>