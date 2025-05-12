<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonos</title>
    <link rel="icon" href="LOGO.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/jquery-3.7.1.min"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
            <a href="Pagos.php" class="btn btn-success text-white">Pagos</a>
        </div>

        <?php
            include('Conexion.php');
            $sql = "CALL TOTAL_ABONOS";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total = $row['total'];
        ?>

        <span class="badge bg-primary ms-3">Total abonos: <?php echo $total?></span>

        <div>
            <button type="button" class="btn btn-success" id = "NAbono" data-bs-toggle="modal" data-bs-target="#agregarAbono"> Agregar abono </button>
        </div>

        <div>
            <a href="ReporteAbonos.php" class="btn btn-success text-white">Reportes</a>
        </div>

         <form class="d-flex" id="formBuscar">
            <input class="form-control me-2" type="search" id="inputBuscar" placeholder="Buscar" aria-label="Search" >
            <button class="btn btn-outline-success" type="button" id="btnBuscar" style="background-color: white;">
                <i class="bi bi-search"></i>
            </button>
        </form>

    </div>
</nav>

<!-- Modal abonos -->
<div class="modal fade" id="agregarAbono" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-black" style="background: linear-gradient(135deg, #d9d9d9, #a6a6a6, #808080);">
                <h3 class="modal-title" id="TituloModalPagos"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id = "ModalAbonos">

            </div>

            <div class="modal-footer">
                <button type = "button" class="btn btn-primary invisible" id = "EdiAbono">Editar</button>
                <button type="submit" class="btn btn-primary" id="GuardarAbono" name="registrar">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
             </div>
        </div>
    </div>
</div>
<!-- Modal -->

<div class="container-fluid">
    <div class="row">
        <div>
            <h1 class="text-center text-primary">
                Abonos
            </h1>
        </div>

        <div class="col-12 text-center">
            <table class="table" id="tablaAbonos">
                <thead>
                    <th></th>
                    <th>Clientes</th>
                    <th></th>
                    <th>Pago pendiente</th>
                    <th>Abono</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </thead>

                <tbody id="resultadoAbonos">
                    
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

<Script>
    $(document).ready(function(){
        TablaAbonos();

        $(document).on('click', '#btnBuscar', function() {
            var terminoBusqueda = $('#inputBuscar').val(); 
            buscarPago(terminoBusqueda);
        });

        function buscarPago(terminoBusqueda) {
            $.ajax({
                url: './Funciones/FAbonos.php',
                type: 'POST',
                data: { funcion: 'BuscarAbono', termino: terminoBusqueda },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#resultadoAbonos').html(data.html); 
                }
            });
        }

        $(document).on('click','#GuardarAbono',function(){
            if($('#cliente').val() == ""){
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

            if($('#Abono').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese el abono',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#Abono').focus();
                });
                return false;
            }

            if($('#FechaAbono').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese la fecha del abono',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                    $('#FechaAbono').focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FAbonos.php',
                type: 'POST',
                data: {
                    funcion: 'GuardarAbono',
                    cliente: $('#cliente').val(),
                    Abono: $('#Abono').val(),
                    FechaAbono: $('#FechaAbono').val()
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
                        text: 'Abono guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#ModalAbonos').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('agregarAbono'));
                        modal.hide();
                        TablaAbonos();
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

        $(document).on('change', '#cliente', function() {
            var clienteId = $(this).val();
            if(clienteId) {
                $.ajax({
                    url: './Funciones/FAbonos.php',
                    type: 'POST',
                    data: {
                        funcion: 'ObtenerPagoPendiente', 
                        clienteId: clienteId
                    },
                    success: function(response) {
                        if(response !== "") {
                            $('#PagoPendiente').val(response);
                        } else {
                            $('#PagoPendiente').val('0');
                            alert('No se encontró información de pago para este cliente');
                        }
                    },
                    error: function() {
                        alert('Error al obtener el pago pendiente');
                    }
                });
            } else {
                $('#PagoPendiente').val('');
            }
        });

        $(document).on('click','#EdiAbono',function(){
            var idregistros = $(this).attr('idregistros');

            if($('#cliente').val() == ""){
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

            if($('#Abono').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese el abono',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                $('#Abono').focus();
                });
                return false;
            }

            if($('#FechaAbono').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese la fecha del abono',
                    confirmButtonColor: '#3085d6',
                }).then(()=> {
                $('#FechaAbono').focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FAbonos.php',
                type: 'POST',
                data: {
                    funcion: 'Editar',
                    IdAbono: idregistros,
                    cliente: $('#cliente').val(),
                    Abono: $('#Abono').val(),
                    FechaAbono: $('#FechaAbono').val()
                },
                success: function(response){

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Abono editado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#ModalAbonos').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('agregarAbono'));
                        modal.hide();
                        TablaAbonos();
                    });
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al editar el abono',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });    
        });

        $(document).on('click','.editar',function(){
            var idregistros = $(this).attr('idregistros');
            ModalAbono('Editar', idregistros);
            $('#GuardarAbono').hide();
            $('#EdiAbono').show().removeClass('invisible').attr('idregistros', idregistros);
            $('#TituloModalPagos').text('Editar abono');
            $('#agregarAbono').modal('show');
        });

        $(document).on('click','.eliminar',function(){
            var idregistros = $(this).attr('idregistros');

            Swal.fire({
                title: '¿Está seguro de eliminar el abono?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './Funciones/FAbonos.php',
                        type: 'POST',
                        data: {
                            funcion: 'Eliminar',
                            IdAbono: idregistros
                        },
                        success: function(response){
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Abono eliminado correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                TablaAbonos();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar el abono',
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click','#NAbono',function(){
            ModalAbono('Guardar',0);
            $('#GuardarAbono').show();
            $('#EdiAbono').hide();
            $('#TituloModalPagos').text('Agregar abono');
        });

        function ModalAbono(tipo,id){
            $.ajax({
                url: './Funciones/FAbonos.php',
                type: 'POST',
                data: {
                    funcion: 'ModalAbono',
                    tipo: tipo,
                    id: id
                },
                success: function(response){
                    $('#ModalAbonos').html(response);
                }
            });
        }

        var currentPage = 1;
        var recordsPerPage = 50;

        function TablaAbonos(page){
            $.ajax({
                url: './Funciones/FAbonos.php',
                type: 'POST',
                data: {
                    funcion: 'TablaAbonos',
                    page: page,
                    recordsPerPage: recordsPerPage
                },
                success: function(response){
                    var data = JSON.parse(response);
                    $('#resultadoAbonos').html(data.html);
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
            TablaAbonos(page);
        };

        TablaAbonos(currentPage);

        $('#resultadoAbonos').on('click','.btn-danger',function(){
            $.ajax({
                url: './Funciones/FAbonos.php',
                type: 'POST',
                data:{
                    funcion: 'Eliminar',
                    IdAbono: $(this).attr('idregistros')
                },

                success: function(response){
                    TablaAbonos();
                }
            });
        });

    });
</Script>
</body>
</html>