<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="icon" href="LOGO.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/jquery-3.7.1.min"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
<nav class="navbar navbar-light" style="background-color: #5e5e5e;">
  <!-- Container wrapper -->
  <div class="container">
    <!-- Navbar brand -->
    <a class="navbar-brand me-2" href="Admin.php">
      <img
        src="Logo.jpg" width="60" height="40" class="d-inline-block align-text-top"/>
        Inicio
    </a>

    <div class="d-inline-block align-text-top">
            <span class="d-inline-block align-text-top" style="color: white;">
                Usuario: <?php echo htmlspecialchars($_SESSION['name']); ?>
            </span>
    </div>

      <div class="d-flex align-items-center">
        <button  class="btn btn-success" id = "NuevoC" data-bs-toggle="modal" data-bs-target="#Modal">Nuevo</button>
      </div>

    <?php
        include('Conexion.php'); 
        $sql = "CALL TOTAL_CLIENTES";
        $resultado = $conn->query($sql);
        $fila = $resultado->fetch_assoc();
        $totalClientes = $fila['total'];
    ?>

    <span class="badge bg-primary ms-3">Total clientes: <?php echo $totalClientes; ?></span>

    <form class="d-flex" id="formBuscar">
        <input class="form-control me-2" type="search" id="inputBuscar" placeholder="Buscar" aria-label="Search" >
        <button class="btn btn-outline-success" type="button" id="btnBuscar" style="background-color: white;">
            <i class="bi bi-search"></i>
        </button>
    </form>
    
    </div>
    <!-- Collapsible wrapper -->
  </div>
  <!-- Container wrapper -->
</nav>
<!-- Navbar --> 

    <div class="container-fluid">
        <div class="row">
            <div>
                <h1 class="text-center text-primary">
                     Clientes
                </h1>
            </div>

            <div class="col-12 text-center">
                <table class="table" id = "tablaClientes">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Teléfono</th>
                            <th>Ciudad</th>
                            <th>Colonia</th>
                            <th>Calle</th>
                            <th>Num. Exterior</th>
                            <th>Num. Interior</th>
                            <th>Caso</th>
                        </tr>
                    </thead>

                    <tbody id="resultadoClientes">

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

<!-- Modal para crear clientes -->
<div class="modal" id="Modal" tabindex="-1">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalC"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id = "modal-bodyC">
                       
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary invisible" id="Edita_cliente">Editar clientes</button>
                <button type="button" class="btn btn-primary" id="Guardar_Cliente">Guardar cliente</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    
    $(document).ready(function(){
        TablaClientes();

    $(document).on('click', '#btnBuscar', function() {
        var terminoBusqueda = $('#inputBuscar').val(); 
        buscarClientes(terminoBusqueda);
    });

    function buscarClientes(terminoBusqueda) {
        $.ajax({
            url: './Funciones/FClientes.php',
            type: 'POST',
            data: { funcion: 'BuscarClientes', termino: terminoBusqueda },
            success: function(response) {
                var data = JSON.parse(response);
                $('#resultadoClientes').html(data.html); 
            }
        });
    }

        $(document).on('click', '#Guardar_Cliente', function(){
            
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

            if($("#Caso").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El caso es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Caso").focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FClientes.php',
                type: 'POST',
                data: {funcion: 'Guardar',
                    nombre: $("#Nombre").val(),
                    ApellidoP: $("#ApellidoP").val(),
                    ApellidoM: $("#ApellidoM").val(),
                    Telefono: $("#Telefono").val(),
                    Ciudad: $("#Ciudad").val(),
                    Colonia: $("#Colonia").val(),
                    Calle: $("#Calle").val(),
                    Num_ext: $("#Num_ext").val(),
                    Num_int: $("#Num_int").val(),
                    Caso: $("#Caso").val()
                },

                success: function(response) {
                    if(response.includes("ya existe")) {
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
                        text: 'Cliente guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-bodyC').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('Modal'));
                        modal.hide();
                        TablaClientes();
                    });
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar el cliente',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });

        });

        $(document).on("click","#Edita_cliente",function(){
            var idregistros = $(this).attr('idregistros');
            
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

            if($("#Caso").val()==""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El caso es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $("#Caso").focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FClientes.php',
                type: 'POST',
                data: {funcion: 'Editar',
                    id: idregistros,
                    nombre: $("#Nombre").val(),
                    ApellidoP: $("#ApellidoP").val(),
                    ApellidoM: $("#ApellidoM").val(),
                    Telefono: $("#Telefono").val(),
                    Ciudad: $("#Ciudad").val(),
                    Colonia: $("#Colonia").val(),
                    Calle: $("#Calle").val(),
                    Num_ext: $("#Num_ext").val(),
                    Num_int: $("#Num_int").val(),
                    Caso: $("#Caso").val(),
                },

                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Cliente actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-bodyC').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('Modal'));
                        modal.hide();
                        TablaClientes();
                    });
                },
                
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al actualizar el cliente',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });
        });
        
        $(document).on("click","#NuevoC",function(){
            Modal("Nuevo",0);
            $("#Guardar_Cliente").show();
            $("#Edita_cliente").hide();
            $("#TituloModalC").text("Nuevos clientes");
        });

        $(document).on("click",".editar",function(){
            var idregistros = $(this).attr('idregistros');
            Modal("Editar",idregistros);
            $("#Guardar_Cliente").hide();
            $("#Edita_cliente").show().removeClass('invisible').attr('idregistros',idregistros);
            $("#TituloModalC").text("Editar cliente");
            $('#Modal').modal('show');
        });

        $(document).on("click", ".eliminar", function(){
            var idregistros = $(this).attr('idregistros');
            
            Swal.fire({
                title: '¿Eliminar?',
                text: "¿Quieres borrar el cliente?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './Funciones/FClientes.php',
                        type: 'POST',
                        data: {funcion: 'Eliminar', IdClien: idregistros},
                        success: function(response) {
                            Swal.fire(
                                'Eliminado!',
                                'El cliente ha sido eliminado.',
                                'success'
                            ).then(() => {
                                TablaClientes();
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

        function Modal(tipo, id){
            $.ajax({
                url: './Funciones/FClientes.php',
                type: 'POST',
                data: {funcion: "Modal", tipo: tipo, id: id},
                success: function(response){
                    console.log(response);
                    $('#modal-bodyC').html(response);
                }
            });
        }

        var currentPage = 1;
        var recordsPerPage = 50; 

    function TablaClientes(page) {
        $.ajax({
            url: './Funciones/FClientes.php',
            type: 'POST',
            data: { funcion: 'TablaClientes', 
                page: page, 
                recordsPerPage: recordsPerPage },
            success: function(response) {
                var data = JSON.parse(response);
                $('#resultadoClientes').html(data.html);
                generatePagination(data.totalRecords, recordsPerPage, page);
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
        TablaClientes(page);
    }

    TablaClientes(currentPage);

        $('#resultadoClientes').on('click', '.btn-danger', function(){
            $.ajax({
                url: './Funciones/FClientes.php',
              type: 'POST',
              data: {
                funcion: 'Eliminar', 
                id: $(this).attr('id')
            },
            
              success: function(response){
                TablaClientes();
              }
            });
          });
    });
</script>

</body>
</html>