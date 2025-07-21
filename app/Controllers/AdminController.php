<?php
require_once 'config.php';
session_start();
verificarAdmin();

// Procesar actualización de configuración
if (isset($_POST['action']) && $_POST['action'] == 'actualizar_config') {
    $total_cupos = $_POST['total_cupos'];
    
    if ($total_cupos < 1 || $total_cupos > 100) {
        $mensaje = 'El número de cupos debe estar entre 1 y 100';
        $tipo_mensaje = 'error';
    } else {
        try {
            $pdo = conectarDB();
            $stmt = $pdo->prepare("UPDATE configuracion SET total_cupos = ? WHERE id = 1");
            $stmt->execute([$total_cupos]);
            $mensaje = 'Configuración actualizada exitosamente';
            $tipo_mensaje = 'success';
        } catch (Exception $e) {
            $mensaje = 'Error al actualizar configuración: ' . $e->getMessage();
            $tipo_mensaje = 'error';
        }
    }
}

// Procesar limpieza de reservas antiguas
if (isset($_POST['action']) && $_POST['action'] == 'limpiar_reservas') {
    try {
        $pdo = conectarDB();
        $stmt = $pdo->prepare("UPDATE reservas SET estado = 'completada' WHERE fecha_reserva < ? AND estado = 'activa'");
        $stmt->execute([date('Y-m-d')]);
        $filas_afectadas = $stmt->rowCount();
        $mensaje = "Se marcaron como completadas $filas_afectadas reservas antiguas";
        $tipo_mensaje = 'success';
    } catch (Exception $e) {
        $mensaje = 'Error al limpiar reservas: ' . $e->getMessage();
        $tipo_mensaje = 'error';
    }
}

// Obtener estadísticas
$pdo = conectarDB();

// Configuración actual
$config = $pdo->query("SELECT * FROM configuracion ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Estadísticas generales
$stats = $pdo->query("
    SELECT 
        COUNT(DISTINCT u.id) as total_usuarios,
        COUNT(r.id) as total_reservas,
        COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas,
        COUNT(CASE WHEN r.estado = 'completada' THEN 1 END) as reservas_completadas,
        COUNT(CASE WHEN r.estado = 'cancelada' THEN 1 END) as reservas_canceladas
    FROM usuarios u
    LEFT JOIN reservas r ON u.id = r.usuario_id
")->fetch(PDO::FETCH_ASSOC);

// Reservas por fecha (últimos 7 días)
$reservas_recientes = $pdo->query("
    SELECT 
        DATE(fecha_reserva) as fecha,
        COUNT(*) as total_reservas,
        COUNT(CASE WHEN estado = 'activa' THEN 1 END) as activas,
        COUNT(CASE WHEN estado = 'completada' THEN 1 END) as completadas,
        COUNT(CASE WHEN estado = 'cancelada' THEN 1 END) as canceladas
    FROM reservas 
    WHERE fecha_reserva >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(fecha_reserva)
    ORDER BY fecha DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Usuarios más activos
$usuarios_activos = $pdo->query("
    SELECT 
        u.nombre, 
        u.email,
        COUNT(r.id) as total_reservas,
        COUNT(CASE WHEN r.estado = 'activa' THEN 1 END) as reservas_activas
    FROM usuarios u
    LEFT JOIN reservas r ON u.id = r.usuario_id
    GROUP BY u.id, u.nombre, u.email
    HAVING total_reservas > 0
    ORDER BY total_reservas DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Cupos disponibles hoy
$cupos_manana = getCuposDisponibles(date('Y-m-d', strtotime('+1 day')));
?>
