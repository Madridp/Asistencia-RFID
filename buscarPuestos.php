<?php 
include ("db.php");


$consulta="SELECT * FROM puesto";
$resultado=mysqli_query($conexion,$consulta);

// $arreglo=mysqli_fetch_array($resultado);
$arreglo=mysqli_fetch_all($resultado, MYSQLI_ASSOC);


mysqli_close($conexion);

echo json_encode($arreglo);
// echo json_encode("as");
// echo json_encode($arreglo, JSON_UNESCAPED_LINE_TERMINATORS);
