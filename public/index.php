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

// Obtener total de cupos desde la configuración
$config = $pdo->query("SELECT total_cupos FROM configuracion ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$total_cupos = $config['total_cupos'] ?? 10;

// Obtener espacios ocupados para mañana
$manana = date('Y-m-d', strtotime('+1 day'));
$stmt = $pdo->prepare("SELECT numero_espacio, usuario_id FROM reservas WHERE fecha_reserva = ? AND estado = 'activa'");
$stmt->execute([$manana]);
$espacios_ocupados = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ocupados_map = [];
foreach ($espacios_ocupados as $esp) {
    $ocupados_map[$esp['numero_espacio']] = $esp['usuario_id'];
}

// Definición de bloques de motos y sus cupos
$moto_blocks = [
    // Primera fila (grandes)
    ['id' => 476, 'label' => '476', 'max' => 6, 'row' => 1, 'col' => 1],
    ['id' => 475, 'label' => '475', 'max' => 6, 'row' => 1, 'col' => 2],
    ['id' => 474, 'label' => '474', 'max' => 6, 'row' => 1, 'col' => 3],
    // Segunda fila (pequeños)
    ['id' => '476a', 'label' => '1', 'max' => 1, 'row' => 2, 'col' => 1, 'parent' => 476],
    ['id' => '475a', 'label' => '1', 'max' => 1, 'row' => 2, 'col' => 2, 'parent' => 475],
    ['id' => '474a', 'label' => '1', 'max' => 1, 'row' => 2, 'col' => 3, 'parent' => 474],
    ['id' => '474b', 'label' => '1', 'max' => 1, 'row' => 3, 'col' => 3, 'parent' => 474],
    // Tercera fila (bloque 441)
    ['id' => 441, 'label' => '441', 'max' => 4, 'row' => 4, 'col' => 3],
];
// Obtener reservas activas de motos para mañana
$moto_reservas = $pdo->prepare("SELECT numero_espacio, COUNT(*) as ocupados FROM reservas WHERE fecha_reserva = ? AND estado = 'activa' AND tipo_vehiculo = 'moto' GROUP BY numero_espacio");
$moto_reservas->execute([$manana]);
$moto_ocupados = [];
foreach ($moto_reservas as $row) {
    $moto_ocupados[$row['numero_espacio']] = $row['ocupados'];
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
    $numero_espacio = isset($_POST['numero_espacio']) ? $_POST['numero_espacio'] : null;
    
    $mensaje = '';
    $tipo_mensaje = '';
    
    $manana = date('Y-m-d', strtotime('+1 day'));
    // Validaciones
    if (empty($fecha_reserva) || empty($hora_inicio) || empty($hora_fin) || empty($placa_vehiculo) || empty($numero_espacio)) {
        $mensaje = 'Todos los campos son obligatorios, incluido el espacio de parqueadero';
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
    } elseif (isset($ocupados_map[$numero_espacio])) {
        $mensaje = 'El espacio seleccionado ya está ocupado, elige otro.';
        $tipo_mensaje = 'error';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, numero_espacio, fecha_reserva, hora_inicio, hora_fin, placa_vehiculo, tipo_vehiculo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $numero_espacio, $fecha_reserva, $hora_inicio, $hora_fin, $placa_vehiculo, $tipo_vehiculo]);
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
    <link rel="shortcut icon" href="assets/images/3shape-intraoral-logo.png" type="image/x-icon">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-papmQv7Qn1v8Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q6Qw1Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #222;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            width: 100vw;
            min-width: 100vw;
            margin-left: 0;
            margin-right: 0;
            background-color: #223142;
            color: #c7264e;
            padding: 0;
            border-radius: 0;
            border: none;
            box-shadow: 0 2px 8px rgba(199,38,78,0.04);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            left: 50%;
            right: 50%;
            transform: translateX(-50%);
        }
        
        .header-inner {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #223142;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #223142;
        }
        
        .header-content {
            text-align: left;
        }
        
        .header-actions {
            display: flex;
            gap: 10px;
        }
        
        .header-actions a {
            color: #c7264e;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid #c7264e;
            border-radius: 5px;
            transition: all 0.3s;
            background: #fff;
        }
        
        .header-actions a:hover {
            background-color: #c7264e;
            color: #fff;
        }
        
        .nav {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nav a {
            color: #c7264e;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px 20px;
            border: 1px solid #c7264e;
            border-radius: 5px;
            transition: all 0.3s;
            background: #fff;
        }
        
        .nav a:hover {
            background-color: #c7264e;
            color: #fff;
        }
        
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(199,38,78,0.07);
            border: 1px solid #ececec;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #c7264e;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 16px;
            background: #faf9fa;
            color: #222;
        }
        
        button {
            background-color: #c7264e;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #a81d3e;
        }
        
        .mensaje {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .mensaje.success {
            background-color: #f6e6ea;
            color: #c7264e;
            border: 1px solid #c7264e;
        }
        
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .cupos-info {
            background-color: #f6e6ea;
            border-left: 4px solid #c7264e;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .reserva-item {
            background-color: #faf9fa;
            border-left: 4px solid #c7264e;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        
        .reserva-item h4 {
            margin-bottom: 8px;
            color: #c7264e;
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
        .mapa-titulo {
            display: flex;
            align-items: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 18px;
            gap: 10px;
        }
        .mapa-leyenda {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .mapa-leyenda span {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #c7264e;
        }
        .mapa-espacios-bg {
            background: #f8fafc;
            border-radius: 18px;
            padding: 28px 16px 18px 16px;
            margin-bottom: 18px;
            display: flex;
            justify-content: center;
            width: 100%;
            min-height: 120px;
            box-sizing: border-box;
        }
        #mapa-espacios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
            gap: 18px;
            justify-items: center;
            align-items: center;
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }
        #mapa-espacios::-webkit-scrollbar {
            height: 10px;
        }
        #mapa-espacios::-webkit-scrollbar-thumb {
            background: #2563eb;
            border-radius: 6px;
        }
        #mapa-espacios::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .espacio-btn {
            min-width: 60px;
            max-width: 80px;
            width: 70px;
            height: 80px;
            border-radius: 14px;
            border: none;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(44,62,80,0.07);
            transition: box-shadow 0.2s, transform 0.2s, background 0.2s;
            margin-bottom: 0;
            cursor: pointer;
            outline: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .espacio-btn.disponible {
            background: #22c55e;
            color: #fff;
        }
        .espacio-btn.ocupado {
            background: #b0b0b0;
            color: #fff;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .espacio-btn.seleccionado {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 0 0 4px #2563eb33;
            border: 2px solid #2563eb;
        }
        .espacio-btn .icono-auto {
            font-size: 1.5rem;
            margin-bottom: 4px;
        }
        @media (max-width: 900px) {
            #mapa-espacios {
                grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
                gap: 10px;
                max-width: 100vw;
            }
            .espacio-btn {
                min-width: 40px;
                max-width: 60px;
                width: 50px;
                height: 60px;
                font-size: 13px;
            }
            .espacio-btn .icono-auto {
                font-size: 1.1rem;
            }
        }
        .form-reserva-oculto {
            display: none;
        }
        .form-reserva-visible {
            display: block;
            animation: fadeIn 0.4s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: none; }
        }
        #mapa-espacios-carro, #mapa-espacios-moto {
            display: flex;
            gap: 18px;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            width: 100%;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #2563eb #f8fafc;
        }
        #mapa-espacios-carro::-webkit-scrollbar, #mapa-espacios-moto::-webkit-scrollbar {
            height: 10px;
        }
        #mapa-espacios-carro::-webkit-scrollbar-thumb, #mapa-espacios-moto::-webkit-scrollbar-thumb {
            background: #2563eb;
            border-radius: 6px;
        }
        #mapa-espacios-carro::-webkit-scrollbar-track, #mapa-espacios-moto::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        #mapa-motos-grid {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-auto-rows: 100px;
            gap: 18px;
            justify-content: center;
            align-items: center;
            margin-bottom: 12px;
            min-height: 420px;
        }
        @media (max-width: 600px) {
            #mapa-motos-grid {
                grid-template-columns: repeat(3, 60px);
                grid-auto-rows: 60px;
                gap: 8px;
                min-height: 250px;
            }
        }
        .tooltip-cupos {
            visibility: hidden;
            opacity: 0;
            background: #222;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 6px 12px;
            position: absolute;
            z-index: 10;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
            pointer-events: none;
            transition: opacity 0.2s;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }
        .espacio-btn:hover .tooltip-cupos,
        .espacio-btn:focus .tooltip-cupos {
            visibility: visible;
            opacity: 1;
        }
        @media (max-width: 700px) {
            .tooltip-cupos {
                display: none;
            }
            #info-cupos-movil {
                display: block;
                margin: 10px auto 0 auto;
                text-align: center;
                font-size: 15px;
                color: #2563eb;
                background: #f1f5fa;
                border-radius: 8px;
                padding: 7px 12px;
                max-width: 300px;
            }
        }
        @media (min-width: 701px) {
            #info-cupos-movil {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-inner">
            <div class="header-content">
                <h1>🚗 Sistema de Agendamiento de Parqueadero de 3Shape <img src="assets/images/3shape-intraoral-logo.png" alt="" width="75" height="75"></h1>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
            </div>
            <div class="header-actions">
                <a href="cambiar_password.php">🔒 Cambiar Contraseña</a>
                <a href="login.php?logout=1">🚪 Cerrar Sesión</a>
            </div>
        </div>
    </div>
    <div class="container">
        
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
                <div class="card" style="margin-bottom: 32px; width: 100%; max-width: 100vw;">
                    <div style="margin-bottom: 18px;">
                        <label style="font-weight:bold; font-size:1.1rem; margin-right: 18px;">Tipo de Vehículo:</label>
                        <label style="margin-right: 12px;"><input type="radio" name="tipo_vehiculo_selector" value="carro" checked> Carro</label>
                        <label><input type="radio" name="tipo_vehiculo_selector" value="moto"> Moto</label>
                    </div>
                    <div id="mapa-carro" style="display:block;">
                        <div class="mapa-titulo" style="justify-content: flex-start;">
                            <span style="color:#2563eb;font-size:2.2rem;">&#128205;</span>
                            Mapa de Espacios de Parqueadero (Carros)
                        </div>
                        <div class="mapa-leyenda" style="justify-content: flex-start;">
                            <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#b0b0b0;border-radius:4px;"></span> Ocupado</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Seleccionado</span>
                        </div>
                        <div class="mapa-espacios-bg">
                            <div id="mapa-espacios-carro">
                                <?php for ($i = 281; $i >= 270; $i--): ?>
                                    <?php
                                        $estado = 'disponible';
                                        if (isset($ocupados_map[$i])) {
                                            if ($ocupados_map[$i] == $_SESSION['usuario_id']) {
                                                $estado = 'seleccionado';
                                            } else {
                                                $estado = 'ocupado';
                                            }
                                        }
                                    ?>
                                    <button type="button" class="espacio-btn <?php echo $estado; ?>" data-espacio="<?php echo $i; ?>" data-tipo="carro" <?php echo ($estado=='ocupado'?'disabled':''); ?>
                                    <?php if($estado=='seleccionado'): ?> style="background:#2563eb;color:#fff;border:2px solid #2563eb;box-shadow:0 0 0 4px #2563eb33;"<?php endif; ?>
                                    >
                                        <span class="icono-auto"><i class="fa-solid fa-car-side"></i></span>
                                        <span><?php echo $i; ?></span>
                                    </button>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div id="ayuda-espacio-carro" style="font-size: 13px; color: #888; margin-top: 3px; text-align:center;">Selecciona un espacio disponible (verde).</div>
                    </div>
                    <div id="mapa-moto" style="display:none;">
                        <div class="mapa-titulo" style="justify-content: flex-start;">
                            <span style="color:#2563eb;font-size:2.2rem;">&#128205;</span>
                            Mapa de Espacios de Parqueadero (Motos)
                        </div>
                        <div class="mapa-leyenda" style="justify-content: flex-start;">
                            <span><span style="display:inline-block;width:18px;height:18px;background:#22c55e;border-radius:4px;"></span> Disponible</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#b0b0b0;border-radius:4px;"></span> Ocupado</span>
                            <span><span style="display:inline-block;width:18px;height:18px;background:#f6e6ea;border-radius:4px;"></span> Seleccionado</span>
                        </div>
                        <div class="mapa-espacios-bg" style="display: flex; flex-direction: column; align-items: center;">
                            <div id="mapa-motos-grid">
                                <!-- Columna 1 -->
                                <?php
                                    $id = 476;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 6;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="476" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:1;grid-row:1;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>476</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '476a';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="476a" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:1;grid-row:2;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <!-- Columna 2 -->
                                <?php
                                    $id = 475;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 6;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="475" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:2;grid-row:1;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>475</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '475a';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="475a" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:2;grid-row:2;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <!-- Columna 3 -->
                                <?php
                                    $id = 474;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 6;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:1;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>474</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '474a';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474a" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:2;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = '474b';
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 1;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="474b" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:3;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>1</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                                <?php
                                    $id = 441;
                                    $ocupados = $moto_ocupados[$id] ?? 0;
                                    $max = 4;
                                    $estado = ($ocupados >= $max) ? 'ocupado' : 'disponible';
                                    $info = "$ocupados/$max cupos reservados";
                                ?>
                                <button type="button" class="espacio-btn moto-btn <?php echo $estado; ?>" data-espacio="441" data-tipo="moto" data-cupos="<?php echo $info; ?>" <?php echo ($estado=='ocupado'?'disabled':''); ?> style="grid-column:3;grid-row:4;position:relative;">
                                    <span class="icono-auto"><i class="fa-solid fa-motorcycle"></i></span>
                                    <span>441</span>
                                    <span class="tooltip-cupos"><?php echo $info; ?></span>
                                </button>
                            </div>
                        </div>
                        <div id="ayuda-espacio-moto" style="font-size: 13px; color: #888; margin-top: 3px; text-align:center;">Selecciona un espacio disponible (verde). Pasa el mouse para ver los cupos ocupados.</div>
                    </div>
                </div>

                <div id="formulario-reserva" class="card form-reserva-oculto">
                    <h2 style="margin-bottom: 18px;"><span style="color:#2563eb;">&#128100;</span> Reservar Espacio <span id="espacio-seleccionado-titulo"></span></h2>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="reservar">
                        <input type="hidden" name="numero_espacio" id="numero_espacio" required>
                        <input type="hidden" name="tipo_vehiculo" id="tipo_vehiculo" value="carro">
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
     <?php require __DIR__ . '/../resources/partials/footer.php'; ?>
    
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

        // Selector de tipo de vehículo y mapas
        const tipoVehiculoRadios = document.getElementsByName('tipo_vehiculo_selector');
        const mapaCarro = document.getElementById('mapa-carro');
        const mapaMoto = document.getElementById('mapa-moto');
        const formTipoVehiculo = document.getElementById('tipo_vehiculo');

        // Mostrar el mapa correcto según selección
        Array.from(tipoVehiculoRadios).forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'carro') {
                    mapaCarro.style.display = 'block';
                    mapaMoto.style.display = 'none';
                    if (formTipoVehiculo) formTipoVehiculo.value = 'carro';
                } else {
                    mapaCarro.style.display = 'none';
                    mapaMoto.style.display = 'block';
                    if (formTipoVehiculo) formTipoVehiculo.value = 'moto';
                }
                // Limpiar selección previa
                document.getElementById('numero_espacio').value = '';
                document.getElementById('formulario-reserva').classList.add('form-reserva-oculto');
            });
        });

        // Selección dinámica de espacio y mostrar formulario
        function activarSeleccionEspacios(selector, tipo) {
            const botones = document.querySelectorAll(selector);
            const inputEspacio = document.getElementById('numero_espacio');
            const formReserva = document.getElementById('formulario-reserva');
            const tituloEspacio = document.getElementById('espacio-seleccionado-titulo');
            const tipoVehiculoInput = document.getElementById('tipo_vehiculo');
            botones.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (btn.hasAttribute('disabled')) return;
                    // Quitar seleccionado de todos los botones de motos
                    document.querySelectorAll('.moto-btn').forEach(b => b.classList.remove('seleccionado'));
                    // Quitar seleccionado de todos los botones de carros
                    document.querySelectorAll('.espacio-btn:not(.moto-btn)').forEach(b => b.classList.remove('seleccionado'));
                    btn.classList.add('seleccionado');
                    inputEspacio.value = btn.getAttribute('data-espacio');
                    tituloEspacio.textContent = btn.getAttribute('data-espacio');
                    if (tipoVehiculoInput) tipoVehiculoInput.value = tipo;
                    formReserva.classList.remove('form-reserva-oculto');
                    formReserva.classList.add('form-reserva-visible');
                    window.scrollTo({ top: formReserva.offsetTop - 40, behavior: 'smooth' });
                    // Mostrar info de cupos en móviles
                    mostrarInfoCuposMovil(btn.getAttribute('data-cupos'));
                });
            });
        }
        activarSeleccionEspacios('#mapa-espacios-carro .espacio-btn.disponible, #mapa-espacios-carro .espacio-btn.seleccionado', 'carro');
        activarSeleccionEspacios('.moto-btn.disponible, .moto-btn.seleccionado', 'moto');

        // Refrescar el mapa de motos tras una reserva exitosa
        const formReserva = document.querySelector('#formulario-reserva form');
        if (formReserva) {
            formReserva.addEventListener('submit', function(e) {
                setTimeout(() => {
                    location.reload();
                }, 500);
            });
        }
    </script>
</body>
</html>