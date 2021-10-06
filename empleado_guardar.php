<?php
if (!isset($_POST["nombre"])) {
    exit("No existen datos");
}
include_once "functions.php";
$nombre = $_POST["nombre"];
$puesto = $_POST["id_puesto"];
$telefono = $_POST["telefono"];
$id_usuario = $_POST["id_usuario"];


try{
    saveEmpleados($nombre, $puesto, $telefono, $id_usuario);
    echo "1";
}catch(Exception $e){
    echo "0";
}

//header("Location: empleados.php");