<?php
if (!isset($_POST["nombre"]) || !isset($_POST["id"])) {
    exit("No existen datos");
}

include_once "functions.php";
$nombre = $_POST["nombre"];
$telefono = $_POST["telefono"];
$puesto = $_POST["id_puesto"];
$id = $_POST["id"];
$id_usuario = $_POST["id_usuario"];

// var_dump([$id, $nombre, $telefono, $puesto, $id_usuario]);
// die();

try{
    updateEmpleados($nombre, $telefono, $id, $puesto, $id_usuario);
    echo "1";
}catch(Exception $e){
    echo "0";
}

//header("Location: empleados.php");