<?php
    //  ***********************
    session_start();
    
    if ( !isset($_SESSION['usuario']) ){
        header('Location: index.php');
    }
    //  ***********************

    include_once "header.php";
    include_once "nav.php";
    include_once "functions.php";
    $empleados = getEmpleados();

    // var_dump($empleados);
    // die();
?>

<div class="row">
    <div class="col-12">
        <h1 class="text-center">Empleados</h1>
    </div>
    <div class="d-flex pr-5 ml-auto mb-1">
        <div class="input-group">
            <div class="input-group-prepend">
            <span  class="input-group-text"><i class="fas fa-search fa-fw"></i></span>
            <input onkeyup="buscar_ahora($('#buscar').val());" placeholder="Buscar" aria-label="buscador" 
            class="form-control" type="text" id="buscar">
            </div> 
            
        </div>
        <img id="start_buscar" src="img/mic.gif">    
    </div>
    <div class="col-12">
        <a href="empleado_agregar.php" class="btn btn-primary mb-2">Agregar nuevo empleado <i class="fa fa-plus"></i></a>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover table-striped border" id="myTable" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <!--div class="card col-12 mt-5">
                    <div class="card-body">
                        <div id="datos_buscador" class="container pl-5 pr-5"></div>
                    </div>
                </div-->
                <tbody id="datos_buscador">
                    <?php foreach ($empleados as $empleado) { ?>
                        <tr>
                            <td scope="row">
                                <?php echo $empleado->id_empleado ?>
                            </td>
                            <td>
                                <?php echo $empleado->nombre ?>
                            </td>
                            <td>
                                <?php echo $empleado->telefono ?>
                            </td>
                            <td>
                                <?php echo $empleado->puesto ?>
                            </td>
                            <td>
                                <a class="btn btn-warning" href="empleado_editar.php?id=<?php echo $empleado->id_empleado ?>">
                                Editar <i class="fa fa-edit"></i>
                            </a>
                            </td>
                            <td>
                                <!--a class="btn btn-danger" href="empleado_eliminar.php?id=<!?php echo $empleado->id_empleado ?>">
                                Eliminar <i class="fa fa-trash"></i>
                                </a-->
                                <a class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" onclick="actualizarEmpleadoEliminar('<?= $empleado->id_empleado ?>');">
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
                                        <a id="enlace_eliminar_empleado" class= "btn btn-danger" href="empleado_eliminar.php?id=<?php echo $empleado->id_empleado ?>">
                                        Eliminar <i class=" fas fa-exclamation "></i>
                                        </a>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div>
	<p class='error' style="padding: 20px;
        background-color: rgba(0,0,0,0.2);  font-family: sans-serif;
        text-align: center;
        padding: 20px;" id="mensaje_error">
    </p>             
</div>
<script type="text/javascript" src="js/buscar.js" defer></script>
<script type="text/javascript" defer>

        function actualizarEmpleadoEliminar(id_empleado){
            let enlace_boton = document.getElementById("enlace_eliminar_empleado");
	        if ( enlace_boton != null ){
		    enlace_boton.href = "empleado_eliminar.php?id=" + id_empleado;
	        }
        }
        function buscar_ahora (buscar){
            // var parametros = {"buscar": buscar};
            /*
            $ajax({
                data:parametros,
                type:'POST',
                url: 'buscar.php',
                success: function (data){
                    console.log(data);
                    document.getElementById("datos_buscador").innerHTML = data;
                }
            })
            */

            let request = new XMLHttpRequest();
            request.addEventListener("load", requestPeticionBuscarEmpleado);
            request.open("GET", "buscar.php?buscar=" + buscar);
            request.send();
        }

        function requestPeticionBuscarEmpleado(){
            if ( this.responseText != "" ){
                console.clear();
                let data = JSON.parse(this.responseText);
                
                actualizarTablaEmpleados(data);
            }
        }

        function actualizarTablaEmpleados(data){
            let body = document.getElementById("datos_buscador");
            body.innerHTML = ""; // limpiar la tabla

            if ( data != null ){
                if ( data.length > 0 ){
                    console.log(data);
                    for(i = 0; i < data.length; i++){
                        let fila_nueva = document.createElement("tr");
                        
                        let td_id = document.createElement("td");
                        td_id.appendChild(document.createTextNode(data[i].id_empleado));

                        let td_nombre = document.createElement("td");
                        td_nombre.appendChild(document.createTextNode(data[i].nombre));

                        let td_telefono = document.createElement("td");
                        td_telefono.appendChild(document.createTextNode(data[i].telefono));

                        let td_puesto = document.createElement("td");
                        td_puesto.appendChild(document.createTextNode(data[i].puesto_nombre));

                        let td_editar = document.createElement("td");
                        let a_editar = document.createElement('a');
                        a_editar.setAttribute("class", "btn btn-warning");
                        a_editar.setAttribute("href", "empleado_editar.php?id=" + data[i].id_empleado);
                        a_editar.text = "Editar";
                        let i_editar = document.createElement("i");
                        i_editar.setAttribute("class", "fa fa-edit");
                        a_editar.appendChild(i_editar);
                        td_editar.appendChild(a_editar);

                        let td_eliminar = document.createElement("td");
                        let a_eliminar = document.createElement('a');
                        a_eliminar.setAttribute("class", "btn btn-danger");
                        a_eliminar.setAttribute("href", "empleado_eliminar.php?id=" + data[i].id_empleado);
                        a_eliminar.text = "Eliminar";
                        let i_eliminar = document.createElement("i");
                        i_eliminar.setAttribute("class", "fa fa-trash");
                        a_eliminar.appendChild(i_eliminar);
                        td_eliminar.appendChild(a_eliminar);

                        fila_nueva.appendChild(td_id);
                        fila_nueva.appendChild(td_nombre);
                        fila_nueva.appendChild(td_telefono);
                        fila_nueva.appendChild(td_puesto);
                        fila_nueva.appendChild(td_editar);
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
        var table = $('#myTable').DataTable({
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
    #myTable {
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
   /* .form-control-feedback {
    position: absolute;
    padding: 10px;
    pointer-events: none;
    }

    .form-control {
    padding-left: 30px!important;
    }*/
    
</style>

<?php
include_once "footer.php";