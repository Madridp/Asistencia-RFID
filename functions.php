<?php
if (!defined("RFID_STATUS_FILE")) {
    define("RFID_STATUS_FILE", "rfid_status");
}
if (!defined("RFID_STATUS_READING")) {
    define("RFID_STATUS_READING", "r");
}
if (!defined("RFID_STATUS_PAIRING")) {
    define("RFID_STATUS_PAIRING", "p");
}
if (!defined("PAIRING_EMPLOYEE_ID_FILE")) {
    define("PAIRING_EMPLOYEE_ID_FILE", "pairing_employee_id_file");
}

function getEmpleadosWithRfid()
{
    $query = "SELECT id, rfid_serial, empleados_id FROM empleados_rfid";
    $db = getDatabase();
    $statement = $db->query($query);
    return $statement->fetchAll();
}

function onRfidSerialRead($rfidSerial)
{
    // entra si en el archivo de "rfid_status" tiene el contenido p
    if (getReaderStatus() === RFID_STATUS_PAIRING) {
        // registra en la tabla empleado_rfid, asocinado al serial con el id_empleado en el archivo de emparejamiento
        pairEmpleadoWithRfid($rfidSerial, getPairingEmpleadoId());
        // actualia el estado en el archivo rfid_status
        setReaderStatus(RFID_STATUS_READING);
    } else { // si es "r" u otro, es decir marcar asistencia
        $empleado = getEmpleadoByRfidSerial($rfidSerial);
        if ($empleado) {
            saveEmpleadoAttendance($empleado->id);
        }
    }
}
function deleteEmpleadoattendanceByIdAndDate($empleadoId, $fecha)
{

    $query = "DELETE FROM empleados_entrada where id = ? and fecha = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    return $statement->execute([$empleadoId, $fecha]);
}

/**
 * Marca la aasistencia del empleado que tenga asignada el rfid seria
 * y detectar si es entrada o salida el marcaje
 */
function saveEmpleadoAttendance($empleadoId)
{
    /*
    $date = date("Y-m-d");
    deleteEmpleadoAttendanceByIdAndDate($date, $empleadoId);
    $status = "presencia";
    $query = "INSERT INTO empleados_entrada(id, fecha, estado) VALUES (?, ?, ?)";
    $db = getDatabase();
    $statement = $db->prepare($query);
    return $statement->execute([$empleadoId, $date, $status]);
    */

    $db = getDatabase();
    date_default_timezone_set('America/Guatemala');
    $tipo_asistencia = 1; // 1=entrada, 2=salida
    $fecha_actual = date("Y-m-d");
    // $fecha_actual = "2021-08-24";

    // Identificar si el empleado esta entrando o saliendo
    // si se devuelve par es SALIDA y si es impar sera ENTRADA.
    // DATE_FORMAT($fecha_actual, '%Y-%m-%d')
    $sql_consulta = "SELECT count(*) FROM asistencia_empleados WHERE empleados_id = ? AND estado = 1 AND cast(fecha as date) = ?;";

    $statement = $db->prepare($sql_consulta);
    $statement->execute([$empleadoId, $fecha_actual]);
    $registros_devueltos = $statement->fetchColumn(0); // $statement->fetchObject();

    if ( intval($registros_devueltos) %2 != 0 ){ // si es impar
        $tipo_asistencia = 2; // PAR = SALIDA
    }else{
        $tipo_asistencia = 1; // IMPAR = ENTRADA
    }

    // --------------------------------------------------
    // Registramos la asistencia ya sabiendo si es entrada o salida
    $sql_insert = "INSERT INTO asistencia_empleados(empleados_id, tipo_asistencia) VALUES (?, ?);";
    $statement = $db->prepare($sql_insert);
    return $statement->execute([$empleadoId, $tipo_asistencia]);
}

function setReaderForEmpleadoPairing($empleadoId)
{
    setReaderStatus(RFID_STATUS_PAIRING);
    setPairingEmpleadoId($empleadoId);
}

function setPairingEmpleadoId($empleadoId)
{
    file_put_contents(PAIRING_EMPLOYEE_ID_FILE, $empleadoId);
}

function getPairingEmpleadoId()
{
    return file_get_contents(PAIRING_EMPLOYEE_ID_FILE);
}

function pairEmpleadoWithRfid($rfidSerial, $empleadoId)
{
    // var_dump("Entra a registrar empleado rfid");
    // var_dump([$rfidSerial, $empleadoId]);
    removeRfidFromEmpleado($rfidSerial);
    $query = "INSERT INTO empleados_rfid(empleados_id, rfid_serial, estado) VALUES (?, ?, ?)";
    $db = getDatabase();
    $statement = $db->prepare($query);

    $estado = 1;

    return $statement->execute([$empleadoId, $rfidSerial, $estado]);
}

function removeRfidFromEmpleado($rfidSerial)
{
    $query = "DELETE FROM empleados_rfid WHERE rfid_serial = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    return $statement->execute([$rfidSerial]);
}

/**
 * Obtiene el empleado que tenga asignado el rfid con el serial indicado.
 */
function getEmpleadoByRfidSerial($rfidSerial)
{
    $query = "SELECT e.id, e.nombre FROM empleados e INNER JOIN empleados_rfid erfid ON erfid.empleados_id = e.id " . 
        " WHERE erfid.rfid_serial = ?";

    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$rfidSerial]);
    return $statement->fetchObject();
}
function getEmpleadoRfidById($empleadoId)
{
    $query = "SELECT rfid_serial FROM empleados_rfid WHERE empleados_id = ?";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$empleadoId]);
    return $statement->fetchObject();
}

