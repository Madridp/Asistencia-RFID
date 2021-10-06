<?php
include_once "header.php";
include_once "nav.php";
include_once "functions.php";
    $empleados = getEmpleados();

    // var_dump($empleados);
    // die();
session_start();
date_default_timezone_set('America/Guatemala');
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Agregar asistencia manual</h1>
        <br>
    </div>
    <div class="col-12">
        <!--form action="registrar_asistencia_manual.php" method="POST"-->
        <form method="POST" action="#" id="registra_asistencia_manual">
        <label for="">Fecha | Hora (*)</label>
		<div class="input-group">
            <div class="input-group-prepend">
				<span class="input-group-text" id=""> <i class="fas fa-calendar-week"></i> &nbsp;</span>
			</div>
                <!--label for="fecha_hora">Fecha | Hora</label-->
                <input name="fecha_hora" placeholder="fecha , hora" type="datetime-local" value="<?php echo date('Y-m-d\TH:i');?>" id="fecha_hora" class="form-control" required>
            </div>
            <br>
            <br>
            <label for="">Nombre empleado (*)</label>
            <div class="input-group">
                <div class="input-group-prepend">
					<span class="input-group-text" id=""> <i class="fas fa-user"></i> &nbsp;</span>
				</div>
                <!-- <input name="nombre_empleado" placeholder="Nombre" type="text" id="nombre_empleado" class="form-control" required> -->
                <select class="form-control form-control-lg" name="id_empleado" id="id_empleado">
                    <option value="">Seleccione...</option>
                    <?php foreach($empleados as $empleado) { ?>
                        <option value="<?= $empleado->id_empleado ?>"><?= $empleado->nombre ?></option>
                    <?php } ?>
                </select>
                <img id="start_nombreEmpleado" src="img/mic.gif">
            </div>
            <br>
            <br>
            <label for="">Tipo de registro (*)</label>
            <div class="input-group">
                <div class="input-group-prepend">
					<span class="input-group-text" id=""> <i class="fas fa-user-check"></i> &nbsp;</span>
				</div>
                <select name="tipo_asistencia" id="tipo_asistencia" class="form-control form-control-lg">
					<option value="">--- Seleccione el tipo de registro ---</option>
					<option value="1">Entrada</option>
					<option value="2">Salida</option>
                </select>
                <img id="start_tipoRegistro" src="img/mic.gif">
            </div>
            <br>
            <br>
            <p>* Los campos son obligatorios</p>
            <div class="btn-group mb-3">
                <button class="btn btn-success" id="guardar"><span class="fa fa-check"></span>&nbsp;
                    Guardar 
                </button>
                <img id="start_guardar" src="img/mic.gif"> 
            </div>
            <div class="btn-group mb-3">
                <button class="btn btn-danger" id="cancelar"><span class="fa fa-window-close"></span>&nbsp;
                    Cancelar 
				</button>
				<img id="start_cancelar" src="img/mic.gif"> 
            </div>
        </form>
        <br>
        <br>
        <p class='error' style="padding: 20px;
        background-color: rgba(0,0,0,0.2);  font-family: sans-serif;
  		text-align: center;
  		padding: 20px;" id="mensaje_error">
    	</p>
    </div>
</div>
<script type="text/javascript" src="js/asistencia_manual.js" defer></script>
<?php
include_once "footer.php";
