<?php
include_once "functions.php";
$empleados = getEmpleados();
echo json_encode($empleados);