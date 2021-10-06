<?php
include_once "header.php";
include_once "nav.php";
include_once "functions.php";
 //  ***********************
 session_start();

 date_default_timezone_set('America/Guatemala'); 
    
 if ( !isset($_SESSION['usuario']) ){
     header('Location: index.php');
 }
 //  *
$start = date("Y-m-d");
$end = date("Y-m-d");
if (isset($_GET["start"])) {
    $start = $_GET["start"];
}
if (isset($_GET["end"])) {
    $end = $_GET["end"];
}
$empleados = getEmployeesWithAttendanceCount($start, $end);

// ***************************************
// Domingo = 0
// Lunes = 1
// Martes = 2
// Miercoles = 3
// Jueves = 4
// Viernes = 5
// Sabado = 6
// $timestamp = strtotime('2021-09-19');
// $dia = date('w', $timestamp);
// var_dump($dia);
// die();
$dias_intervalos = array();
$interval = new DateInterval('P1D');

$realEnd = new DateTime('2021-09-30');
$realEnd->add($interval);

$period = new DatePeriod(new DateTime('2021-09-01'), $interval, $realEnd);

foreach($period as $date) { 
    $fecha_iterativa_YMD = $date->format('Y-m-d');

    $timestamp = strtotime($fecha_iterativa_YMD);
    $dia = date('w', $timestamp);
    if ( $dia != 0 && $dia != 6 ){ // si no es domingo ni sabado
        $dias_intervalos[] = $fecha_iterativa_YMD;
    }
}
var_dump($dias_intervalos);
// ***************************************
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Reporte de Asistencia</h1>
    </div>
    <div class="col-12">

        <form action="asistencia_reporte.php" class="form-inline mb-2">
            <label for="start">Comenzar:&nbsp;</label>
            <input required id="start" type="date" name="start" value="<?php echo $start ?>" class="form-control mr-2">
            <label for="end">Fin:&nbsp;</label>
            <input required id="end" type="date" name="end" value="<?php echo $end ?>" class="form-control">
            <button class="btn btn-success ml-2">Filtro</button>
        </form>
        <a href="./download_employee_report.php?start=<?php echo $start ?>&end=<?php echo $end ?>" class="btn btn-info mb-2">Descargar Reporte Excel</a>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Contar Asistencias</th>
                        <th>Contar Ausencias</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $empleado) { ?>
                        <tr>
                            <td>
                                <?php echo $empleado->nombre ?>
                            </td>
                            <td>
                                <?php echo $empleado->asistencia_count ?>
                            </td>
                            <td>
                                <?php echo $empleado->ausencia_count ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include_once "footer.php";