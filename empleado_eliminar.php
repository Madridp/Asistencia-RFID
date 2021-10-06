<?php
if (!isset($_GET["id"])) {
    exit("No existen datos");
}
include_once "functions.php";
$id = $_GET["id"];
deleteEmpleados($id);
header("Location: empleados.php");