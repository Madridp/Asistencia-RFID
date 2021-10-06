<?php
include_once "header.php";
include_once "nav.php";
 //  ***********************
 session_start();
    
 if ( !isset($_SESSION['usuario']) ){
     header('Location: index.php');
 }
 //  *
?>
<div class="row" id="consultaEmpleadosRFID">
    <div class="col-12">
        <h1 class="text-center">RFID Emparejamiento</h1>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover table-striped border" id="myTabla">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">
                            Empleado
                        </th>
                        <th scope="col">
                            RFID serial
                        </th>
                        <th scope="col">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="empleado in empleados">
                        <td>
                            {{ empleado.nombre }}
                        </td>
                        <td>

                            <h4 v-if="empleado.rfid_serial"><span class="badge badge-success"><i class="fa fa-check"></i>&nbsp;Asignada( {{ empleado.rfid_serial }} )</span></h4>
                            <h4 v-else-if="empleado.waiting"><span class="badge badge-warning"><i class="fa fa-clock"></i>&nbsp;Esperando...a leer una tarjeta RFID</span></h4>
                            <h4 v-else><span class="badge badge-primary"><i class="fa fa-times"></i>&nbsp;No asignada</span></h4>
                        </td>
                        <td>
                            <button @click="removeRfidCard(empleado.rfid_serial)" v-if="empleado.rfid_serial" class="btn btn-danger">Remover</button>
                            <button v-else-if="empleado.waiting" @click="cancelWaitingForPairing" class="btn btn-warning">Cancelar</button>
                            <button @click="assignRfidCard(empleado)" v-else class="btn btn-info">Asignar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- <script src="/js/vue.min.js"></script> -->
<!-- <script src="/js/vue-toasted.min.js"></script> -->
<script>
    Vue.use(Toasted);
    let shouldCheck = true;
    const CHECK_PAIRING_EMPLOYEE_INTERVAL = 1000;
    
    new Vue({
        el: "#consultaEmpleadosRFID",
        data() {
            return {
                empleados: [],
                fecha: ""
            }
        },
        async mounted() {
            await this.setReaderForReading();
            await this.refreshEmpleadosList();
        },
        methods: {
            async removeRfidCard(rfidSerial) {
                await fetch("./remove_rfid_card.php?rfid_serial=" + rfidSerial);
                this.$toasted.show("RFID removed", {
                    position: "top-left",
                    duration: 1000,
                });
                await this.refreshEmpleadosList();
            },
            async cancelWaitingForPairing() {
                shouldCheck = false;
                await this.setReaderForReading();
            },
            async setReaderForReading() {
                await fetch("./set_reader_for_reading.php");
            },
            async assignRfidCard(empleado) {
                shouldCheck = true;
                const empleadoId = empleado.id;
                // console.log("El empleadoId es: " + empleadoId);
                empleado.waiting = true;
                await fetch("./set_reader_for_pairing.php?id=" + empleadoId);
                this.checkIfEmployeeHasJustAssignedRfid(empleado);
            },
            async checkIfEmployeeHasJustAssignedRfid(empleado) {
                // console.log("Entro a checkIfEmployeeHasJustAssignedRfid: " + empleado.id);
                const r = await fetch("./get_empleado_rfid_serial_by_id.php?id=" + empleado.id);
                const serial = await r.json();
                if (!shouldCheck) {
                    empleado.waiting = false;
                    return;
                }
                if (serial) {
                    this.$toasted.show("RFID asignada!", {
                        position: "top-left",
                        duration: 1000,
                    });
                    await this.setReaderForReading();
                    await this.refreshEmpleadosList();
                } else {
                    console.log("No serial encontrada...");
                    setTimeout(() => {
                        this.checkIfEmployeeHasJustAssignedRfid(empleado);
                    }, CHECK_PAIRING_EMPLOYEE_INTERVAL);
                }
            },
            async refreshEmpleadosList() {
                // Get all employees
                let response = await fetch("./get_empleados_ajax.php");
                let empleados = await response.json();
                // Set rfid_serial by default: null
                let empleadoDictionary = {};
                empleados = empleados.map((empleado, index) => {
                    empleadoDictionary[empleado.id_empleado] = index;
                    return {
                        id: empleado.id_empleado,
                        nombre: empleado.nombre,
                        telefono: empleado.telefono,
                        rfid_serial: null,
                        waiting: false,
                       //0 puesto_id: empleado.puesto_id
                    }
                });
                // Get RFID data, if any
                response = await fetch(`./get_empleados_with_rfid.php`);
                let rfidData = await response.json();
                // Refresh rfid data in each employee, if any
                rfidData.forEach(rfidDetail => {
                    // console.log(empleadoDictionary);
                    // console.log(rfidDetail);
                    let empleadoId = rfidDetail.empleados_id;

                    // console.log(empleadoId);
                    /// asigna el rfid al empleado correcto
                    if (empleadoId in empleadoDictionary) {
                        let index = empleadoDictionary[empleadoId];
                        empleados[index].rfid_serial = rfidDetail.rfid_serial;
                    }
                });
                // Let Vue do its magic ;)
                this.empleados = empleados;
            }
        },
    });
</script>
<style type="text/css">
    #myTabla {
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