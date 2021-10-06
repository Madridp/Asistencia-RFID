<?php

session_start();
if (!isset($_GET["id"])) exit("No existe el id");
include_once "header.php";
include_once "nav.php";
$id = $_GET["id"];
include_once "functions.php";
$empleado = getEmpleadoById($id);
$editar_empleado = $empleado;

// var_dump($editar_empleado);
// die();
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Editar empleado</h1>
    </div>
    <div class="col-12">
        <!-- <form action="empleado_actualizar.php" method="POST" id="formulario_actualizarEmpleado"> -->
        <form action="#" method="POST" id="formulario_actualizarEmpleado">
		<!--?php foreach ($empleados as $empleado) { ?-->
            <input type="hidden" id="id" name="id" value="<?php echo $editar_empleado->id; ?>">
			<label for="">Nombre (*)</label>
            <div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text" id=""> <i class="fas fa-user"></i> &nbsp;</span>
				</div>
                <input value="<?php echo $editar_empleado->nombre; ?>" name="nombre" placeholder="Nombre" type="text" id="nombre" class="form-control" required>
				<img id="start_nombre" src="img/mic.gif">
            </div>
			<br>
			<br>
			<label for="">Seleccione Puesto (*)</label>
            <div class="input-group">
				<div class="input-group-prepend">
    				<span class="input-group-text" id=""><i class="fas fa-briefcase"></i> &nbsp;</span>
  				</div>
                <select name="id_puesto" id="id_puesto" class="form-control form-control-lg">
                </select>
				<img id="start_puesto" src="img/mic.gif">
            </div>
			<br>
			<br>
			<label for="">Telefono (*)</label>
            <div class="input-group">
				<div class="input-group-prepend">
    				<span class="input-group-text" id=""><i class="fas fa-phone-square"></i> &nbsp;</span>
  				</div>
                <input value="<?php echo $editar_empleado->telefono; ?>" name="telefono" placeholder="Telefono" type="text" id="telefono" class="form-control" required>
				<img id="start_telefono" src="img/mic.gif">
            </div>
			<br>
			<br>
			<p>* Los campos son obligatorios</p>
            <input type="hidden" id="id_usuario" name="id_usuario" value="<?= $_SESSION['usuario'] ? $_SESSION['usuario']['id'] : '' ?>">
            <div class="btn-group mb-3">
		<!--?php } ?-->
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
<script type="text/javascript">
		window.onload = function() {
		 	cargarPuestos();
		};

		/**
		 * Cargar las avenidas.
		 */
		function cargarPuestos(){
			var oReq = new XMLHttpRequest();
	        oReq.addEventListener("load", requestObtenerPuesto);
	        oReq.open("GET", "buscarPuestos.php" + "?buscador=" + "");
	        oReq.send();
		}

		/**
		 * Metodo del callback del XMLHttpRequest para obtener las avenidas
		 */
		function requestObtenerPuesto(){
            console.log(this.responseText);
			if ( this.responseText != "" ){
				// console.log(this.responseText);
	            let puestoFiltrados = JSON.parse(this.responseText);
	            // console.log(avenidasFiltradas);
	            actualizarSelectPuesto(puestoFiltrados);
	        }else{
	            actualizarSelectPuesto([]);
	        }
		}

		/**
		 * De las avenidas obtenidas, actualizamos el combo para mostrarlas.
		 */
		function actualizarSelectPuesto(puesto){
			if ( puesto.length < 1 ){
	            alert("No se han encontrado puestos...");
	        }

	        let selectPuesto = document.getElementById("id_puesto");
	        selectPuesto.innerHTML = ""; // limpiamos

	        if ( selectPuesto){
	        	for(var item of puesto){
	        		let option = document.createElement("option");

	        		option.value = item['id'];
	        		option.innerText = item['puesto'];
						
					if ( "<?php echo $editar_empleado->puesto_id; ?>" == item.id ){
						option.selected = true;
					}


	        		selectPuesto.appendChild(option);
	        	}
	        }
		}
	</script>
	<script type="text/javascript" src="js/empleadoActualizar.js" defer></script>
<?php
include_once "footer.php";