function getReaderStatus()
{
    return file_get_contents(RFID_STATUS_FILE);
}

function setReaderStatus($newStatus)
{
    if (!in_array($newStatus, [RFID_STATUS_PAIRING, RFID_STATUS_READING])) {
        return;
    }

    file_put_contents(RFID_STATUS_FILE, $newStatus);
}

function getEmployeesWithAttendanceCount($start, $end)
{
    $query = "select empleados.nombre, 
        sum(case when empleados_entrada.estado = 'asistencia' then 1 else 0 end) as asistencia_count,
        sum(case when empleados_entrada.estado = 'ausencia' then 1 else 0 end) as ausencia_count 
        from empleados_entrada
        inner join empleados on empleados.id = empleados_entrada.id
        where fecha >= ? and fecha <= ?
        group by empleados.id, empleados.nombre;";
    $db = getDatabase();
    $statement = $db->prepare($query);
    $statement->execute([$start, $end]);
    return $statement->fetchAll();
}

function saveAttendanceData($fecha, $empleados)
{
    deleteAttendanceDataByDate($fecha);
    $db = getDatabase();
    $db->beginTransaction();
    $statement = $db->prepare("INSERT INTO empleados_entrada(id, fecha, estado) VALUES (?, ?, ?)");
    foreach ($empleados as $empleado) {
        $statement->execute([$empleado->id, $fecha, $empleado->estado]);
    }
    $db->commit();
    return true;
}

function deleteAttendanceDataByDate($fecha)
{
    $db = getDatabase();
    $statement = $db->prepare("DELETE FROM empleados_entrada WHERE date = ?");
    return $statement->execute([$fecha]);
}
function getAttendanceDataByDate($fecha)
{
    $db = getDatabase();
    $statement = $db->prepare("SELECT id, status FROM empleados_entrada WHERE date = ?");
    $statement->execute([$fecha]);
    return $statement->fetchAll();
}


function deleteEmpleados($id)
{
    $db = getDatabase();
    // $statement = $db->prepare("DELETE FROM empleados WHERE id = ?");

    $statement = $db->prepare("UPDATE empleados SET estado = 2  WHERE id = ?");

    return $statement->execute([$id]);
}

function updateEmpleados($nombre, $telefono, $id, $puesto, $id_usuario)
{
    $db = getDatabase();

    // var_dump($id);
    // die();

    $statement = $db->prepare("UPDATE empleados SET nombre = ?, telefono= ?, puesto_id= ?, usuario_id = ?  WHERE id = ?");
    return $statement->execute([$nombre, $telefono, $puesto, $id_usuario, $id]);
}
function getEmpleadoById($id)
{
    $db = getDatabase();
    $statement = $db->prepare("SELECT id, nombre, telefono, puesto_id, usuario_id FROM empleados WHERE id = ?");
    $statement->execute([$id]);
    return $statement->fetchObject();
}

function saveEmpleados($nombre, $puesto, $telefono, $id_usuario)
{
    $db = getDatabase();

    $estado = 1;

    $statement = $db->prepare("INSERT INTO empleados(nombre, puesto_id, telefono, usuario_id, estado) VALUES (?, ?, ?, ?, ?)");
    return $statement->execute([$nombre, $puesto, $telefono, $id_usuario, $estado]);
}

function getEmpleados()
{
    $db = getDatabase();

    $sql = "SELECT 
        e.id as id_empleado, 
        e.nombre, 
        e.telefono, 
        e.estado, 
        e.usuario_id, p.id as id_puesto, 
        p.puesto 
        FROM empleados e 
        inner join puesto p on p.id = e.puesto_id 
        where e.estado =1;";


    $statement = $db->query($sql);
    return $statement->fetchAll();
}


function obtener_historial()
{
    $db = getDatabase();

    $sql = "SELECT 
        a.id as id_registro,
        a.fecha,
        a.estado,
        a.tipo_asistencia,
        e.id as codigo_empleado,
        e.nombre
        FROM asistencia_empleados a
        inner join empleados e on e.id = a.empleados_id
        where a.estado =1;";


    $statement = $db->query($sql);
    return $statement->fetchAll();
}

function guardarAsistencia($nombre_empleado, $tipo_asistencia, $fecha_hora){
    $db = getDatabase();

    $statement = $db->prepare("INSERT INTO asistencia_empleados(empleados_id, tipo_asistencia, fecha) VALUES (?, ?, ?)");
    return $statement->execute([$nombre_empleado, $tipo_asistencia, $fecha_hora]);

}
function deleteHistorial($id)
{
    $db = getDatabase();
    // $statement = $db->prepare("DELETE FROM empleados WHERE id = ?");

    $statement = $db->prepare("UPDATE asistencia_empleados SET estado = 2  WHERE id = ?");

    return $statement->execute([$id]);
}

function getVarFromEnvironmentVariables($key)
{
    if (defined("_ENV_CACHE")) {
        $vars = _ENV_CACHE;
    } else {
        $file = "env.php";
        if (!file_exists($file)) {
            throw new Exception("The environment file ($file) does not exists. Please create it");
        }
        $vars = parse_ini_file($file);
        define("_ENV_CACHE", $vars);
    }
    if (isset($vars[$key])) {
        return $vars[$key];
    } else {
        throw new Exception("The specified key (" . $key . ") does not exist in the environment file");
    }
}

function getDatabase()
{
    $password = getVarFromEnvironmentVariables("MYSQL_PASSWORD");
    $user = getVarFromEnvironmentVariables("MYSQL_USER");
    $dbName = getVarFromEnvironmentVariables("MYSQL_DATABASE_NAME");
    $database = new PDO('mysql:host=localhost:8111;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}
