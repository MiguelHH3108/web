<?php
require_once 'Check_session.php';
require_once 'Conexion.php';

$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d');

$sql = "SELECT t.* FROM Total t 
        WHERE t.Fecha_Abono BETWEEN '$fechaInicio' AND '$fechaFin'
        ORDER BY t.Fecha_Abono DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Abonos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-report {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
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
                    <a href="Abonos.php" class="btn btn-success text-white">Abonos</a>
                </div>

            </div>
        </nav>

    <div class="container">
        <div class="header-report text-center">
            <h2>Reporte de Abonos</h2>
            <p>Del <?php echo date('d/m/Y', strtotime($fechaInicio)); ?> al <?php echo date('d/m/Y', strtotime($fechaFin)); ?></p>
            
            <form method="get" class="row g-3">
                <div class="col-md-5">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fechaInicio; ?>">
                </div>
                <div class="col-md-5">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fechaFin; ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Abono</th>
                        <th>Saldo Anterior</th>
                        <th>Nuevo Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalAbonos = 0;
                    while($row = mysqli_fetch_assoc($result)): 
                        $totalAbonos += $row['Abono'];
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($row['Fecha_Abono'])); ?></td>
                        <td><?php echo htmlspecialchars($row['Nombre'].' '.$row['ApellidoP'].' '.$row['ApellidoM']); ?></td>
                        <td>$<?php echo number_format($row['Abono'], 2); ?></td>
                        <td>$<?php echo number_format($row['PagoPendiente'] + $row['Abono'], 2); ?></td>
                        <td>$<?php echo number_format($row['PagoPendiente'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="total-row">
                        <td colspan="2">TOTAL</td>
                        <td>$<?php echo number_format($totalAbonos, 2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <button onclick="window.print()" class="btn btn-primary">Imprimir Reporte</button>
            <a href="exportar_abonos.php?fecha_inicio=<?php echo $fechaInicio; ?>&fecha_fin=<?php echo $fechaFin; ?>" class="btn btn-success">Exportar a Excel</a>
        </div>
    </div>
</body>
</html>