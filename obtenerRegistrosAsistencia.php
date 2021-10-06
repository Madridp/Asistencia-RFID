<?php
    include('functions.php');
    date_default_timezone_set('America/Guatemala'); 

    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

    $db = getDatabase();

    /// Paso 1. Obtener todos los empleados registrados (activos).
    $sql_obtener_empleados = "SELECT * from empleados where estado = 1";
    $statement_empleados = $db->query($sql_obtener_empleados);

    $empleados = $statement_empleados->fetchAll();

    if ( count($empleados) > 0){
        // var_dump($empleados);
        // die();

        // Paso 2. Obtener una lista con el rango de las fechas indicadas.
        // ***************************************
        // Domingo = 0
        // Lunes = 1
        // Martes = 2
        // Miercoles = 3
        // Jueves = 4
        // Viernes = 5
        // Sabado = 6
        // $timestamp = strtotime('2021-09-19');
        // $dia = date('w', $timestamp);
        // var_dump($dia);
        // die();
        $dias_intervalos = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($fechaFinal);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($fechaInicial), $interval, $realEnd);

        foreach($period as $date) { 
            $fecha_iterativa_YMD = $date->format('Y-m-d');

            $timestamp = strtotime($fecha_iterativa_YMD);
            $dia = date('w', $timestamp);
            if ( $dia != 0 && $dia != 6 ){ // si no es domingo ni sabado
                $dias_intervalos[] = $fecha_iterativa_YMD;
            }
        }
        // var_dump($dias_intervalos);
        // die();
        // ***************************************

        // Paso 3. Obtener las asistencias
        foreach($empleados as $empleado){
            $contador_asistencia = 0;
            $contador_ausencia = 0;

            foreach($dias_intervalos as $dia){
                $sql_obtener_asistencias = "SELECT count(*) FROM asistencia_empleados WHERE empleados_id = '". $empleado->id . "' AND  date(fecha) = '$dia' AND estado = 1;";
                
                $statement_busqueda_asistencia = $db->query($sql_obtener_asistencias);
                $count = $statement_busqueda_asistencia->fetchColumn();

                if ( $count > 0 ){
                    $contador_asistencia++;
                }else{
                    $contador_ausencia++;
                }
            }

            $empleado->contador_asistencia = $contador_asistencia;
            $empleado->contador_ausencia = $contador_ausencia;
        }
    }

    // var_dump($empleados);
    // die();

    echo json_encode($empleados);
    die();