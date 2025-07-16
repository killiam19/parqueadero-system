<?php
require_once 'config.php';
require_once 'mail_reserva.php';
session_start();

// Redirigir a login si no está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Establecer conexión a la base de datos
$pdo = conectarDB();

// Obtener todos los usuarios para el formulario (solo si es admin)
$usuarios = [];
if ($_SESSION['usuario_rol'] == 'admin') {
    $usuarios = $pdo->query("SELECT id, nombre FROM usuarios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
}

// Procesar formulario de reserva
if (isset($_POST['action']) && $_POST['action'] == 'reservar') {
    // Para usuarios normales, usar su propio ID; para admin, permitir seleccionar
    if ($_SESSION['usuario_rol'] == 'admin' && isset($_POST['usuario_id'])) {
        $usuario_id = $_POST['usuario_id'];
    } else {
        $usuario_id = $_SESSION['usuario_id'];
    }
    
    $fecha_reserva = $_POST['fecha_reserva'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $placa_vehiculo = strtoupper($_POST['placa_vehiculo']);
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    
    $mensaje = '';
    $tipo_mensaje = '';
    
    $manana = date('Y-m-d', strtotime('+1 day'));
    // Validaciones
    if (empty($fecha_reserva) || empty($hora_inicio) || empty($hora_fin) || empty($placa_vehiculo)) {
        $mensaje = 'Todos los campos son obligatorios';
        $tipo_mensaje = 'error';
    } elseif ($fecha_reserva != $manana) {
        $mensaje = 'Solo puedes agendar cupos para el día siguiente (' . date('d/m/Y', strtotime($manana)) . ')';
        $tipo_mensaje = 'error';
    } elseif ($hora_inicio >= $hora_fin) {
        $mensaje = 'La hora de inicio debe ser anterior a la hora de fin';
        $tipo_mensaje = 'error';
    } elseif (usuarioTieneReserva($usuario_id, $fecha_reserva)) {
        $mensaje = 'Ya tienes una reserva para esta fecha';
        $tipo_mensaje = 'error';
    } elseif (getCuposDisponibles($fecha_reserva) <= 0) {
        $mensaje = 'No hay cupos disponibles para el día siguiente. Intenta más tarde o consulta con el administrador.';
        $tipo_mensaje = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, fecha_reserva, hora_inicio, hora_fin, placa_vehiculo, tipo_vehiculo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $fecha_reserva, $hora_inicio, $hora_fin, $placa_vehiculo, $tipo_vehiculo]);
            $mensaje = 'Reserva creada exitosamente';
            $tipo_mensaje = 'success';

            // Obtener datos del usuario
            $stmt = $pdo->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
            $stmt->execute([$usuario_id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Llama a la función de correo
            enviarCorreoReserva(
                $usuario['email'],
                $usuario['nombre'],
                [
                    'fecha' => $fecha_reserva,
                    'hora_inicio' => $hora_inicio,
                    'hora_fin' => $hora_fin,
                    'placa' => $placa_vehiculo,
                    'tipo_vehiculo' => $tipo_vehiculo
                ]
            );

        } catch (Exception $e) {
            $mensaje = 'Error al crear la reserva: ' . $e->getMessage();
            $tipo_mensaje = 'error';
        }
    }
}

// Obtener reservas del usuario actual para mañana (o todas si es admin)
$manana = date('Y-m-d', strtotime('+1 day'));
if ($_SESSION['usuario_rol'] == 'admin') {
    $reservas_manana = $pdo->prepare("
        SELECT r.*, u.nombre, u.email 
        FROM reservas r 
        JOIN usuarios u ON r.usuario_id = u.id 
        WHERE r.fecha_reserva = ? 
        AND r.estado = 'activa'
        ORDER BY r.hora_inicio
    ");
    $reservas_manana->execute([$manana]);
} else {
    $reservas_manana = $pdo->prepare("
        SELECT r.*, u.nombre, u.email 
        FROM reservas r 
        JOIN usuarios u ON r.usuario_id = u.id 
        WHERE r.fecha_reserva = ? 
        AND r.estado = 'activa'
        AND r.usuario_id = ?
        ORDER BY r.hora_inicio
    ");
    $reservas_manana->execute([$manana, $_SESSION['usuario_id']]);
}
$reservas_manana = $reservas_manana->fetchAll(PDO::FETCH_ASSOC);

$cupos_disponibles_manana = getCuposDisponibles($manana);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Agendamiento - Parqueadero de 3Shape</title>
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
            max-width: 1200px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-content {
            text-align: left;
        }
        
        .header-actions {
            display: flex;
            gap: 10px;
        }
        
        .header-actions a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid white;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .header-actions a:hover {
            background-color: white;
            color: #2c3e50;
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
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select, textarea {
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
        }
        
        button:hover {
            background-color: #2980b9;
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
        
        .cupos-info {
            background-color: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .reserva-item {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        
        .reserva-item h4 {
            margin-bottom: 8px;
            color: #2c3e50;
        }
        
        .reserva-item p {
            margin-bottom: 5px;
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
            
            .header {
                flex-direction: column;
                gap: 15px;
            }
            
            .header-actions {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1>🚗 Sistema de Agendamiento de Parqueadero de 3Shape</h1>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
            </div>
            <div class="header-actions">
                <a href="cambiar_password.php">🔒 Cambiar Contraseña</a>
                <a href="login.php?logout=1">🚪 Cerrar Sesión</a>
            </div>
        </div>
        
        <div class="nav">
            <a href="mis_reservas.php">📋 Mis Reservas</a>
            <?php if ($_SESSION['usuario_rol'] == 'admin'): ?>
                <a href="usuarios.php">👥 Usuarios</a>
                <a href="admin.php">⚙️ Administración</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <div class="grid">
            <div>
                <div class="card">
                    <h2>📅 Nueva Reserva</h2>
                    
                    <div class="cupos-info">
                        <strong>Cupos disponibles para mañana (<?php echo date('d/m/Y', strtotime('+1 day')); ?>): <?php echo $cupos_disponibles_manana; ?>/10</strong>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="reservar">
                        
                        <?php if ($_SESSION['usuario_rol'] == 'admin'): ?>
                            <div class="form-group">
                                <label for="usuario_id">Usuario:</label>
                                <select name="usuario_id" id="usuario_id" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="fecha_reserva">Fecha de Reserva:</label>
                            <input type="date" name="fecha_reserva" id="fecha_reserva" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" max="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="hora_inicio">Hora de Inicio:</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="hora_fin">Hora de Fin:</label>
                            <input type="time" name="hora_fin" id="hora_fin" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="placa_vehiculo">Placa del Vehículo:</label>
                            <input type="text" name="placa_vehiculo" id="placa_vehiculo" placeholder="ABC123" maxlength="10" required>
                        </div>

                        <div class="form-group">
                            <label for="tipo_vehiculo">Tipo de Vehículo:</label>
                            <select name="tipo_vehiculo" id="tipo_vehiculo" required>
                                <option value="carro">Carro</option>
                                <option value="moto">Moto</option>
                            </select>
                        </div>
                        
                        <button type="submit">Reservar Cupo</button>
                    </form>
                </div>
            </div>
            
            <div>
                <div class="card">
                    <h2>📋 Reservas para Mañana</h2>
                    
                    <?php if (empty($reservas_manana)): ?>
                        <p><?php echo ($_SESSION['usuario_rol'] == 'admin') ? 'No hay reservas para mañana.' : 'No tienes reservas para mañana.'; ?></p>
                    <?php else: ?>
                        <?php foreach ($reservas_manana as $reserva): ?>
                            <div class="reserva-item">
                                <h4><?php echo htmlspecialchars($reserva['nombre']); ?></h4>
                                <p><strong>Horario:</strong> <?php echo date('H:i', strtotime($reserva['hora_inicio'])); ?> - <?php echo date('H:i', strtotime($reserva['hora_fin'])); ?></p>
                                <p><strong>Placa:</strong> <?php echo htmlspecialchars($reserva['placa_vehiculo']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($reserva['email']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Actualizar cupos disponibles cuando cambie la fecha
        document.getElementById('fecha_reserva').addEventListener('change', function() {
            const fecha = this.value;
            if (fecha) {
                fetch('get-cupos.php?fecha=' + fecha)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.cupos-info').innerHTML = 
                            '<strong>Cupos disponibles para ' + fecha + ': ' + data.disponibles + '/10</strong>';
                    });
            }
        });
        // Deshabilitar el input si solo hay una fecha posible
        document.getElementById('fecha_reserva').readOnly = true;
    </script>
</body>
</html>