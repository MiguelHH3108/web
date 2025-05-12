<?php
require_once 'Conexion.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="reporte_abonos_'.date('Ymd').'.xls"');

$fechaInicio = $_GET['fecha_inicio'];
$fechaFin = $_GET['fecha_fin'];

$sql = "SELECT t.* FROM Total t 
        WHERE t.Fecha_Abono BETWEEN '$fechaInicio' AND '$fechaFin'
        ORDER BY t.Fecha_Abono DESC";
$result = mysqli_query($conn, $sql);
?>

<table border="1">
    <tr>
        <th colspan="5">REPORTE DE ABONOS</th>
    </tr>
    <tr>
        <th colspan="5">Del <?php echo date('d/m/Y', strtotime($fechaInicio)); ?> al <?php echo date('d/m/Y', strtotime($fechaFin)); ?></th>
    </tr>
    <tr>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Abono</th>
        <th>Saldo Anterior</th>
        <th>Nuevo Saldo</th>
    </tr>
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
    <tr>
        <td colspan="2"><b>TOTAL</b></td>
        <td><b>$<?php echo number_format($totalAbonos, 2); ?></b></td>
        <td colspan="2"></td>
    </tr>
</table>