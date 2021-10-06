<?php 

$usuario=$_POST['usuario'];
$contrasena=$_POST['contrasena'];
session_start();
// $_SESSION['usuario']=$usuario;

// var_dump($contrasena);
// die();

include('db.php');

$consulta="SELECT * FROM usuario where usuario='$usuario' and contrasena='$contrasena'";
$resultado=mysqli_query($conexion,$consulta);

// $filas=mysqli_num_rows($resultado);
$usaurios_filtrados = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

mysqli_free_result($resultado);
mysqli_close($conexion);

$respuesta = 0;

if ( $usaurios_filtrados ){
    $usuario = $usaurios_filtrados[0];
    // var_dump($usaurios_filtrados[0]);
    // die();
    
    if($usaurios_filtrados[0] != null ){
        $_SESSION['usuario'] = $usuario;
        //header("location:empleados.php");
        $respuesta = 1;
    }else{
        //$_SESSION['error'] = "Datos invalidos";
        //header("location:index.php");  
        $respuesta = 0;
    }        
}else{
    // $_SESSION['error'] = "Datos invalidos";
    //header("location:index.php");  
    $respuesta = 0;
}

echo $respuesta;