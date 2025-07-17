<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
    $cupos_disponibles = getCuposDisponibles($fecha);
    // Obtener el total de cupos dinámicamente
    $pdo = conectarDB();
    $config = $pdo->query("SELECT total_cupos FROM configuracion ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $total = $config['total_cupos'] ?? 10;
    echo json_encode([
        'fecha' => $fecha,
        'disponibles' => $cupos_disponibles,
        'total' => $total
    ]);
} else {
    echo json_encode(['error' => 'Fecha no proporcionada']);
}
?>