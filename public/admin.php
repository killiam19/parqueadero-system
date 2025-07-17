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
    ORDER BY fecha_reserva DESC
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Parqueadero</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        
        .nav {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nav a {
            color: #3498db;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px 20px;
            border: 1px solid #3498db;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav a:hover {
            background-color: #3498db;
            color: white;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .stat-card h3 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }
        
        .stat-card.danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .btn-warning {
            background-color: #f39c12;
        }
        
        .btn-warning:hover {
            background-color: #e67e22;
        }
        
        .mensaje {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .mensaje.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .table tr:hover {
            background-color: #f8f9fa;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Panel de Administración</h1>
            <p>Gestiona la configuración y monitorea el sistema de parqueadero</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Inicio</a>
            <a href="mis_reservas.php">📋 Reservas</a>
            <a href="usuarios.php">👥 Usuarios</a>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <!-- Estadísticas principales -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $stats['total_usuarios']; ?></h3>
                <p>Usuarios Registrados</p>
            </div>
            <div class="stat-card success">
                <h3><?php echo $cupos_manana; ?></h3>
                <p>Cupos Disponibles para Mañana</p>
            </div>
            <div class="stat-card warning">
                <h3><?php echo $stats['reservas_activas']; ?></h3>
                <p>Reservas Activas</p>
            </div>
            <div class="stat-card danger">
                <h3><?php echo $stats['total_reservas']; ?></h3>
                <p>Total de Reservas</p>
            </div>
        </div>
        
        <div class="grid">
            <div>
                <div class="card">
                    <h2>🔧 Configuración del Sistema</h2>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="actualizar_config">
                        
                        <div class="form-group">
                            <label for="total_cupos">Total de Cupos de Parqueadero:</label>
                            <input type="number" name="total_cupos" id="total_cupos" value="<?php echo $config['total_cupos']; ?>" min="1" max="100" required>
                        </div>
                        
                        <button type="submit">Actualizar Configuración</button>
                    </form>
                    
                    <hr style="margin: 20px 0;">
                    
                    <h3>🧹 Mantenimiento</h3>
                    <p>Marca como completadas las reservas de días anteriores:</p>
                    <form method="POST" action="" style="margin-top: 10px;">
                        <input type="hidden" name="action" value="limpiar_reservas">
                        <button type="submit" class="btn-warning" onclick="return confirm('¿Estás seguro de que quieres marcar como completadas todas las reservas de días anteriores?')">
                            Limpiar Reservas Antiguas
                        </button>
                    </form>
                </div>
                
                <div class="card">
                    <h2>👑 Usuarios Más Activos</h2>
                    
                    <?php if (empty($usuarios_activos)): ?>
                        <p>No hay usuarios con reservas.</p>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Total Reservas</th>
                                    <th>Activas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios_activos as $usuario): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td><?php echo $usuario['total_reservas']; ?></td>
                                        <td><?php echo $usuario['reservas_activas']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <div class="card">
                    <h2>📊 Estadísticas Detalladas</h2>
                    
                    <div style="margin-bottom: 20px;">
                        <h4>Resumen de Reservas</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                <strong>Total de Reservas:</strong> <?php echo $stats['total_reservas']; ?>
                            </li>
                            <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                <strong>Reservas Activas:</strong> <?php echo $stats['reservas_activas']; ?>
                            </li>
                            <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                <strong>Reservas Completadas:</strong> <?php echo $stats['reservas_completadas']; ?>
                            </li>
                            <li style="padding: 5px 0;">
                                <strong>Reservas Canceladas:</strong> <?php echo $stats['reservas_canceladas']; ?>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4>Configuración Actual</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                <strong>Total de Cupos:</strong> <?php echo $config['total_cupos']; ?>
                            </li>
                            <li style="padding: 5px 0; border-bottom: 1px solid #eee;">
                                <strong>Cupos Ocupados Mañana:</strong> <?php echo ($config['total_cupos'] - $cupos_manana); ?>
                            </li>
                            <li style="padding: 5px 0;">
                                <strong>Última Actualización:</strong> <?php echo date('d/m/Y H:i', strtotime($config['fecha_actualizacion'])); ?>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card">
                    <h2>📅 Reservas Recientes (Últimos 7 días)</h2>
                    
                    <?php if (empty($reservas_recientes)): ?>
                        <p>No hay reservas en los últimos 7 días.</p>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Activas</th>
                                    <th>Completadas</th>
                                    <th>Canceladas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservas_recientes as $reserva): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?></td>
                                        <td><?php echo $reserva['total_reservas']; ?></td>
                                        <td><?php echo $reserva['activas']; ?></td>
                                        <td><?php echo $reserva['completadas']; ?></td>
                                        <td><?php echo $reserva['canceladas']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/../resources/partials/footer.php'; ?>
</body>
</html>