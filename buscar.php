<?php
    include('functions.php');

    // $buscador = strtoupper($_GET["buscar"]);
    $buscador = $_GET["buscar"];

    $db = getDatabase();
    $sql = "SELECT e.id as id_empleado, e.nombre, e.telefono, p.puesto as puesto_nombre FROM empleados e INNER JOIN puesto p ON p.id = e.puesto_id WHERE e.estado = 1 AND e.nombre LIKE '%$buscador%';";
    $statement = $db->query($sql);
    
    echo json_encode($statement->fetchAll());
