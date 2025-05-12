<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivos</title>
    <!-- <link rel="stylesheet" href="Index.css"> -->
    <link rel="icon" href="LOGO.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <button type="button" class="btn btn-success" id = "NuevoArchivo" data-bs-toggle="modal" data-bs-target="#agregararchivos"> Agregar archivos </button>
        </div>

        <?php
            include('Conexion.php'); 
            $sql = "CALL SELECT_ARCHIVOS";
            $resultado = $conn->query($sql);
            $fila = $resultado->fetch_assoc();
            $totalDocumentos = $fila['total'];
        ?>

        <span class="badge bg-primary ms-3">Total documentos: <?php echo $totalDocumentos; ?></span>

        <div>
            <a href="TipoArchivo.php" class="btn btn-success text-white">Tipo archivos</a>
        </div>

        <form class="d-flex" id = "formBuscar">
            <input class="form-control me-2" type="search" id = "inputBuscar" placeholder="Buscar" aria-label="Search">
            <button class="btn btn-outline-success" type="button" id = "btnBuscar" style="background-color: white;">
                <i class="bi bi-search"></i>
            </button>
        </form>
</nav>

<div class="container-fluid">
    <div class="row">
        <div>
            <h1 class="text-center text-primary">
                Archivos
            </h1>
        </div>

        <div class="col-12 text-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope = "col">Titulo</th>
                        <th scope = "col"></th>
                        <th scope = "col">Cliente</th>
                        <th scope = "col"></th>
                        <th scope = "col">Tipo documento</th>
                        <th scope = "col">Descripcion</th>
                        <th scope = "col">Documento</th>
                        <th scope = "col"></th>
                        <th scope = "col">Acciones</th>
                        <th scope = "col"></th>
                    </tr>
                </thead>
                    <tbody id="resultado_archivo">

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

<!-- Modal archivos -->
<div class="modal fade" id="agregararchivos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h3 class="modal-title" id="TituloModalArchivos"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id = "ArchivosModal">

            </div>

            <div class="modal-footer">
                <button type = "button" class="btn btn-primary invisible" id = "EdiArchi">Editar</button>
                <button type="submit" class="btn btn-primary" id="GuardarDoc" name="registrar">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
             </div>
        </div>
    </div>
</div>
<!-- Modal -->

<footer class="bg-body-tertiary text-center text-lg-start">
  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2025 Copyright:
    <p class="text-body"> Miguel Hernández Systems</p>
  </div>
  <!-- Copyright -->
</footer>

