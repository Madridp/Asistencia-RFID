<?php
if (!isset($_POST["id_empleado"])) {
    exit("No existen datos");
}
include_once "functions.php";
$nombre_empleado = $_POST["id_empleado"];
$tipo_asistencia = $_POST["tipo_asistencia"];
$fecha_hora = $_POST["fecha_hora"];


try{
    guardarAsistencia($nombre_empleado, $tipo_asistencia, $fecha_hora);
    echo "1";
}catch(Exception $e){
    // var_dump($e->getMessage());
    echo "0";
}
