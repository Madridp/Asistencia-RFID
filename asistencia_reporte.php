<?php
include_once "header.php";
include_once "nav.php";
include_once "functions.php";
 //  ***********************
 session_start();
    
 if ( !isset($_SESSION['usuario']) ){
     header('Location: index.php');
 }
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Reporte de Asistencia</h1>
    </div>
    <div class="col-12 form-inline mb-2">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Fecha inicial: &nbsp;</span>
                <input onchange="buscar_aistencia_fechas($('#buscarFecha').val(), $('#buscarFechaFinal').val());" name="buscarFecha" id="buscarFecha" type="date" class="form-control">
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="">Fecha final: &nbsp;</span>
                <input onchange="buscar_aistencia_fechas($('#buscarFecha').val(), $('#buscarFechaFinal').val());" name="buscarFechaFinal" id="buscarFechaFinal" type="date" class="form-control">
            </div>
        </div>
    </div>
    <a href="./download_employee_report.php?start=<?php echo $start ?>&end=<?php echo $end ?>" class="btn btn-info mb-2">Descargar Reporte Excel</a>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Cantidad Asistencias</th>
                        <th>Cantidad Ausencias</th>
                    </tr>
                </thead>
                <tbody id="tbody_reporte_asistencia">
                    <!-- se actualizara con javascript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" defer>
        function buscar_aistencia_fechas (fecha_inicial, fecha_final){
            let request = new XMLHttpRequest();
            request.addEventListener("load", requestReporteAsistencia);
            request.open("GET", "obtenerRegistrosAsistencia.php?fechaInicial=" + fecha_inicial + "&fechaFinal=" + fecha_final);
            request.send();
        }

        function requestReporteAsistencia(){
            if ( this.responseText != "" ){
                console.clear();
                let data = JSON.parse(this.responseText);
                
                actualizarTablaAsistencia(data);
            }else{
                actualizarTablaAsistencia([]);
            }
        }

        function actualizarTablaAsistencia(data){
            let body = document.getElementById("tbody_reporte_asistencia");
            body.innerHTML = ""; // limpiar la tabla

            if ( data != null ){
                if ( data.length > 0 ){
                    console.log(data);
                    for(i = 0; i < data.length; i++){
                        let fila_nueva = document.createElement("tr");
                        
                        let td_nombre_empleado = document.createElement("td");
                        td_nombre_empleado.appendChild(document.createTextNode(data[i].nombre));

                        let td_constador_asistencia = document.createElement("td");
                        td_constador_asistencia.appendChild(document.createTextNode(data[i].contador_asistencia));

                        let td_constador_austencia = document.createElement("td");
                        td_constador_austencia.appendChild(document.createTextNode(data[i].contador_ausencia));

                        fila_nueva.appendChild(td_nombre_empleado);
                        fila_nueva.appendChild(td_constador_asistencia);
                        fila_nueva.appendChild(td_constador_austencia);

                        body.appendChild(fila_nueva);
                    }
                }else{
                    let fila_nueva = document.createElement("tr");
                    let td = document.createElement("td");

                    td.colSpan = 3;
                    td.appendChild(document.createTextNode("** NO HAY DATOS **"));

                    fila_nueva.appendChild(td);
                    body.appendChild(fila_nueva);
                }
            }
        }
</script>

<?php
include_once "footer.php";
?>