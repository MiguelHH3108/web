<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="icon" href="LOGO.jpg">
    <script src="js/jquery-3.7.1.min"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="Contraseña.js"></script>
</head>
<body>
    
<section class="vh-100" style="background-image: url('LOGO.jpg'); background-size: cover; 
    background-position: center; 
    background-repeat: no-repeat;">
  <div class="container py-5 h-100 ">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <h3 class="mb-5">Bienvenido</h3>
          <form action="Loggin.php" method = "post">
            
            <div data-mdb-input-init class="form-outline mb-4">
              <input type="text" id="form2Example1" class="form-control form-control-lg" name="Usuario" placeholder="Usuario" required>    
            </div>

            <div data-mdb-input-init class="form-outline mb-4">
              <input type="password" id="show-password-input-2" class="form-control form-control-lg" name = "Contra" placeholder = "Contraseña" required />
            </div>

            <div class="form-check d-flex justify-content-center mb-4">
              <input class="form-check-input me-4" type="checkbox" value="" id="show-password-toggle-checkbox" />
              <label class="form-check-label" for="show-password-toggle-checkbox">Ver contraseña</label>

              <script>
                document.getElementById('show-password-toggle-checkbox').addEventListener('change', function () {
                  let passwordInput = document.getElementById('show-password-input-2');
                  passwordInput.type = this.checked ? 'text' : 'password';
                });
              </script>
            </div>

            <input data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block" type="submit" name = "btnInicio" value = "Inicio">
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>