<script>
   $(document).ready(function(){
    TablaDocumentos();

        $(document).on('click', '#btnBuscar',function(){
            var terminoBusqueda = $('#inputBuscar').val();
            buscarArchivo(terminoBusqueda);
        });

        function buscarArchivo(terminoBusqueda){
            $.ajax({
                url: './Funciones/FArchivos.php',
                type: 'POST',
                data: {funcion: 'BuscarArchivo', termino: terminoBusqueda},
                success: function(response){
                    var data = JSON.parse(response);
                    $('#resultado_archivo').html(data.html);
                }
            });
        }

        $(document).on('click', "#GuardarDoc", function(){

        if($('#Titulo').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo titulo es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                 $('#Titulo').focus();
                });
                return false;
        }
        
        if($('#cliente').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Seleccione un cliente',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#cliente').focus();
                });
                return false;
        }

        if($('#TipoDoc').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Selecciona el tipo de documento',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#TipoDoc').focus();
                });
                return false;
        }

        if($('#Descripcion').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Agrega una descripción',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#Descripcion').focus();
                });
                return false;
        }

        if($('#Archivo').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Agrega un archivo',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#Archivo').focus();
                });
                return false;
        }

        var formData = new FormData();
        formData.append('funcion', 'GuardarDatos');
        formData.append('Titulo', $('#Titulo').val());
        formData.append('cliente', $('#cliente option:selected').val());
        formData.append('TipoDoc', $('#TipoDoc option:selected').val());
        formData.append('Descripcion', $('#Descripcion').val());
        formData.append('Archivo', $('#Archivo')[0].files[0]);

        $.ajax({
            url: './Funciones/FArchivos.php',
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 

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
                        text: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $("#agregararchivos").find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('agregararchivos'));
                        modal.hide();
                        TablaDocumentos();
                    });

            },

            error: function() {
               Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar el archivo',
                        confirmButtonColor: '#3085d6',
                    });
            }
        });
    });

    $(document).on('click', '#EdiArchi', function(){
        var idDocumentos = $(this).attr('idDocumentos');

        if($('#Titulo').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo titulo es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                 $('#Titulo').focus();
                });
                return false;
        }
        
        if($('#cliente').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Seleccione un cliente',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#cliente').focus();
                });
                return false;
        }

        if($('#TipoDoc').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Selecciona el tipo de documento',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#TipoDoc').focus();
                });
                return false;
        }

        if($('#Descripcion').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Agrega una descripción',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#Descripcion').focus();
                });
                return false;
        }

        if($('#Archivo').val()==""){
            Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Agrega un archivo',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#Archivo').focus();
                });
                return false;
        }

        var formData = new FormData();
        formData.append('funcion', 'Editar');
        formData.append('idDocumentos', idDocumentos); 
        formData.append('Titulo', $('#Titulo').val());
        formData.append('cliente', $('#cliente option:selected').val());
        formData.append('TipoDoc', $('#TipoDoc option:selected').val());
        formData.append('Descripcion', $('#Descripcion').val());
        
        if($('#Archivo')[0].files[0]){
            formData.append('Archivo', $('#Archivo')[0].files[0]);
        }

        $.ajax({
            url: './Funciones/FArchivos.php',
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            success: function(response){
                if(response.includes("Error")){
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
                        text: 'Archivo actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $("#agregararchivos").find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('agregararchivos'));
                        modal.hide();
                        TablaDocumentos();
                    });
            },
            error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al actualizar el archivo',
                        confirmButtonColor: '#3085d6',
                    });
                }
        });
    });

    $(document).on('click', '#NuevoArchivo', function() {
            ModalDoc("Nuevo", 0);
            $('#agregararchivos').modal('show'); 
            $("#EdiArchi").hide();
            $("#TituloModalArchivos").text("Guardar archivo");
    });

    $(document).on('click','.editar',function(){
        var idDocumentos = $(this).attr('idDocumentos');
        ModalDoc("Editar", idDocumentos);
        $("#GuardarDoc").hide();
        $('#agregararchivos').modal('show'); 
        $("#EdiArchi").show().removeClass("invisible").attr("idDocumentos", idDocumentos);
        $("#TituloModalArchivos").text("Editar archivo");
    });

    $(document).on("click", ".eliminar", function(){
         var idDocumentos = $(this).attr('idDocumentos');
            
        Swal.fire({
            title: '¿Eliminar archivo?',
            text: '¿Quires eliminar este archivo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) =>{
            if(result.isConfirmed){
                $.ajax({
                    url: './Funciones/FArchivos.php',
                    type: 'POST',
                    data: {
                        funcion: 'Eliminar', 
                        IdDoc: idDocumentos
                    },

                    success: function(response){
                        Swal.fire(
                            'Eliminado',
                            'Archivo eliminado correctamente',
                            'success'
                        ).then(() =>{ 
                            TablaDocumentos();
                        });
                    },
                });
            }
        });
    });

    function ModalDoc(tipo, id){
            $.ajax({
                url: './Funciones/FArchivos.php',
                type: 'POST',
                data: {funcion: 'ModalArchivos', tipo: tipo, id: id},
                success: function(response){
                    console.log(response);
                    $('#ArchivosModal').html(response);
                }
            });
    }

    var currentPage = 1;
    var recordsPerPage = 50;

    function TablaDocumentos(page){
        $.ajax({
            url: './Funciones/FArchivos.php',
            type: 'POST',
            data: {funcion: 'TablaDocumentos', page: page, recordsPerPage: recordsPerPage},
            success: function(response){
                var data = JSON.parse(response);
                $('#resultado_archivo').html(data.html);
                generatePagination(data.totalRecords, recordsPerPage, page);
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

    window.changePage = function(page){
        currentPage = page;
        TablaDocumentos(page);
    }

    TablaDocumentos(currentPage);

    $('#resultado_archivo').on('click','.btn-danger',function(){
        $.ajax({
            url: './Funciones/FArchivos.php',
            type: 'POST',
            data: {
                funcion: 'Eliminar', 
                IdDoc: $(this).attr('idDocumentos')
            },
            
            success: function(response){
                TablaDocumentos();
            }
        });
    });
});
</script>
</body>
</html>