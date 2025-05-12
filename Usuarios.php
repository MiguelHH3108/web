<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="Index.css">
    <link rel="icon" href="LOGO.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

        <div class="d-flex align-items-center">
            <span class="d-inline-block align-text-top" style="color: white;">
                Usuario: <?php echo htmlspecialchars($_SESSION['name']); ?>
            </span>
        </div>

        <div>
            <button type="button" class="btn btn-success" id = "NuevoUser" data-bs-toggle = "modal" data-bs-target = "#NuevosUsuarios"> Agregar usuarios</button>
        </div>

        <?php
            include('Conexion.php'); 
            $sql = "CALL TOTAL_USUARIOS";
            $resultado = $conn->query($sql);
            $fila = $resultado->fetch_assoc();
            $totalUsuarios = $fila['total'];
        ?>

        <span class="badge bg-primary ms-3">Total usuarios: <?php echo $totalUsuarios; ?></span>

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
            Usuarios
        </h1>
        </div>

        <div class="col-12 text-center">
            <table class="table">
                <thead>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Nombre</th>
                    <th>Apellido paterno</th>
                    <th>Apellido materno</th>
                    <th>Telefono</th>
                    <th>Ciudad</th>
                    <th>Colonia</th>
                    <th>Calle</th>
                    <th>Num_ext</th>
                    <th>Num_int</th>
                </thead>

                <tbody id="resultadoUsuarios">

                </tbody>

            </table>
        </div>

    </div>
    <div class="row">
            <div class="col-12 text-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Los botones de paginación se generarán dinámicamente aquí -->
                    </ul>
                </nav>
            </div>
        </div>
</div>

<!-- Modal para crear usuarios -->
 <div class="modal" id="NuevosUsuarios" tabidenx = "-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalU"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="modal-bodyU">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary invisible" id="EditaUsuarios">Editar usuarios</button>
                <button type="button" class="btn btn-primary" id="GuardaUsuario">Guardar usuario</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> 
            </div>

        </div>
    </div>
 </div>

