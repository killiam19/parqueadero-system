    <?php require __DIR__ . '/partials/header.php'; ?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Sistema de Parqueadero</title>
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
        
        .fecha-grupo {
            margin-bottom: 30px;
        }
        
        .fecha-titulo {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        
        .reserva-item {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .reserva-item.cancelada {
            border-left-color: #dc3545;
            opacity: 0.7;
        }
        
        .reserva-item.completada {
            border-left-color: #6c757d;
            opacity: 0.8;
        }
        
        .reserva-info h4 {
            margin-bottom: 8px;
            color: #2c3e50;
        }
        
        .reserva-info p {
            margin-bottom: 5px;
        }
        
        .estado-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .estado-activa {
            background-color: #d4edda;
            color: #155724;
        }
        
        .estado-cancelada {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .estado-completada {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .btn-cancelar {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .btn-cancelar:hover {
            background-color: #c82333;
        }
        
        .reserva-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .no-reservas {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Todas las Reservas</h1>
            <p>Gestiona y visualiza todas las reservas del parqueadero</p>
        </div>
        
        <div class="nav">
            <a href="index.php">🏠 Inicio</a>
            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'admin'): ?>
                <a href="usuarios.php">👥 Usuarios</a>
                <a href="admin.php">⚙️ Administración</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje <?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <?php if (empty($reservas_por_fecha)): ?>
                <div class="no-reservas">
                    <h3>No hay reservas registradas</h3>
                    <p>Aún no se han realizado reservas en el sistema.</p>
                </div>
            <?php else: ?>
                <?php foreach ($reservas_por_fecha as $fecha => $reservas_fecha): ?>
                    <div class="fecha-grupo">
                        <div class="fecha-titulo">
                            📅 <?php echo date('l, d \d\e F \d\e Y', strtotime($fecha)); ?>
                            <span style="float: right;">
                                <?php echo count($reservas_fecha); ?> reserva(s) - 
                                <?php echo getCuposDisponibles($fecha); ?> cupos disponibles
                            </span>
                        </div>
                        <?php foreach ($reservas_fecha as $reserva): ?>
                            <div class="reserva-item <?php echo $reserva['estado']; ?>">
                                <div class="reserva-info">
                                    <h4><?php echo $reserva['nombre']; ?></h4>
                                    <p><strong>Horario:</strong> <?php echo date('H:i', strtotime($reserva['hora_inicio'])); ?> - <?php echo date('H:i', strtotime($reserva['hora_fin'])); ?></p>
                                    <p><strong>Placa:</strong> <?php echo $reserva['placa_vehiculo']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $reserva['email']; ?></p>
                                    <p><strong>Reservado el:</strong> <?php echo date('d/m/Y H:i', strtotime($reserva['fecha_creacion'])); ?></p>
                                    <p><strong>Para el día:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha_reserva'])); ?></p>
                                </div>
                                <div class="reserva-actions">
                                    <span class="estado-badge estado-<?php echo $reserva['estado']; ?>">
                                        <?php echo $reserva['estado']; ?>
                                    </span>
                                    <?php if ($reserva['estado'] == 'activa' && $reserva['fecha_reserva'] >= date('Y-m-d')): ?>
                                        <?php if (
                                            (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'admin') ||
                                            (isset($_SESSION['usuario_id']) && $reserva['usuario_id'] == $_SESSION['usuario_id'])
                                        ): ?>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="action" value="cancelar">
                                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                                <button type="submit" class="btn-cancelar" onclick="return confirm('¿Estás seguro de que quieres cancelar esta reserva?')">
                                                    Cancelar
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($reservas_canceladas)): ?>
            <div class="card">
                <h3 style="margin-bottom: 18px; color: #dc3545;">Reservas Canceladas</h3>
                <form method="POST" action="" style="margin-bottom: 12px;">
                    <input type="hidden" name="action" value="limpiar_canceladas">
                    <button type="submit" class="btn-cancelar" style="background:#888;">Limpiar esta vista</button>
                </form>
                <?php foreach ($reservas_canceladas as $reserva): ?>
                    <div class="reserva-item cancelada">
                        <div class="reserva-info">
                            <h4><?php echo $reserva['nombre']; ?></h4>
                            <p><strong>Horario:</strong> <?php echo date('H:i', strtotime($reserva['hora_inicio'])); ?> - <?php echo date('H:i', strtotime($reserva['hora_fin'])); ?></p>
                            <p><strong>Placa:</strong> <?php echo $reserva['placa_vehiculo']; ?></p>
                            <p><strong>Email:</strong> <?php echo $reserva['email']; ?></p>
                            <p><strong>Reservado el:</strong> <?php echo date('d/m/Y H:i', strtotime($reserva['fecha_creacion'])); ?></p>
                            <p><strong>Para el día:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha_reserva'])); ?></p>
                        </div>
                        <div class="reserva-actions">
                            <span class="estado-badge estado-cancelada">Cancelada</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
    
  <?php require __DIR__ . '/partials/footer.php'; ?>
    <?php require __DIR__ . '/partials/new.footer.php'; ?>

