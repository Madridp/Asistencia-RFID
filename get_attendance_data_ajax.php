<?php
if (!isset($_GET["fecha"])) {
    exit("[]");
}
include_once "functions.php";
$fecha = $_GET["fecha"];
$dato = getAttendanceDataByDate($fecha);
echo json_encode($dato);