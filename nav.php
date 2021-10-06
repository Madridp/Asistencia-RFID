<!--nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="background-color: #e3f2fd;"-->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger fixed-top">
    <a class="navbar-brand" href="https://www.facebook.com/Liceo-Cristiano-Zacapaneco-142773289154796/">
        <img class="img-fluid" style="max-height: 60px" src="img/lcz2.png">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" style="font-size: 18px;" href="empleados.php">Empleados <i class="fa fa-users"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="font-size: 18px;" href="empleado_rfid.php">RFID empleado carnet&nbsp;<i class="fa fa-id-card"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="font-size: 18px;" href="lista_asistencia.php">Lista de asistencia <i class="fa fa-check-double"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="font-size: 18px;" href="asistencia_manual.php">Asistencia manual <i class="fas fa-book-reader"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="font-size: 18px;" href="asistencia_reporte.php">Reportes <i class="fa fa-file-alt"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  src="https://clientes.dongee.com/whatsapp.png" style="font-size: 18px;" href="https://wa.me/50233693809?text=Hola!%20Estoy%20interesado%20en%20tu%20servicio" onclick="return confirm('Llamá al telefono: 3369-3809 o Escribenos por WhatsApp');">Soporte & Ayuda&nbsp;<i class="fas fa-address-book"></i></a>
                
            </li>
            <li class="nav-item active">
                <form action="cerrarSesion.php" method="post">
                    <button type="submit" class="btn btn-md btn-danger" style="margin: 5px; position: absolute; right: 0;">Cerrar sesión <i class="fa fa-sign-out-alt"></i></a>
                </form>
            </li>
        </ul>
        
    </div>
</nav>
<style rel="stylesheet">
    .navbar-nav > li{
    padding-left:10px;
    padding-right:10px;
    }

    .navbar-nav > li{
    margin-left:10px;
    margin-right:10px;
    }
</style>
