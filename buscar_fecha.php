<?php
    include('functions.php');

    // $buscador = strtoupper($_GET["buscar"]);
    $buscador = $_GET["buscarFecha"];

    // echo $buscador;
    // die();

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
    where a.estado =1 AND a.fecha LIKE '%$buscador%';";
    //  where a.estado =1 AND a.fecha = '$buscador';";
    $statement = $db->query($sql);
    
    echo json_encode($statement->fetchAll());
