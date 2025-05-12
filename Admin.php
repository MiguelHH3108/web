<?php
    require_once 'Check_session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="Admin.css">
    <link rel="icon" href="LOGO.jpg">
    <script src="js/jquery-3.7.1.min"></script>
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

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>  Cambiar usuario
        </button>

        <form action="CerrarSesion.php" method = "post">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                    <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
                </svg>    
            Cerrar sesión</button>
        </form>

    </div>
</nav>

    <!-- Topic Cards -->
<div id="cards_landscape_wrap-2" style="
        background-image: url('LOGO.jpg'); 
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat;
        min-height: calc(100vh - 56px); 
        margin: 0;
        padding: 20px 0;
    ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <a href="Archivos.php">
                        <div class="card-flyer">
                            <div class="text-box">
                                <div class="image-box">
                                    <img src="Abogado.png" alt="Tu explorador de internet no soporta este archivo" />
                                </div>
                                <div class="text-container">
                                    <h6>Archivos</h6>
                                    <p style = "font-family: Arial, Helvetica, sans-serif;">Aquí podras agregar y gestionar tus archivos.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <a href="Clientes.php">
                        <div class="card-flyer">
                            <div class="text-box">
                                <div class="image-box">
                                    <img src="Clientes.png" alt="Error" />
                                </div>
                                <div class="text-container">                                    
                                    <h6>Clientes</h6>
                                    <p style = "font-family: Arial, Helvetica, sans-serif;">Aquí puedes ver tus clientes, agregar, editar o eliminar clientes.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <a href="Pagos.php">
                        <div class="card-flyer">
                            <div class="text-box">
                                <div class="image-box">
                                    <img src="Pagos.png" alt="Error" />
                                </div>

                                <div class="text-container">
                                    <h6>Pagos</h6>
                                   <p style = "font-family: Arial, Helvetica, sans-serif;">Consulta los pagos y abonos de tus clientes.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <a href="Usuarios.php">
                        <div class="card-flyer">
                            <div class="text-box">
                                <div class="image-box">
                                    <img src="Usuarios.png" alt="Error" />
                                </div>
                                <div class="text-container">
                                    <h6>Abogados/Usuarios</h6>
                                   <p style = "font-family: Arial, Helvetica, sans-serif;">Consulta a las personas que tienen acceso a tu sistema</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal cambio de usuario -->
<div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content w-75">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Cambiar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="CambiarUsuario.php" method = "post">
            
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="form2Example1" class="form-control form-control-lg" name="Usuario" placeholder="Usuario" required>    
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" id="typePasswordX-2" class="form-control form-control-lg" name = "Contra" placeholder = "Contraseña" required>
                    </div>

                    <input data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block" type="submit" name = "btnInicio" value = "Inicio">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>