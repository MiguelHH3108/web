<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos</title>
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
            <button type="button" class="btn btn-success" id = "NPago" data-bs-toggle="modal" data-bs-target="#agregarPago"> Agregar pago </button>
        </div>

        <?php
            include('Conexion.php'); 
            $sql = "CALL TOTAL_PAGOS";
            $resultado = $conn->query($sql);
            $fila = $resultado->fetch_assoc();
            $totalPagos = $fila['total'];
        ?>

        <span class="badge bg-primary ms-3">Total pagos: <?php echo $totalPagos; ?></span>


        <div>
            <a href="Abonos.php" class="btn btn-success text-white">Abonos</a>
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
            <h1 class="text-center">Pagos</h1>
        </div>
        <div class="col-12 text-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope = "col"></th>
                        <th scope = "col">Cliente</th>
                        <th scope = "col"></th>
                        <th scope = "col">Pago pendiente</th>
                    </tr>
                </thead>
                <tbody id="resultadopago">

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

<!-- Modal pagos -->
<div class="modal fade" id="agregarPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-black" style="background: linear-gradient(135deg, #d9d9d9, #a6a6a6, #808080);">
                <h3 class="modal-title" id="TituloModalPagos"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id = "ModalPagosB">

            </div>

            <div class="modal-footer">
                <button type = "button" class="btn btn-primary invisible" id = "EdiPago">Editar</button>
                <button type="submit" class="btn btn-primary" id="GuardarPago" name="registrar">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
             </div>
        </div>
    </div>
</div>
<!-- Modal -->

<script>
    $(document).ready(function(){
        TablaPagos();

        $(document).on('click', '#btnBuscar', function() {
            var terminoBusqueda = $('#inputBuscar').val(); 
            buscarPago(terminoBusqueda);
        });

        function buscarPago(terminoBusqueda) {
            $.ajax({
                url: './Funciones/FPagos.php',
                type: 'POST',
                data: { funcion: 'BuscarPagos', termino: terminoBusqueda },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#resultadopago').html(data.html); 
                }
            });
        }

        $(document).on('click','#GuardarPago',function(){
            
            if($('#cliente').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El cliente es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    $('#cliente').focus();
                });
                return false;
            }

            if($('#Pago').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El pago es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                $('#Pago').focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FPagos.php',
                type: 'POST',
                data: {
                    funcion: 'GuardarPago',
                    cliente: $('#cliente').val(), 
                    Pago: $('#Pago').val()
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
                        title: 'Exito',
                        text: 'Pago creado',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#ModalPagosB').find('input, textarea').val('');
                        var modal = bootstrap.Modal.getInstance(document.getElementById('agregarPago'));
                        modal.hide();
                        TablaPagos();
                    });
                },

                    error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar el pago',
                        confirmButtonColor: '#3085d6',
                    });
                }
                
            });
        });

        $(document).on('click','#EdiPago',function(){
            var idregistros = $(this).attr('idregistros');

            if($('#cliente').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El cliente es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                    $('#cliente').focus();
                });
                return false;
            }

            if($('#Pago').val() == ""){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El pago es obligatorio',
                    confirmButtonColor: '#3085d6',
                }).then(() => {
                $('#Pago').focus();
                });
                return false;
            }

            $.ajax({
                url: './Funciones/FPagos.php',
                type: 'POST',
                data: {
                    funcion: 'Editar',
                    IdPago: idregistros,
                    cliente: $('#cliente').val(), 
                    Pago: $('#Pago').val() 
                },
                success: function(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Pago actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() =>{
                        TablaPagos();
                        $('#agregarPago').modal('hide');
                    });
                },

                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar el pago',
                        confirmButtonColor: '#3085d6',yy
                    });
                }
            });
        });

        $(document).on('click', ".editar", function(){
            var idregistros = $(this).attr('idregistros'); 
            ModalPago('Editar', idregistros);
            $('#GuardarPago').hide();
            $('#EdiPago').show().removeClass('invisible').attr('idregistros', idregistros);
            $('#TituloModalPagos').text('Editar pago');
            $('#agregarPago').modal('show');
        });

        $(document).on('click',".eliminar",function(){
            var idregistros = $(this).attr('idregistros');

            Swal.fire({
                title: '¿Eliminar?',
                text: "¿Quieres borrar el pago?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './Funciones/FPagos.php',
                        type: 'POST',
                        data: {funcion: 'Eliminar', IdPago: idregistros},
                        success: function(response) {
                            Swal.fire(
                                'Eliminado!',
                                'El pago ha sido eliminado.',
                                'success'
                            ).then(() => {
                                TablaPagos();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar el pago',
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    });
                }
            });

        });

        $(document).on('click','#NPago',function(){
            ModalPago('Guardar',0);
            $('#GuardarPago').show();
            $('#EdiPago').hide();
            $('#TituloModalPagos').text('Agregar pago');
        });

        function ModalPago(tipo, id){
            $.ajax({
                url: './Funciones/FPagos.php',
                type: 'POST',
                data: {funcion: 'ModalPago', tipo: tipo, id: id},
                success: function(response){
                    console.log(response);
                    $('#ModalPagosB').html(response);
                }
            });
        }

        var currentPage = 1;
        var recordsPerPage = 50;

        function TablaPagos(page){
            $.ajax({
                url: './Funciones/FPagos.php',
                type: 'POST',
                data: {funcion: 'TablaPagos',page: page, recordsPerPage: recordsPerPage},

                success: function(response){
                    var data = JSON.parse(response);
                    $('#resultadopago').html(data.html);
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

        window.changePage = function(page) {
            currentPage = page;
            TablaPagos(currentPage);
        };

        TablaPagos(currentPage);

        $('#resultadopago').on('click','.btn-danger',function(){
            $.ajax({
                url: './Funciones/FPagos.php',
                type: 'POST',
                data: {
                    funcion: 'Eliminar',
                    IdPago: $(this).attr('idregistros')
                },
                success: function(response){
                    TablaPagos();
                }
            });
        });
    });
</script>
</body>
</html>