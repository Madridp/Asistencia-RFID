<?php
if (!isset($_GET["id"])) {
    exit("employee_id is required");
}
include_once "functions.php";
$empleadoId = $_GET["id"];
setReaderForEmpleadoPairing($empleadoId);
