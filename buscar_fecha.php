<?php
    include('functions.php');

    // $buscador = strtoupper($_GET["buscar"]);
    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

    // echo $fechaInicial;
    // die();

    $db = getDatabase();
    if ( $fechaInicial != "" && $fechaFinal != ""){
        $sql = "SELECT 
        a.id as id_registro,
        a.fecha,
        a.estado,
        a.tipo_asistencia,
        e.id as codigo_empleado,
        e.nombre
        FROM asistencia_empleados a
        inner join empleados e on e.id = a.empleados_id
        where a.estado = 1 AND DATE(a.fecha) BETWEEN '$fechaInicial' AND '$fechaFinal';";
    }else if ( $fechaInicial != "" && $fechaFinal == ""){
        $sql = "SELECT 
        a.id as id_registro,
        a.fecha,
        a.estado,
        a.tipo_asistencia,
        e.id as codigo_empleado,
        e.nombre
        FROM asistencia_empleados a
        inner join empleados e on e.id = a.empleados_id
        where a.estado = 1 AND DATE(a.fecha) >= '$fechaInicial';";
    }else if ( $fechaInicial == "" && $fechaFinal != ""){
        $sql = "SELECT 
        a.id as id_registro,
        a.fecha,
        a.estado,
        a.tipo_asistencia,
        e.id as codigo_empleado,
        e.nombre
        FROM asistencia_empleados a
        inner join empleados e on e.id = a.empleados_id
        where a.estado = 1 AND DATE(a.fecha) <= '$fechaFinal';";
    }else{
        $sql = "SELECT 
        a.id as id_registro,
        a.fecha,
        a.estado,
        a.tipo_asistencia,
        e.id as codigo_empleado,
        e.nombre
        FROM asistencia_empleados a
        inner join empleados e on e.id = a.empleados_id
        where a.estado = 1;";
    }
    $statement = $db->query($sql);

    // var_dump($sql);
    // die();
    
    echo json_encode($statement->fetchAll());