<script>
    $(document).ready(function(){
        TablaUsuarios();

        $(document).on('click','#btnBuscar', function(){
            var terminoBusqueda = $('#inputBuscar').val();
            buscarUsuario(terminoBusqueda);
        });

        function buscarUsuario(terminoBusqueda){
            $.ajax({
                url: './Funciones/FUsuarios.php',
                type: 'POST',
                data: {funcion: 'BuscarUsuario', termino: terminoBusqueda},
                success: function(response){
                    var data = JSON.parse(response);
                    $("#resultadoUsuarios").html(data.html);
                }
            });
        }

        $(document).on('click','#GuardaUsuario',function(){
            
            if($('#Usuario').val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre de usuario es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                $('#Usuario').focus();
                });
                return false;
            }

            if($('#Contraseña').val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La contraseña es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                $('#Contraseña').focus();
                });
                return false;
            }
            
            if($("#Nombre").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo nombre es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Nombre").focus();
                });
                return false;
            }

            if($("#ApellidoP").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo apellido paterno es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#ApellidoP").focus();
                });
                return false;
            }

            if($("#ApellidoM").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo apellido materno es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#ApellidoM").focus();
                });
                return false;
            }

            if($("#Telefono").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El telefóno es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Telefono").focus();
                });
                return false;
            }

            if($("#Ciudad").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La ciudad es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Ciudad").focus();
                });
                return false;
            }

            if($("#Colonia").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La colonia es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Colonia").focus();
                });
                return false;
            }

            if($("#Calle").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La calle es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Calle").focus();
                });
                return false;
            }

            if($("#Num_ext").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El numero exterior es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Num_ext").focus();
                });
                return false;
            }

            if($("#Num_int").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El numero interior es obligatorio, puede agregar un 0 si no tiene numero exterior',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Num_int").focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FUsuarios.php',
                type: 'POST',
                data: {funcion: 'Guardar',
                    usuario: $('#Usuario').val(),
                    contraseña: $('#Contraseña').val(),
                    nombre: $("#Nombre").val(),
                    ApellidoP: $("#ApellidoP").val(),
                    ApellidoM: $("#ApellidoM").val(),
                    Telefono: $("#Telefono").val(),
                    Ciudad: $("#Ciudad").val(),
                    Colonia: $("#Colonia").val(),
                    Calle: $("#Calle").val(),
                    Num_ext: $("#Num_ext").val(),
                    Num_int: $("#Num_int").val()
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
                        text: 'Usuario guardado correctamente.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-bodyU').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('NuevosUsuarios'));
                        modal.hide();
                        TablaUsuarios();
                    });
                },

                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar el usuario.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        });

        $(document).on('click','#EditaUsuarios',function(){
            var idregistros = $(this).attr('idregistros');

            if($('#Usuario').val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre de usuario es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                $('#Usuario').focus();
                });
                return false;
            }

            if($('#Contraseña').val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La contraseña es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                $('#Contraseña').focus();
                });
                return false;
            }
            
            if($("#Nombre").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo nombre es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Nombre").focus();
                });
                return false;
            }

            if($("#ApellidoP").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo apellido paterno es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#ApellidoP").focus();
                });
                return false;
            }

            if($("#ApellidoM").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo apellido materno es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#ApellidoM").focus();
                });
                return false;
            }

            if($("#Telefono").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El telefóno es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Telefono").focus();
                });
                return false;
            }

            if($("#Ciudad").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La ciudad es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Ciudad").focus();
                });
                return false;
            }

            if($("#Colonia").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La colonia es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Colonia").focus();
                });
                return false;
            }

            if($("#Calle").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La calle es obligatoria',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Calle").focus();
                });
                return false;
            }

            if($("#Num_ext").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El numero exterior es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Num_ext").focus();
                });
                return false;
            }

            if($("#Num_int").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El numero interior es obligatorio, puede agregar un 0 si no tiene numero interior',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Num_int").focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FUsuarios.php',
                type: 'POST',
                data:{funcion: 'Editar',
                    id: idregistros,
                    usuario: $('#Usuario').val(),
                    contraseña: $('#Contraseña').val(),
                    nombre: $("#Nombre").val(),
                    ApellidoP: $("#ApellidoP").val(),
                    ApellidoM: $("#ApellidoM").val(),
                    Telefono: $("#Telefono").val(),
                    Ciudad: $("#Ciudad").val(),
                    Colonia: $("#Colonia").val(),
                    Calle: $("#Calle").val(),
                    Num_ext: $("#Num_ext").val(),
                    Num_int: $("#Num_int").val(),
                },

                success: function(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: 'Usuario editado correctamente.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-bodyU').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('NuevosUsuarios'));
                        modal.hide();
                        TablaUsuarios();
                    });
                },

                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al editar el usuario.',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });
        });

        $(document).on('click','#NuevoUser',function(){
            Modal("Guardar",0);
            $("#GuardaUsuario").show();
            $("#EditaUsuarios").hide();
            $("#TituloModalU").text("Nuevo usuario");
        });

        $(document).on('click','.editar',function(){
            var idregistros = $(this).attr('idregistros');
            Modal("Editar",idregistros);
            $("#GuardaUsuario").hide();
            $("#EditaUsuarios").show().removeClass('invisible').attr('idregistros',idregistros);
            $("#TituloModalU").text("Editar usuario");
            $("#NuevosUsuarios").modal("show");
        });

        $(document).on('click','.eliminar',function(){
            var idregistros = $(this).attr('idregistros');

            Swal.fire({
                title: '¿Eliminar usuario?',
                text: '¿Quires eliminar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        url: './Funciones/FUsuarios.php',
                        type: 'POST',
                        data: {
                            funcion: 'Eliminar',
                            IdU: idregistros
                        },

                        success: function(response){
                            Swal.fire(
                                'Eliminado',
                                'Usuario eliminado correctamente.',
                                'success'
                            ).then(() => {
                                TablaUsuarios();
                            });
                        },

                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar el cliente',
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    });
                }
            });
        });

        function Modal(tipo,id){
            $.ajax({
                url: './Funciones/FUsuarios.php',
                type: 'POST',
                data: {funcion: 'Modal', tipo: tipo, id: id},
                success: function(response){
                    console.log(response);
                    $("#modal-bodyU").html(response);
                }
            });
        }

        var currentPage = 1;
        var recordsPerPage = 50;

        function TablaUsuarios(page){
            $.ajax({
                url: './Funciones/FUsuarios.php',
                type: 'POST',
                data: {funcion: 'TablaUsuarios', page: page, recordsPerPage: recordsPerPage},
                success: function(response){
                    var data = JSON.parse(response);
                    $("#resultadoUsuarios").html(data.html);
                    generatePagination(data.totalRecords, recordsPerPage ,page);
                }
            });
        }

        function generatePagination(totalRecords, recordsPerPage, currentPage) {
            var totalPages = Math.ceil(totalRecords / recordsPerPage);
            var paginationHtml = '';

            if (totalPages > 1) {
                paginationHtml += '<li class="page-item ' + (currentPage == 1 ? 'disabled' : '') + '"><a class="page-link" href="#" onclick="changePage(' + (currentPage - 1) + ')">Anterior</a></li>';

                for (var i = 1; i <= totalPages; i++) {
                    paginationHtml += '<li class="page-item ' + (currentPage == i ? 'active' : '') + '"><a class="page-link" href="#" onclick="changePage(' + i + ')">' + i + '</a></li>';
                }

                paginationHtml += '<li class="page-item ' + (currentPage == totalPages ? 'disabled' : '') + '"><a class="page-link" href="#" onclick="changePage(' + (currentPage + 1) + ')">Siguiente</a></li>';
            }

            $('#pagination').html(paginationHtml);
        }

        window.changePage = function(page) {
            currentPage = page;
            TablaUsuarios(page);
        }

        TablaUsuarios(currentPage);

        $('#resultadoUsuarios').on('click','.btn-danger',function(){
            $.ajax({
                url: './Funciones/FUsuarios.php',
                type: 'POST',
                data: {funcion: 'Eliminar', id: $(this).attr('id')},
                success: function(response){
                    TablaUsuarios();
                }
            });
        });

    });
</script>
</body>
</html>