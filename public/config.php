<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'parqueadero_system');
define('DB_USER', 'root');
define('DB_PASS', '1213123Shape');

function conectarDB() {
    try {
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function getCuposDisponibles($fecha) {
    $pdo = conectarDB();
    
    // Obtener total de cupos configurados
    $config = $pdo->query("SELECT total_cupos FROM configuracion ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $total_cupos = $config['total_cupos'] ?? 10;
    
    // Contar reservas activas para la fecha
    $stmt = $pdo->prepare("SELECT COUNT(*) as ocupados FROM reservas WHERE fecha_reserva = ? AND estado = 'activa'");
    $stmt->execute([$fecha]);
    $ocupados = $stmt->fetch(PDO::FETCH_ASSOC)['ocupados'];
    
    return $total_cupos - $ocupados;
}

function usuarioTieneReserva($usuario_id, $fecha) {
    $pdo = conectarDB();
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reservas WHERE usuario_id = ? AND fecha_reserva = ? AND estado = 'activa'");
    $stmt->execute([$usuario_id, $fecha]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
}

// Función para verificar si el usuario está logueado
function verificarAutenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Función para verificar si el usuario es admin
function verificarAdmin() {
    verificarAutenticacion();
    if ($_SESSION['usuario_rol'] != 'admin') {
        header("Location: index.php");
        exit();
    }
}