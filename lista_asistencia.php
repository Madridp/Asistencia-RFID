<?php
include_once "header.php";
include_once "nav.php";
include_once "functions.php";
    $empleados = obtener_historial();
 //  ***********************
 session_start();
    
 if ( !isset($_SESSION['usuario']) ){
     header('Location: index.php');
 }
 //  *

 function cambiarestado($tipo_asistencia){
     $estado = '';

     switch ($tipo_asistencia){
         case 1:
            $estado = 'Entrada';
            break;
         case 2:
            $estado = 'Salida';
            break;
     }
     
     return $estado;
 }
?>

<div class="row" id="app-asistencia-entrada">
    <div class="col-12">
        <h1 class="text-center">Historial de asistencias</h1>
    </div>
    <div class="col-12 form-inline mb-2">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Fecha inicial: &nbsp;</span>
                <input onchange="buscar_fecha($('#buscarFecha').val(), $('#buscarFechaFinal').val());" name="buscarFecha" id="buscarFecha" type="date" class="form-control">
            </div>
            <!--label for="fecha" class="form-label">Fecha: &nbsp;</label-->
            <!--input @change="refreshEmpleadosList" v-model="date" name="date" id="date" type="date" class="form-control">
            <button @click="save" class="btn btn-success ml-2">Consultar</button-->
            
            <!-- <button class="btn btn-success ml-2">Consultar</button> -->
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Fecha final: &nbsp;</span>
                <input onchange="buscar_fecha($('#buscarFecha').val(), $('#buscarFechaFinal').val());" name="buscarFechaFinal" id="buscarFechaFinal" type="date" class="form-control">
            </div>
            <!--label for="fecha" class="form-label">Fecha: &nbsp;</label-->
            <!--input @change="refreshEmpleadosList" v-model="date" name="date" id="date" type="date" class="form-control">
            <button @click="save" class="btn btn-success ml-2">Consultar</button-->
            
            <!-- <button class="btn btn-success ml-2">Consultar</button> -->
        </div>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover table-striped border" id="myTable2" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">
                            Fecha | Hora
                        </th>
                        <th scope="col">
                            Empleado
                        </th>
                        <th scope="col">
                            Tipo de asistencia
                        </th>
                        <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody id="datos_buscador">
                    <?php foreach ($empleados as $empleado) { ?>
                        <tr>
                            <td scope="row">
                                <?php echo $empleado->id_registro ?>
                            </td>
                            <td>
                                <?php echo $empleado->fecha ?>
                            </td>
                            <td>
                                <?php echo $empleado->nombre ?>
                            </td>
                            <td>
                                <?php echo cambiarestado($empleado->tipo_asistencia) ?> 
                                
                            </td>
                            
                            </td>
                            <td>
                                <!--a class="btn btn-danger" href="asistencia_eliminar.php?id=<!?php echo $empleado->id_registro ?>">
                                Eliminar <i class="fa fa-trash"></i>
                                </a-->
                                <a class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="actualizarEmpleadoEliminar('<?= $empleado->id_registro ?>');">
                                Eliminar <i class="fa fa-trash"></i> 
                                </a>
                                 <!-- Modal -->
                                 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmación eliminar</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Seguro que quieres eliminarlo?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <a id="enlace_eliminar_empleado" class= "btn btn-danger" href="asistencia_eliminar.php?id=<!?php echo $empleado->id_registro ?>">
                                        Eliminar <i class=" fas fa-exclamation "></i>
                                        </a>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <!--tr v-for="empleado in empleados">
                        <td>{{ empleado.nombre }}</td>
                        <td>
                            <select v-model="empleado.estado" class="form-control">
                                <option disabled value="unset">--Seleccionar--</option>
                                <option value="asistencia">Asistencia</option>
                                <option value="ausencia">Ausencia</option>
                            </select>
                        </td>
                    </tr-->
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>

<!-- <script src="/js/vue.min.js"></script> -->
<!-- <script src="/js/vue-toasted.min.js"></script> -->
<!--script>
    Vue.use(Toasted);
    const UNSET_STATUS_ASISTENCIA_ENTRADA = "unset";
    new Vue({
        el: "#app-asistencia-entrada",
        data: () => ({
            empleados: [],
            fecha: "",
        }),
        async mounted() {
            this.date = this.getTodaysDate();
            await this.refreshEmpleadosList();
        },
        methods: {
            getTodaysDate() {
                const date = new Date();
                const month = date.getMonth() + 1;
                const day = date.getDate();
                return `${date.getFullYear()}-${(month < 10 ? '0' : '').concat(month)}-${(day < 10 ? '0' : '').concat(day)}`;
            },
            async save() {
                // We only need id and status, nothing more
                let empleadosMapeados = this.empleado.map(empleado => {
                    return {
                        id: empleado.id,
                        estado: empleado.estado,
                    }
                });
                // And we need only where status is set
                empleadosMapeados = empleadosMapeados.filter(empleado => empleado.estado != UNSET_STATUS_ASISTENCIA_ENTRADA);
                const payload = {
                    fecha: this.fecha,
                    empleados: empleadosMapeados,
                };
                const response = await fetch("./save_attedance_data.php", {
                    method: "POST",
                    body: JSON.stringify(payload),
                });
                this.$toasted.show("Guardar", {
                    position: "top-left",
                    duration: 1000,
                });
            },
            async refreshEmpleadosList() {
                // Get all employees
                let response = await fetch("./get_empleados_ajax.php");
                let empleados = await response.json();
                // Set default status: unset
                let empleadoDictionary = {};
                empleados = empleados.map((empleado, index) => {
                    empleadoDictionary[empleado.id] = index;
                    return {
                        id: empleado.id,
                        nombre: empleado.nombre,
                        telefono: empleado.telefono,
                        puesto_id: empleado.puesto_id,
                        estado: UNSET_STATUS_ASISTENCIA_ENTRADA,
                    }
                });
                // Get attendance data, if any
                response = await fetch(`./get_attendance_data_ajax.php?fecha=${this.fecha}`);
                let attendanceData = await response.json();
                // Refresh attendance data in each employee, if any
                attendanceData.forEach(attendanceDetail => {
                    let empleadoId = attendanceDetail.empleado_id;
                    if (empleadoId in empleadoDictionary) {
                        let index = empleadoDictionary[empleadoId];
                        empleados[index].status = attendanceDetail.status;
                    }
                });
                // Let Vue do its magic ;)
                this.empleados = empleados;
            }
        },
    });
    
</script-->
<script type="text/javascript" defer>
        function actualizarEmpleadoEliminar(id_registro){
            let enlace_boton = document.getElementById("enlace_eliminar_empleado");
	        if ( enlace_boton != null ){
		    enlace_boton.href = "asistencia_eliminar.php?id=" + id_registro;
	        }
        }

        function buscar_fecha (fecha_inicial, fecha_final){
            let request = new XMLHttpRequest();
            request.addEventListener("load", requestPeticionBuscarAsistencia);
            request.open("GET", "buscar_fecha.php?fechaInicial=" + fecha_inicial + "&fechaFinal=" + fecha_final);
            request.send();
        }

        function requestPeticionBuscarAsistencia(){
            if ( this.responseText != "" ){
                console.clear();
                let data = JSON.parse(this.responseText);
                
                actualizarTablaAsistencia(data);
            }
        }

        function actualizarTablaAsistencia(data){
            let body = document.getElementById("datos_buscador");
            body.innerHTML = ""; // limpiar la tabla

            if ( data != null ){
                if ( data.length > 0 ){
                    console.log(data);
                    for(i = 0; i < data.length; i++){
                        let fila_nueva = document.createElement("tr");
                        
                        let td_id_registro = document.createElement("td");
                        td_id_registro.appendChild(document.createTextNode(data[i].id_registro));

                        let td_fecha = document.createElement("td");
                        td_fecha.appendChild(document.createTextNode(data[i].fecha));

                        let td_nombre = document.createElement("td");
                        td_nombre.appendChild(document.createTextNode(data[i].nombre));

                        let td_tipo_asistencia = document.createElement("td");
                        let nombre_tipo = cambiarestado(data[i].tipo_asistencia);
                        td_tipo_asistencia.appendChild(document.createTextNode(nombre_tipo));

                        let td_eliminar = document.createElement("td");
                        let a_eliminar = document.createElement('a');
                        a_eliminar.setAttribute("class", "btn btn-danger");
                        a_eliminar.setAttribute("href", "asistencia_eliminar.php?id=" + data[i].id_registro);
                        a_eliminar.text = "Eliminar";
                        let i_eliminar = document.createElement("i");
                        i_eliminar.setAttribute("class", "fa fa-trash");
                        a_eliminar.appendChild(i_eliminar);
                        td_eliminar.appendChild(a_eliminar);

                        fila_nueva.appendChild(td_id_registro);
                        fila_nueva.appendChild(td_fecha);
                        fila_nueva.appendChild(td_nombre);
                        fila_nueva.appendChild(td_tipo_asistencia);
                        fila_nueva.appendChild(td_eliminar);

                        body.appendChild(fila_nueva);
                    }
                }else{
                    let fila_nueva = document.createElement("tr");
                    let td = document.createElement("td");

                    td.colSpan = 6;
                    td.appendChild(document.createTextNode("** NO HAY DATOS **"));

                    fila_nueva.appendChild(td);
                    body.appendChild(fila_nueva);
                }
            }
        }

        function cambiarestado(tipo_asistencia){
            estado = '';

            switch (tipo_asistencia){
                case 1:
                    estado = 'Entrada';
                    break;
                case 2:
                    estado = 'Salida';
                    break;
            }
            
            return estado;
        }
    </script>
       
<!--link type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" /-->
<link type="text/css" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.bootstrap5.min.css" />
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" />
<link type="text/css" href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap5.min.css" />
<!-- <script type="text/javascript" src="//cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#myTable2').DataTable({
            "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por pagina",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_/_PAGES_",
                    "infoEmpty": "Ningun registrado encontrado",
                    "infoFiltered": "(coincidencias de _MAX_ registros)",
                    "search": "Búsqueda",
                    "LoadingRecords": "Cargando ...",
                    "Processing": "Procesando...",
                    "paginate": {
                        "previous": "<",
                        "next": ">",
                    }
                    
                },
            "searching": false,/*
            "paging": true,
            lengthChange: false,
            "ordering": true,
            "info": true,
            dom: 'Bfrtip',*/
            
            //buttons: [
                // 'excel', 'pdf'
              //  'excel'
               /*{
                    extend: 'excelHtml5',
                    title: 'Reporte espacios',
                    footer: true
                },*/
             //]
        });
        /*table.buttons().container()
        .appendTo( $('.col-md-6:eq(0)', table.table().container() ) );*/
    });
</script>
<style>
    #myTable2 {
    font-size: 15px;
    }

    td {
    font-size: 15px;
    color: black;
    }

    th {
    font-size: 15px;
    color:green;  
    }
</style>

<?php
include_once "footer.php";