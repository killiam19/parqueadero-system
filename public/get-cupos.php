<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
    $cupos_disponibles = getCuposDisponibles($fecha);
    
    echo json_encode([
        'fecha' => $fecha,
        'disponibles' => $cupos_disponibles,
        'total' => 10
    ]);
} else {
    echo json_encode(['error' => 'Fecha no proporcionada']);
}
?>