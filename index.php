<?php
    session_start();

    if ( isset($_SESSION['usuario']) ){
        header('Location: empleados.php');
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>

    <!--JQUERY-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <!-- FRAMEWORK BOOTSTRAP para el estilo de la pagina-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
   
    <link rel="shortcut icon" type="image/x-icon" href="img/lcz.png">
    <link rel="stylesheet" href="css/estilos.css"/>
 
    <hr>
</head>
<body>
    
    <!-- action="validar.php" -->
    <form class="formulario" id="formulario_login"  method="post">
        <h1> Colegio Liceo Cristiano Zacapaneco</h1>
        <h2>Control de Asistencia de Personal</h2>
        <img src="img/lcz.png" height="140dp" width="140dp"/>

        <?php if ( isset($_SESSION['error']) ) { ?>
            <div class="alert alert-danger" role="alert">
                Usuario y Contrasena invalidos.
            </div>
            <?php $_SESSION['error'] = null ?>
        <?php } 
        ?>
		<div class="contenorItems" id="Usuario">
			<input name="usuario" id="usuario" type="text" placeholder="Nombre de usuario" class="formulario" required>
			<img id="start_usuario" src="img/mic.gif">
		</div>
		<div class="contenorItems" id="show_hide_password" >
            <input name="contrasena" id="contrasena" type="password" placeholder="Contraseña" class="formulario" required>
            <img id="start_contrasena" src="img/mic.gif">
            <div class="contenorItems" >
            <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
            </div>
		</div>
        <div class="contenorItems" id="button">
            <button id="ingresar" type="button" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i>  Ingresar </button>
            <img id="start_ingresar" src="img/mic.gif"> 
        </div>
    </form>
        <div>
		<p class='error' id="mensaje_error">
         </p>
        </div>
    <input type="hidden" id="aux" value="Bienvenido administrador"/>
    <script type="text/javascript" defer>
        $(document).ready(function() {
        $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
        });
    });
    </script>
    <script type="text/javascript" src="js/main.js" defer></script>
</body>
</html>



