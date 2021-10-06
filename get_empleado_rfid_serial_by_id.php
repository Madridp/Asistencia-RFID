<?php
if (!isset($_GET["id"])) {
    exit("id no está presente");
}
include_once "functions.php";
$empleado_rfid = getEmpleadoRfidById($_GET["id"]);

$serial = "";
if ($empleado_rfid ) {
    $serial = $empleado_rfid->rfid_serial;
}

// var_dump($empleado_rfid);
// die();

echo json_encode($serial);
